<?php

// Nạp các controller cần thiết
require_once 'app/controllers/HomeController.php';
require_once 'app/controllers/ProductController.php';
require_once 'app/controllers/CategoryController.php'; // Thêm dòng này
require_once 'app/controllers/AdminController.php';
require_once 'app/controllers/UserController.php';
require_once 'app/controllers/CartController.php';
require_once 'app/controllers/OrderController.php';
require_once 'app/controllers/SideBannerController.php';
require_once 'app/controllers/VoucherController.php';
require_once 'app/controllers/PromotionController.php';
// Có thể bổ sung thêm nếu cần: UserController, OrderController, ...

// Lấy controller và action từ URL, mặc định là trang chủ
$controller = isset($_GET['controller']) ? $_GET['controller'] : 'home';
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

// Nạp header trước khi gọi controller


// Điều hướng theo controller
switch ($controller) {
    case 'home':
        $homeController = new HomeController();
        if ($action == 'index') {
            $homeController->index();
        }
        break;

    case 'product':
        $productController = new ProductController();
        if ($action == 'index') {
            $productController->index();
        } elseif ($action == 'detail' && isset($_GET['id'])) {
            $productController->detail($_GET['id']);
        } elseif ($action === 'create') {
            $productController->create();
        } elseif ($action === 'edit' && isset($_GET['id'])) {
            $productController->edit($_GET['id']);
        } elseif ($action === 'delete' && isset($_GET['id'])) {
            $productController->delete($_GET['id']);
        } elseif ($action === 'addToCart' && isset($_GET['id'])) {
            $productController->addToCart($_GET['id']);
        } elseif ($action === 'search') { // Thêm xử lý tìm kiếm
            $productController->search();
        }
        break;
    case 'category':
        $categoryController = new CategoryController();
        if ($action === 'index') {
            $categoryController->index();
        } elseif ($action === 'create') {
            $categoryController->create();
        } elseif ($action === 'delete' && isset($_GET['id'])) {
            $categoryController->delete($_GET['id']);
        } elseif ($action === 'edit' && isset($_GET['id'])) {
            $categoryController->edit($_GET['id']);
        }
        break;

    case 'cart':
        $cartController = new CartController();
        if ($action === 'index') {
            $cartController->index();
        } elseif ($action === 'remove') {
            $cartController->remove();
        } elseif ($action === 'update') {
            $cartController->update();
        } elseif ($action === 'clear') {
            $cartController->clear();
        }
        break;



    // Bạn có thể thêm các controller khác tại đây:


    case 'user':
        $userController = new UserController();
        if ($action === 'login') {
            $userController->login();
        } elseif ($action === 'register') {
            $userController->register();
        } elseif ($action === 'logout') {
            $userController->logout();
        } elseif ($action === 'profile') {
            $userController->profile();
        } elseif ($action === 'updateProfile') {
            $userController->updateProfile();
        }
        break;


    case 'order':
        $orderController = new OrderController();
        if ($action === 'checkout') {
            $orderController->checkout();
        } elseif ($action === 'details') {
            $orderController->details();
        } elseif ($action === 'history') {
            $orderController->history();
        } elseif ($action === 'payment') {
            $orderController->payment();
        } elseif ($action === 'checkoutWithVNPAY') {
            $orderController->checkoutWithVNPAY();
        } elseif ($action === 'confirmReceipt') {
            $orderController->confirmReceipt();
        }
        break;
    // case 'order':
    case 'admin':
        $adminController = new AdminController();
        if ($action === 'dashboard') {
            $adminController->dashboard();
        } elseif ($action === 'products') {
            $adminController->products();
        } elseif ($action === 'categories') {
            $adminController->categories();
        } elseif ($action === 'users') {
            $adminController->users();
        } elseif ($action === 'banUser') { // Route cho banUser
            $userController = new UserController();
            $userController->banUser();
        } elseif ($action === 'unbanUser') { // Route cho unbanUser
            $userController = new UserController();
            $userController->unbanUser();
        } elseif ($action === 'banners') {
            $bannerController = new SideBannerController();
            $bannerController->index();
        } elseif ($action === 'createBanner') {
            $bannerController = new SideBannerController();
            $bannerController->create();
        } elseif ($action === 'deleteBanner' && isset($_GET['id'])) {
            $bannerController = new SideBannerController();
            $bannerController->delete($_GET['id']);
        } elseif ($action === 'toggleBannerStatus' && isset($_GET['id'])) {
            $bannerController = new SideBannerController();
            $bannerController->toggleStatus($_GET['id']);
        } elseif ($action === 'editBanner' && isset($_GET['id'])) {
            $bannerController = new SideBannerController();
            $bannerController->edit($_GET['id']);
        } elseif ($action === 'orders') {
            $adminController->orders();
        } elseif ($action === 'orderDetail' && isset($_GET['id'])) {
            $adminController->orderDetail($_GET['id']);
        } elseif ($action === 'updateOrderStatus') {
            $adminController->updateOrderStatus();
        } elseif ($action === 'vouchers') {
            $voucherController = new VoucherController();
            $voucherController->index();
        } elseif ($action === 'createVoucher') {
            $voucherController = new VoucherController();
            $voucherController->create();
        } elseif ($action === 'editVoucher' && isset($_GET['id'])) {
            $voucherController = new VoucherController();
            $voucherController->edit();
        } elseif ($action === 'deleteVoucher' && isset($_GET['id'])) {
            $voucherController = new VoucherController();
            $voucherController->delete();
        }


        break;

    case 'voucher':
        $voucherController = new VoucherController();
        if ($action === 'apply') {
            $voucherController->apply();
        } elseif ($action === 'remove') {
            $voucherController->remove();
        }
        break;

    case 'promotion':
        $promotionController = new PromotionController();
        if ($action === 'index') {
            $promotionController->index();
        } elseif ($action === 'create') {
            $promotionController->create();
        } elseif ($action === 'edit' && isset($_GET['id'])) {
            $promotionController->edit($_GET['id']);
        } elseif ($action === 'delete' && isset($_GET['id'])) {
            $promotionController->delete($_GET['id']);
        }
        break;
}
