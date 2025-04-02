<?php
require_once 'app/models/Product.php';
require_once 'app/models/Category.php';
require_once 'app/models/User.php'; // Thêm dòng này để nạp lớp User
require_once 'app/models/Order.php';

class AdminController
{
    public function __construct()
    {
        // Ensure session is started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Check if user is logged in and has admin role
        if (!isset($_SESSION['logged_in']) || $_SESSION['user_role'] !== 'admin') {
            // Set error message
            $_SESSION['error'] = "Bạn cần đăng nhập với quyền admin để truy cập trang quản trị.";

            // Load the admin access error page instead of redirecting
            require_once 'app/views/partials/adminaccesserror.php';
            exit();
        }
    }
    public function dashboard()
    {
        require_once 'app/views/admin/dashboard.php';
    }

    public function products()
    {
        $productModel = new Product();
        $products = $productModel->getAllProducts();
        require_once 'app/views/admin/product/index.php';
    }

    public function categories()
    {
        $categoryModel = new Category();
        $categories = $categoryModel->getAll();
        require_once 'app/views/admin/categories/index.php';
    }
    public function users()
    {
        $userModel = new User();
        $users = $userModel->getAllUsers();
        require_once 'app/views/admin/users/index.php';
    }
    /**
     * Hiển thị danh sách tất cả đơn hàng
     */
    public function orders()
    {
        $orderModel = new Order();
        $orders = $orderModel->getAllOrders();

        // Lấy thêm thông tin người dùng để hiển thị
        $userModel = new User();

        require_once 'app/views/admin/orders/index.php';
    }

    /**
     * Hiển thị chi tiết đơn hàng
     */
    public function orderDetail()
    {
        if (!isset($_GET['id'])) {
            $_SESSION['error'] = 'ID đơn hàng không hợp lệ';
            header('Location: index.php?controller=admin&action=orders');
            exit;
        }

        $orderId = intval($_GET['id']);
        $orderModel = new Order();
        $order = $orderModel->getOrderDetails($orderId);

        if (!$order) {
            $_SESSION['error'] = 'Không tìm thấy đơn hàng';
            header('Location: index.php?controller=admin&action=orders');
            exit;
        }

        $orderItems = $orderModel->getOrderItems($orderId);
        require_once 'app/views/admin/orders/detail.php';
    }

    /**
     * Cập nhật trạng thái đơn hàng
     */
    public function updateOrderStatus()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['error'] = 'Phương thức không hợp lệ';
            header('Location: index.php?controller=admin&action=orders');
            exit;
        }

        $orderId = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
        $status = isset($_POST['status']) ? trim($_POST['status']) : '';

        // Kiểm tra dữ liệu đầu vào
        if (!$orderId || empty($status)) {
            $_SESSION['error'] = 'Dữ liệu không hợp lệ';
            header('Location: index.php?controller=admin&action=orders');
            exit;
        }

        // Các trạng thái hợp lệ
        $validStatuses = ['pending', 'processing', 'ready_to_ship', 'shipping', 'completed', 'cancelled'];
        if (!in_array($status, $validStatuses)) {
            $_SESSION['error'] = 'Trạng thái không hợp lệ';
            header('Location: index.php?controller=admin&action=orderDetail&id=' . $orderId);
            exit;
        }

        $orderModel = new Order();
        $result = $orderModel->updateOrderStatus($orderId, $status);

        if ($result) {
            $_SESSION['message'] = 'Cập nhật trạng thái đơn hàng thành công';
        } else {
            $_SESSION['error'] = 'Không thể cập nhật trạng thái đơn hàng';
        }

        header('Location: index.php?controller=admin&action=orderDetail&id=' . $orderId);
        exit;
    }
}
