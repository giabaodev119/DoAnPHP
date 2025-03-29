<?php
// Nạp các controller cần thiết
require_once 'app/controllers/HomeController.php';
require_once 'app/controllers/ProductController.php';
require_once 'app/controllers/CategoryController.php'; // Thêm dòng này
require_once 'app/controllers/AdminController.php';
require_once 'app/controllers/UserController.php';
// Có thể bổ sung thêm nếu cần: UserController, OrderController, ...

// Lấy controller và action từ URL, mặc định là trang chủ
$controller = isset($_GET['controller']) ? $_GET['controller'] : 'home';
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

// Nạp header trước khi gọi controller
require_once 'app/views/partials/header.php';

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
            } elseif ($action === 'search') { // Thêm xử lý tìm kiếm
                $productController->search();
            }
            break;
        
    case 'category':
        $categoryController = new CategoryController();
        if ($action === 'create') {
            $categoryController->create();
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
        }
        break;
    
}
// Nạp footer sau khi gọi controller
require_once 'app/views/partials/footer.php';

?>
