<?php
require_once 'app/models/Product.php';
require_once 'app/models/Category.php';
require_once 'app/models/User.php'; // Thêm dòng này để nạp lớp User
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
        $products = $productModel->getAllProducts();
        require_once 'app/views/admin/product/index.php';
    }

    public function categories()
    {
        $categoryModel = new Category();
        $categories = $categoryModel->getAll();
        require_once 'app/views/admin/categories/index.php';
    }
    public function users() {
        $userModel = new User();
        $users = $userModel->getAllUsers();
        require_once 'app/views/admin/users/index.php';
    }
  public function banners() {
    $bannerModel = new SideBanner();
    $banners = $bannerModel->getAllBanners();
    require_once 'app/views/admin/banner/index.php';
}
}