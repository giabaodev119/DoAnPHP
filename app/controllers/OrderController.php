<?php
require_once 'app/models/Order.php';
require_once 'config/config.php';
require_once 'app/controllers/BaseController.php';

class OrderController extends BaseController
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
    public function details($id = null)
    {
        $this->ensureUserLoggedIn();

        // Get order ID from URL if not passed directly
        if (!$id) {
            $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        }

        if (!$id) {
            $_SESSION['error'] = 'Mã đơn hàng không hợp lệ';
            header('Location: index.php?controller=order&action=history');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $order = $this->orderModel->getOrderDetails($id);

        // Verify this order belongs to the current user
        if (!$order || $order['user_id'] != $userId) {
            $_SESSION['error'] = 'Bạn không có quyền xem đơn hàng này';
            header('Location: index.php?controller=order&action=history');
            exit;
        }

        // Get order items
        $orderData = $this->orderModel->getOrderItems($id);
        $orderItems = $orderData['items'] ?? [];
        $orderInfo = $orderData['order_info'] ?? [];

        $orderId = $id; // For the view to use
        require_once 'app/views/orders/detail.php';
    }

    /**
     * Display order history
     */
    public function history()
    {
        $this->ensureNotAdmin();
        $this->ensureUserLoggedIn();
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
        $this->ensureNotAdmin();
        $this->ensureUserLoggedIn();
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
        $this->ensureNotAdmin();
        $this->ensureUserLoggedIn();
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

    public function checkout()
    {
        $this->ensureNotAdmin();
        $this->ensureUserLoggedIn();
        $userId = $_SESSION['user_id'];
        $cartModel = new Cart();
        $cartItems = $cartModel->getCartItems($userId);

        if (empty($cartItems)) {
            $_SESSION['error'] = 'Giỏ hàng của bạn đang trống!';
            header('Location: index.php?controller=cart');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Xử lý thanh toán COD
            $cartTotal = $cartModel->getCartTotal($userId);
            $voucherId = null;
            $discountAmount = 0;

            // Kiểm tra xem có voucher được áp dụng không
            if (isset($_SESSION['voucher'])) {
                $voucherId = $_SESSION['voucher']['id'];
                $discountAmount = $_SESSION['voucher']['discount_amount'];
            }

            $orderId = $this->orderModel->checkout($userId, $voucherId, $discountAmount);

            if ($orderId) {
                // Xóa voucher khỏi session sau khi đã áp dụng
                unset($_SESSION['voucher']);

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
}
