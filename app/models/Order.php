<?php
require_once 'config/config.php';
require_once 'config/vnpayconfig.php';
class Order {
    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

   
    public function checkout($userId) {
        try {
            $this->conn->beginTransaction();

            // Calculate total price
            $stmt = $this->conn->prepare("SELECT SUM(p.price * c.quantity) AS total FROM cart c 
                                        JOIN products p ON c.product_id = p.id WHERE c.user_id = ?");
            $stmt->execute([$userId]);
            $totalPrice = $stmt->fetchColumn();

            if (!$totalPrice) {
                throw new Exception("Cart is empty.");
            }

            // Insert order without payment method
            $stmt = $this->conn->prepare("INSERT INTO orders (user_id, total_price, status) VALUES (?, ?, 'pending')");
            $stmt->execute([$userId, $totalPrice]);
            $orderId = $this->conn->lastInsertId();

            // Move cart items to order_items
            $stmt = $this->conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) 
                                        SELECT ?, c.product_id, c.quantity, p.price FROM cart c 
                                        JOIN products p ON c.product_id = p.id WHERE c.user_id = ?");
            $stmt->execute([$orderId, $userId]);

            // Clear cart
            $stmt = $this->conn->prepare("DELETE FROM cart WHERE user_id = ?");
            $stmt->execute([$userId]);

            $this->conn->commit();
            return $orderId;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    /**
     * Get order details
     */
    public function getOrderDetails($orderId) {
        $stmt = $this->conn->prepare("SELECT * FROM orders WHERE id = ?");
        $stmt->execute([$orderId]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$order) return null;

        $stmt = $this->conn->prepare("SELECT oi.*, p.name AS product_name FROM order_items oi 
                                    JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
        $stmt->execute([$orderId]);
        $order['items'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $order;
    }

    /**
     * Get order history for a user
     */
    public function getOrderHistory($userId) {
        $stmt = $this->conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function checkoutWithVNPAY($userId) {
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

}
?>
