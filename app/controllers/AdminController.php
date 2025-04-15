<?php
require_once 'app/models/Product.php';
require_once 'app/models/Category.php';
require_once 'app/models/User.php'; // Thêm dòng này để nạp lớp User
require_once 'app/models/Order.php';
require_once 'app/models/Banner.php';
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

        // Xác định trang hiện tại
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $page = max(1, $page); // Đảm bảo trang không nhỏ hơn 1

        // Thiết lập số lượng sản phẩm mỗi trang
        $itemsPerPage = 10;

        // Tính offset
        $offset = ($page - 1) * $itemsPerPage;

        // Lấy tổng số sản phẩm
        $totalProducts = $productModel->getTotalProducts();

        // Tính tổng số trang
        $totalPages = ceil($totalProducts / $itemsPerPage);

        // Lấy sản phẩm cho trang hiện tại
        $products = $productModel->getProductsByPage($itemsPerPage, $offset);

        // Truyền các biến vào view
        require_once 'app/views/admin/product/index.php';
    }

    public function categories()
    {
        $categoryModel = new Category();

        // Xác định trang hiện tại
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $page = max(1, $page); // Đảm bảo trang không nhỏ hơn 1

        // Thiết lập số lượng danh mục mỗi trang
        $itemsPerPage = 10;

        // Tính offset
        $offset = ($page - 1) * $itemsPerPage;

        // Lấy tổng số danh mục
        $totalCategories = $categoryModel->getTotalCategories();

        // Tính tổng số trang
        $totalPages = ceil($totalCategories / $itemsPerPage);

        // Lấy danh mục cho trang hiện tại
        $categories = $categoryModel->getCategoriesByPage($itemsPerPage, $offset);

        require_once 'app/views/admin/categories/index.php';
    }
    public function users()
    {
        $userModel = new User();

        // Xác định trang hiện tại
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $page = max(1, $page); // Đảm bảo trang không nhỏ hơn 1

        // Thiết lập số lượng người dùng mỗi trang
        $itemsPerPage = 10;

        // Tính offset
        $offset = ($page - 1) * $itemsPerPage;

        // Lấy tổng số người dùng
        $totalUsers = $userModel->getTotalUsers();

        // Tính tổng số trang
        $totalPages = ceil($totalUsers / $itemsPerPage);

        // Lấy người dùng cho trang hiện tại
        $users = $userModel->getUsersByPage($itemsPerPage, $offset);

        require_once 'app/views/admin/users/index.php';
    }
    /**
     * Hiển thị danh sách tất cả đơn hàng
     */
    public function orders()
    {
        $orderModel = new Order();

        // Xác định trang hiện tại
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $page = max(1, $page); // Đảm bảo trang không nhỏ hơn 1

        // Thiết lập số lượng đơn hàng mỗi trang
        $itemsPerPage = 10;

        // Tính offset
        $offset = ($page - 1) * $itemsPerPage;

        // Lấy tổng số đơn hàng
        $totalOrders = $orderModel->getTotalOrders();

        // Tính tổng số trang
        $totalPages = ceil($totalOrders / $itemsPerPage);

        // Lấy đơn hàng cho trang hiện tại
        $orders = $orderModel->getOrdersByPage($itemsPerPage, $offset);

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

        // Kiểm tra trạng thái hiện tại
        $orderModel = new Order();
        $order = $orderModel->getOrderDetails($orderId);

        // Ngăn cập nhật nếu đơn hàng đã hoàn thành
        if ($order && $order['status'] === 'completed') {
            $_SESSION['error'] = 'Không thể cập nhật đơn hàng đã hoàn thành';
            header('Location: index.php?controller=admin&action=orderDetail&id=' . $orderId);
            exit;
        }

        // Các trạng thái hợp lệ
        $validStatuses = ['pending', 'processing', 'ready_to_ship', 'shipping', 'completed', 'cancelled'];
        if (!in_array($status, $validStatuses)) {
            $_SESSION['error'] = 'Trạng thái không hợp lệ';
            header('Location: index.php?controller=admin&action=orderDetail&id=' . $orderId);
            exit;
        }

        $result = $orderModel->updateOrderStatus($orderId, $status);

        if ($result) {
            $_SESSION['message'] = 'Cập nhật trạng thái đơn hàng thành công';
        } else {
            $_SESSION['error'] = 'Không thể cập nhật trạng thái đơn hàng';
        }

        header('Location: index.php?controller=admin&action=orderDetail&id=' . $orderId);
        exit;
    }
    public function banners()
    {
        $bannerModel = new Banner();
        $banners = $bannerModel->getAllBanners();
        require_once 'app/views/admin/banner/index.php';
    }
}
