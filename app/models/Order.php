<?php
require_once 'config/config.php';
require_once 'config/vnpayconfig.php';
require_once 'app/models/Cart.php';
require_once 'app/models/Product.php';
require_once 'app/models/Voucher.php';

class Order
{
    private $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }



    /**
     * Lấy chi tiết đơn hàng kèm thông tin khách hàng
     * @param int $orderId ID đơn hàng
     * @return array|false Chi tiết đơn hàng hoặc false nếu không tìm thấy
     */
    public function getOrderDetails($orderId)
    {
        try {
            $stmt = $this->conn->prepare("
            SELECT o.*, u.name AS user_name, u.email AS user_email
            FROM orders o
            JOIN users u ON o.user_id = u.id
            WHERE o.id = ?
        ");
            $stmt->execute([$orderId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("[" . date('Y-m-d H:i:s') . "] Database error in getOrderDetails(): " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get order history for a user
     */
    public function getOrderHistory($userId)
    {
        $stmt = $this->conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function checkoutWithVNPAY($userId)
    {
        try {
            $this->conn->beginTransaction();

            // Lấy tổng số tiền giỏ hàng
            $stmt = $this->conn->prepare("SELECT SUM(p.price * c.quantity) AS total FROM cart c 
                                        JOIN products p ON c.product_id = p.id WHERE c.user_id = ?");
            $stmt->execute([$userId]);
            $totalPrice = $stmt->fetchColumn();

            if (!$totalPrice) {
                throw new Exception("Giỏ hàng trống.");
            }

            // Tạo đơn hàng với trạng thái 'pending'
            $stmt = $this->conn->prepare("INSERT INTO orders (user_id, total_price, status) VALUES (?, ?, 'pending')");
            $stmt->execute([$userId, $totalPrice]);
            $orderId = $this->conn->lastInsertId();

            // Di chuyển sản phẩm từ giỏ hàng sang order_items
            $stmt = $this->conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) 
                                        SELECT ?, c.product_id, c.quantity, p.price FROM cart c 
                                        JOIN products p ON c.product_id = p.id WHERE c.user_id = ?");
            $stmt->execute([$orderId, $userId]);

            // Xóa giỏ hàng sau khi tạo đơn
            $stmt = $this->conn->prepare("DELETE FROM cart WHERE user_id = ?");
            $stmt->execute([$userId]);

            $this->conn->commit();

            // Chuyển hướng trực tiếp đến VNPAY
            $paymentUrl = "config/vnpay_create_payment.php?order_id=$orderId&amount=$totalPrice";
            header("Location: $paymentUrl");
            exit;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    /**
     * Xử lý checkout
     * @param int $userId ID của người dùng
     * @param int|null $voucherId ID của voucher (nếu có)
     * @param float $discountAmount Số tiền giảm giá từ voucher
     * @return int|bool ID của đơn hàng mới nếu thành công, False nếu thất bại
     */
    public function checkout($userId, $voucherId = null, $discountAmount = 0)
    {
        $cartModel = new Cart();
        $productModel = new Product();

        try {
            $this->conn->beginTransaction();

            // 1. Lấy các sản phẩm từ giỏ hàng
            $cartItems = $cartModel->getCartItems($userId);
            if (empty($cartItems)) {
                $this->conn->rollBack();
                $_SESSION['error'] = "Giỏ hàng của bạn đang trống.";
                return false;
            }

            // 2. Kiểm tra tồn kho của tất cả sản phẩm
            foreach ($cartItems as $item) {
                $size = isset($item->size) ? $item->size : null;
                $stockCheck = $productModel->checkStock($item->product_id, $item->quantity, $size);
                if (!$stockCheck) {
                    $this->conn->rollBack();
                    $_SESSION['error'] = "Sản phẩm '{$item->product_name}'" . ($size ? " (Size $size)" : "") . " không đủ số lượng trong kho hàng.";
                    return false;
                }
            }

            // 3. Tính tổng giá trị đơn hàng
            $totalAmount = 0;
            foreach ($cartItems as $item) {
                $totalAmount += $item->price * $item->quantity;
            }

            // Trừ số tiền giảm giá (nếu có)
            $finalAmount = max(0, $totalAmount - $discountAmount);

            // 4. Tạo đơn hàng mới
            $stmt = $this->conn->prepare("INSERT INTO orders (user_id, total_price, status, created_at) VALUES (?, ?, 'pending', NOW())");
            if (!$stmt->execute([$userId, $finalAmount])) {
                $this->conn->rollBack();
                error_log("Lỗi khi tạo đơn hàng mới: " . print_r($stmt->errorInfo(), true));
                $_SESSION['error'] = "Không thể tạo đơn hàng. Vui lòng thử lại sau.";
                return false;
            }

            $orderId = $this->conn->lastInsertId();

            // 5. Tạo chi tiết đơn hàng và cập nhật tồn kho
            foreach ($cartItems as $item) {
                // Thêm chi tiết đơn hàng
                $size = isset($item->size) ? $item->size : null;
                $stmt = $this->conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price, size) VALUES (?, ?, ?, ?, ?)");
                if (!$stmt->execute([$orderId, $item->product_id, $item->quantity, $item->price, $size])) {
                    $this->conn->rollBack();
                    error_log("Lỗi khi thêm chi tiết đơn hàng: " . print_r($stmt->errorInfo(), true));
                    $_SESSION['error'] = "Không thể tạo chi tiết đơn hàng. Vui lòng thử lại sau.";
                    return false;
                }

                // Giảm số lượng tồn kho thủ công để tránh lỗi
                try {
                    if ($size) {
                        // Trường hợp có size cụ thể
                        $updateStmt = $this->conn->prepare("UPDATE product_sizes SET stock = stock - ? WHERE product_id = ? AND size = ? AND stock >= ?");
                        $success = $updateStmt->execute([$item->quantity, $item->product_id, $size, $item->quantity]);
                    } else {
                        // Trường hợp không có size - phân bổ số lượng vào size có sẵn
                        // (thường không nên xảy ra, nhưng xử lý để tránh lỗi)
                        $sizeStmt = $this->conn->prepare("
                            SELECT size, stock FROM product_sizes 
                            WHERE product_id = ? AND stock > 0 
                            ORDER BY stock DESC LIMIT 1
                        ");
                        $sizeStmt->execute([$item->product_id]);
                        $availableSize = $sizeStmt->fetch(PDO::FETCH_ASSOC);

                        if (!$availableSize) {
                            throw new Exception("Không có size nào có sẵn cho sản phẩm này");
                        }

                        $updateStmt = $this->conn->prepare("
                            UPDATE product_sizes 
                            SET stock = stock - ? 
                            WHERE product_id = ? AND size = ? AND stock >= ?
                        ");
                        $success = $updateStmt->execute([
                            $item->quantity,
                            $item->product_id,
                            $availableSize['size'],
                            $item->quantity
                        ]);
                    }

                    if (!$success || $updateStmt->rowCount() == 0) {
                        throw new Exception("Không thể cập nhật số lượng tồn kho");
                    }
                } catch (Exception $e) {
                    $this->conn->rollBack();
                    error_log("Lỗi cập nhật tồn kho: " . $e->getMessage());
                    $_SESSION['error'] = "Lỗi khi cập nhật tồn kho. Vui lòng thử lại sau.";
                    return false;
                }
            }

            // 6. Lưu thông tin voucher nếu có
            if ($voucherId && $discountAmount > 0) {
                try {
                    $stmt = $this->conn->prepare("INSERT INTO voucher_usage (voucher_id, user_id, order_id, discount_amount) VALUES (?, ?, ?, ?)");
                    if (!$stmt->execute([$voucherId, $userId, $orderId, $discountAmount])) {
                        throw new Exception("Không thể lưu thông tin sử dụng voucher");
                    }

                    // Tăng số lượng đã sử dụng của voucher
                    $stmt = $this->conn->prepare("UPDATE vouchers SET used_count = used_count + 1 WHERE id = ?");
                    if (!$stmt->execute([$voucherId])) {
                        throw new Exception("Không thể cập nhật số lượng voucher đã sử dụng");
                    }
                } catch (Exception $e) {
                    $this->conn->rollBack();
                    error_log("Lỗi voucher: " . $e->getMessage());
                    $_SESSION['error'] = "Lỗi khi áp dụng mã giảm giá. Vui lòng thử lại.";
                    return false;
                }
            }

            // 7. Xóa giỏ hàng
            $stmt = $this->conn->prepare("DELETE FROM cart WHERE user_id = ?");
            if (!$stmt->execute([$userId])) {
                $this->conn->rollBack();
                error_log("Lỗi khi xóa giỏ hàng: " . print_r($stmt->errorInfo(), true));
                $_SESSION['error'] = "Không thể hoàn tất đơn hàng. Vui lòng thử lại sau.";
                return false;
            }

            // Hoàn tất giao dịch
            $this->conn->commit();
            return $orderId;
        } catch (PDOException $e) {
            // Đảm bảo rollback nếu có lỗi
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            // Log chi tiết lỗi
            error_log("Lỗi database trong checkout(): " . $e->getMessage() . "\n" . $e->getTraceAsString());
            $_SESSION['error'] = "Lỗi hệ thống: " . $e->getMessage();
            return false;
        } catch (Exception $e) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            error_log("Lỗi chung trong checkout(): " . $e->getMessage());
            $_SESSION['error'] = "Lỗi xử lý đơn hàng: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Cập nhật trạng thái đơn hàng thành completed khi người dùng xác nhận đã nhận hàng
     * @param int $orderId ID của đơn hàng
     * @param int $userId ID của người dùng (để xác thực quyền)
     * @return bool True nếu cập nhật thành công, False nếu thất bại
     */
    public function confirmOrderReceived($orderId, $userId)
    {
        try {
            // Kiểm tra đơn hàng có thuộc về người dùng không
            $stmt = $this->conn->prepare("SELECT id FROM orders WHERE id = ? AND user_id = ? AND status = 'shipping'");
            $stmt->execute([$orderId, $userId]);

            if ($stmt->rowCount() == 0) {
                return false;
            }

            // Cập nhật trạng thái đơn hàng thành completed
            $stmt = $this->conn->prepare("UPDATE orders SET status = 'completed' WHERE id = ?");
            return $stmt->execute([$orderId]);
        } catch (PDOException $e) {
            error_log("[" . date('Y-m-d H:i:s') . "] Database error in confirmOrderReceived(): " . $e->getMessage());
            return false;
        }
    }
    /**
     * Lấy tất cả đơn hàng cho trang admin
     * @return array Danh sách các đơn hàng
     */
    public function getAllOrders()
    {
        try {
            $stmt = $this->conn->prepare("
            SELECT o.*, u.name as user_name, u.email as user_email,
            (SELECT COUNT(*) FROM order_items WHERE order_id = o.id) as total_items
            FROM orders o
            LEFT JOIN users u ON o.user_id = u.id
            ORDER BY o.created_at DESC
        ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("[" . date('Y-m-d H:i:s') . "] Database error in getAllOrders(): " . $e->getMessage());
            return [];
        }
    }

    /**
     * Lấy các mục trong đơn hàng
     * @param int $orderId ID đơn hàng
     * @return array Các mục trong đơn hàng
     */
    public function getOrderItems($orderId)
    {
        try {
            $stmt = $this->conn->prepare("
            SELECT oi.*, p.name as product_name, p.slug as product_slug,
            (SELECT image_path FROM product_images WHERE product_id = oi.product_id LIMIT 1) as image_path
            FROM order_items oi
            JOIN products p ON oi.product_id = p.id
            WHERE oi.order_id = ?
        ");
            $stmt->execute([$orderId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("[" . date('Y-m-d H:i:s') . "] Database error in getOrderItems(): " . $e->getMessage());
            return [];
        }
    }

    /**
     * Cập nhật trạng thái đơn hàng
     * @param int $orderId ID đơn hàng
     * @param string $status Trạng thái mới
     * @return bool Kết quả cập nhật
     */
    public function updateOrderStatus($orderId, $status)
    {
        try {
            $stmt = $this->conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
            return $stmt->execute([$status, $orderId]);
        } catch (PDOException $e) {
            error_log("[" . date('Y-m-d H:i:s') . "] Database error in updateOrderStatus(): " . $e->getMessage());
            return false;
        }
    }
    public function getTotalOrders()
    {
        try {
            $stmt = $this->conn->prepare("SELECT COUNT(*) FROM orders");
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("[" . date('Y-m-d H:i:s') . "] Database error in getTotalOrders(): " . $e->getMessage());
            return 0;
        }
    }

    public function getOrdersByPage($limit, $offset)
    {
        try {
            $stmt = $this->conn->prepare("
                SELECT o.*, u.name as user_name, u.email as user_email,
                (SELECT COUNT(*) FROM order_items WHERE order_id = o.id) as total_items
                FROM orders o
                LEFT JOIN users u ON o.user_id = u.id
                ORDER BY o.created_at DESC
                LIMIT ? OFFSET ?
            ");
            $stmt->bindParam(1, $limit, PDO::PARAM_INT);
            $stmt->bindParam(2, $offset, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("[" . date('Y-m-d H:i:s') . "] Database error in getOrdersByPage(): " . $e->getMessage());
            return [];
        }
    }
}
