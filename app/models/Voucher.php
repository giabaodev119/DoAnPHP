<?php
require_once 'config/config.php';

class Voucher
{
    private $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }

    // Lấy tất cả voucher
    public function getAllVouchers()
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM vouchers ORDER BY created_at DESC");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("[" . date('Y-m-d H:i:s') . "] Database error in getAllVouchers(): " . $e->getMessage());
            return [];
        }
    }

    // Phân trang voucher
    public function getVouchersByPage($limit, $offset)
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM vouchers ORDER BY created_at DESC LIMIT ? OFFSET ?");
            $stmt->bindParam(1, $limit, PDO::PARAM_INT);
            $stmt->bindParam(2, $offset, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("[" . date('Y-m-d H:i:s') . "] Database error in getVouchersByPage(): " . $e->getMessage());
            return [];
        }
    }

    // Lấy tổng số voucher
    public function getTotalVouchers()
    {
        try {
            $stmt = $this->conn->prepare("SELECT COUNT(*) FROM vouchers");
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("[" . date('Y-m-d H:i:s') . "] Database error in getTotalVouchers(): " . $e->getMessage());
            return 0;
        }
    }

    // Lấy voucher theo ID
    public function getVoucherById($id)
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM vouchers WHERE id = ?");
            $stmt->execute([$id]);
            $voucher = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($voucher) {
                // Lấy danh sách category_id được áp dụng
                $stmt = $this->conn->prepare("SELECT category_id FROM voucher_categories WHERE voucher_id = ?");
                $stmt->execute([$id]);
                $categories = $stmt->fetchAll(PDO::FETCH_COLUMN);
                $voucher['categories'] = $categories;
            }

            return $voucher;
        } catch (PDOException $e) {
            error_log("[" . date('Y-m-d H:i:s') . "] Database error in getVoucherById(): " . $e->getMessage());
            return false;
        }
    }

    // Lấy voucher theo code
    public function getVoucherByCode($code)
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM vouchers WHERE code = ? AND status = 'active'");
            $stmt->execute([$code]);
            $voucher = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($voucher) {
                // Lấy danh sách category_id được áp dụng
                $stmt = $this->conn->prepare("SELECT category_id FROM voucher_categories WHERE voucher_id = ?");
                $stmt->execute([$voucher['id']]);
                $categories = $stmt->fetchAll(PDO::FETCH_COLUMN);
                $voucher['categories'] = $categories;
            }

            return $voucher;
        } catch (PDOException $e) {
            error_log("[" . date('Y-m-d H:i:s') . "] Database error in getVoucherByCode(): " . $e->getMessage());
            return false;
        }
    }

    // Tạo voucher mới
    public function create($code, $discount_type, $discount_value, $min_purchase, $max_discount, $quantity, $start_date, $end_date, $description, $categories)
    {
        try {
            $this->conn->beginTransaction();

            $stmt = $this->conn->prepare("INSERT INTO vouchers (code, discount_type, discount_value, min_purchase, max_discount, quantity, start_date, end_date, description) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$code, $discount_type, $discount_value, $min_purchase, $max_discount, $quantity, $start_date, $end_date, $description]);

            $voucherId = $this->conn->lastInsertId();

            // Thêm danh mục áp dụng
            if (!empty($categories)) {
                $stmt = $this->conn->prepare("INSERT INTO voucher_categories (voucher_id, category_id) VALUES (?, ?)");
                foreach ($categories as $categoryId) {
                    $stmt->execute([$voucherId, $categoryId]);
                }
            }

            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("[" . date('Y-m-d H:i:s') . "] Database error in createVoucher(): " . $e->getMessage());
            return false;
        }
    }

    // Cập nhật voucher
    public function update($id, $code, $discount_type, $discount_value, $min_purchase, $max_discount, $quantity, $start_date, $end_date, $description, $status, $categories)
    {
        try {
            $this->conn->beginTransaction();

            $stmt = $this->conn->prepare("UPDATE vouchers SET code = ?, discount_type = ?, discount_value = ?, min_purchase = ?, max_discount = ?, quantity = ?, start_date = ?, end_date = ?, description = ?, status = ? WHERE id = ?");
            $stmt->execute([$code, $discount_type, $discount_value, $min_purchase, $max_discount, $quantity, $start_date, $end_date, $description, $status, $id]);

            // Xóa các danh mục cũ
            $stmt = $this->conn->prepare("DELETE FROM voucher_categories WHERE voucher_id = ?");
            $stmt->execute([$id]);

            // Thêm lại danh mục mới
            if (!empty($categories)) {
                $stmt = $this->conn->prepare("INSERT INTO voucher_categories (voucher_id, category_id) VALUES (?, ?)");
                foreach ($categories as $categoryId) {
                    $stmt->execute([$id, $categoryId]);
                }
            }

            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("[" . date('Y-m-d H:i:s') . "] Database error in updateVoucher(): " . $e->getMessage());
            return false;
        }
    }

    // Xóa voucher
    public function delete($id)
    {
        try {
            $stmt = $this->conn->prepare("DELETE FROM vouchers WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("[" . date('Y-m-d H:i:s') . "] Database error in deleteVoucher(): " . $e->getMessage());
            return false;
        }
    }

    // Kiểm tra voucher hợp lệ
    public function validateVoucher($code, $userId, $cartItems)
    {
        try {
            // Lấy thông tin voucher
            $voucher = $this->getVoucherByCode($code);
            if (!$voucher) {
                return ['valid' => false, 'message' => 'Mã giảm giá không tồn tại hoặc đã hết hạn'];
            }

            // Kiểm tra số lượng
            if ($voucher['quantity'] <= $voucher['used_count']) {
                return ['valid' => false, 'message' => 'Mã giảm giá đã hết lượt sử dụng'];
            }

            // Kiểm tra thời gian hiệu lực
            $currentDate = date('Y-m-d H:i:s');
            if ($voucher['start_date'] && $currentDate < $voucher['start_date']) {
                return ['valid' => false, 'message' => 'Mã giảm giá chưa có hiệu lực'];
            }
            if ($voucher['end_date'] && $currentDate > $voucher['end_date']) {
                return ['valid' => false, 'message' => 'Mã giảm giá đã hết hạn'];
            }

            // Tính tổng giá trị đơn hàng hợp lệ cho voucher
            $cartTotal = 0;
            $voucherCategories = $voucher['categories'] ?? [];

            foreach ($cartItems as $item) {
                $productModel = new Product();
                $product = $productModel->getProductById($item->product_id);

                // Nếu voucher không giới hạn danh mục hoặc sản phẩm thuộc danh mục được chọn
                if (empty($voucherCategories) || in_array($product['category_id'], $voucherCategories)) {
                    $cartTotal += $item->price * $item->quantity;
                }
            }

            // Kiểm tra giá trị đơn hàng tối thiểu
            if ($cartTotal <= $voucher['min_purchase']) {
                return ['valid' => false, 'message' => 'Giá trị đơn hàng hợp lệ chưa đạt tối thiểu ' . number_format($voucher['min_purchase'], 0, ',', '.') . ' đ'];
            }

            // Kiểm tra người dùng đã sử dụng voucher này chưa
            $stmt = $this->conn->prepare("SELECT COUNT(*) FROM voucher_usage WHERE voucher_id = ? AND user_id = ?");
            $stmt->execute([$voucher['id'], $userId]);
            $usedCount = $stmt->fetchColumn();
            if ($usedCount > 0) {
                return ['valid' => false, 'message' => 'Bạn đã sử dụng mã giảm giá này'];
            }

            // Tính toán số tiền giảm
            $discountAmount = 0;
            if ($voucher['discount_type'] == 'percentage') {
                $discountAmount = $cartTotal * ($voucher['discount_value'] / 100);

                // Áp dụng giảm giá tối đa nếu có
                if ($voucher['max_discount'] && $discountAmount > $voucher['max_discount']) {
                    $discountAmount = $voucher['max_discount'];
                }
            } else {
                $discountAmount = $voucher['discount_value'];

                // Đảm bảo số tiền giảm không vượt quá tổng giá trị đơn hàng
                if ($discountAmount > $cartTotal) {
                    $discountAmount = $cartTotal;
                }
            }

            return [
                'valid' => true,
                'discount_amount' => $discountAmount,
                'voucher' => $voucher,
                'applicable_total' => $cartTotal
            ];
        } catch (PDOException $e) {
            error_log("[" . date('Y-m-d H:i:s') . "] Database error in validateVoucher(): " . $e->getMessage());
            return ['valid' => false, 'message' => 'Lỗi hệ thống, vui lòng thử lại sau'];
        }
    }

    // Áp dụng voucher vào đơn hàng
    public function applyVoucher($voucherId, $userId, $orderId, $discountAmount)
    {
        try {
            $this->conn->beginTransaction();

            // Ghi nhận việc sử dụng voucher
            $stmt = $this->conn->prepare("INSERT INTO voucher_usage (voucher_id, user_id, order_id, discount_amount) VALUES (?, ?, ?, ?)");
            $stmt->execute([$voucherId, $userId, $orderId, $discountAmount]);

            // Tăng số lượng đã sử dụng
            $stmt = $this->conn->prepare("UPDATE vouchers SET used_count = used_count + 1 WHERE id = ?");
            $stmt->execute([$voucherId]);

            // Cập nhật tổng tiền đơn hàng
            $stmt = $this->conn->prepare("UPDATE orders SET total_price = total_price - ? WHERE id = ?");
            $stmt->execute([$discountAmount, $orderId]);

            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("[" . date('Y-m-d H:i:s') . "] Database error in applyVoucher(): " . $e->getMessage());
            return false;
        }
    }
}
