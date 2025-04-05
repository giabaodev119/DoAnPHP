<?php
require_once 'app/models/Order.php';
require_once 'config/config.php';

class OrderController
{
    private $orderModel;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Pass the database connection to the Order model
        global $db;
        $this->orderModel = new Order();
    }

    /**
     * Process checkout and choose payment method
     */
    public function checkout()
    {
        if (!$this->isUserLoggedIn()) {
            $_SESSION['error'] = 'You must be logged in to checkout';
            header('Location: index.php?controller=cart');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user_id'];
            $orderId = $this->orderModel->checkout($userId);

            if ($orderId) {
                $_SESSION['order_id'] = $orderId;
                $_SESSION['message'] = 'Đơn hàng đã được đặt thành công!';
                header("Location: index.php?controller=order&action=details&id=" . $orderId);
            } else {
                // Nếu không có thông báo lỗi cụ thể từ model
                if (!isset($_SESSION['error'])) {
                    $_SESSION['error'] = 'Đặt hàng thất bại. Vui lòng thử lại.';
                }
                header('Location: index.php?controller=cart');
            }
            exit;
        }

        $data['title'] = 'Checkout';
        require_once 'app/views/orders/checkout.php';
    }

    /**
     * Payment selection page
     */
    public function payment()
    {
        if (!$this->isUserLoggedIn() || !isset($_SESSION['order_id'])) {
            $_SESSION['error'] = 'Invalid action';
            header('Location: index.php?controller=cart');
            exit;
        }

        $data['title'] = 'Choose Payment Method';
        require_once 'app/views/order/payment.php';
    }

    /**
     * View order details
     */
    public function details()
    {
        if (!$this->isUserLoggedIn()) {
            $_SESSION['error'] = 'You must be logged in to view order details';
            header('Location: index.php?controller=home');
            exit;
        }

        $orderId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        if (!$orderId) {
            $_SESSION['error'] = 'Invalid order ID';
            header('Location: index.php?controller=order&action=history');
            exit;
        }

        $data = [];
        $data['order'] = $this->orderModel->getOrderDetails($orderId);

        if (!$data['order']) {
            $_SESSION['error'] = 'Order not found';
            header('Location: index.php?controller=order&action=history');
            exit;
        }

        $data['title'] = 'Order Details';
        require_once 'app/views/orders/detail.php';
    }

    /**
     * Display order history
     */
    public function history()
    {
        if (!$this->isUserLoggedIn()) {
            $_SESSION['error'] = 'You must be logged in to view order history';
            header('Location: index.php?controller=home');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $data = [];
        $data['orders'] = $this->orderModel->getOrderHistory($userId);
        $data['title'] = 'Order History';

        require_once 'app/views/orders/history.php';
    }

    /**
     * Check if user is logged in
     */
    private function isUserLoggedIn()
    {
        return isset($_SESSION['user_id']);
    }
    public function checkoutWithVNPAY()
    {
        require_once __DIR__ . '/../config/vnpayconfig.php';

        error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
        date_default_timezone_set('Asia/Ho_Chi_Minh');

        $vnp_TxnRef = rand(1, 10000); // Mã giao dịch
        $vnp_Amount = $_POST['amount'] * 100; // Số tiền (VND)
        $vnp_Locale = $_POST['language'] ?? 'vn';
        $vnp_BankCode = $_POST['bankCode'] ?? '';
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => "Thanh toan GD: $vnp_TxnRef",
            "vnp_OrderType" => "other",
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
            "vnp_ExpireDate" => $expire
        );

        if (!empty($vnp_BankCode)) {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }

        ksort($inputData);
        $query = http_build_query($inputData);

        if (isset($vnp_HashSecret)) {
            $hashdata = urldecode(http_build_query($inputData));
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
            $query .= '&vnp_SecureHash=' . $vnpSecureHash;
        }


        echo $vnp_Url . '?' . $query;
        exit();
    }
    /**
     * Xác nhận đã nhận hàng
     */
    public function confirmReceipt()
    {
        if (!$this->isUserLoggedIn()) {
            $_SESSION['error'] = 'Bạn phải đăng nhập để xác nhận đơn hàng';
            header('Location: index.php?controller=user&action=login');
            exit;
        }

        $orderId = isset($_POST['order_id']) ? (int) $_POST['order_id'] : 0;
        if (!$orderId) {
            $_SESSION['error'] = 'Mã đơn hàng không hợp lệ';
            header('Location: index.php?controller=order&action=history');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $result = $this->orderModel->confirmOrderReceived($orderId, $userId);

        if ($result) {
            $_SESSION['message'] = 'Cảm ơn bạn đã xác nhận! Đơn hàng đã được đánh dấu là hoàn thành.';
        } else {
            $_SESSION['error'] = 'Không thể xác nhận đơn hàng. Vui lòng thử lại hoặc liên hệ hỗ trợ.';
        }

        header('Location: index.php?controller=order&action=history');
        exit;
    }
}
