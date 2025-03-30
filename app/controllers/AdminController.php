<?php
require_once 'app/models/Product.php';
require_once 'app/models/Category.php';
require_once 'app/models/User.php'; // Thêm dòng này để nạp lớp User

class AdminController {
    public function dashboard() {
        require_once 'app/views/admin/dashboard.php';
    }

    public function products() {
        $productModel = new Product();
        $products = $productModel->getAllProducts();
        require_once 'app/views/admin/product/index.php';
    }

    public function categories() {
        $categoryModel = new Category();
        $categories = $categoryModel->getAll();
        require_once 'app/views/admin/categories/index.php';
    }

    public function users() {
        $userModel = new User();
        $users = $userModel->getAllUsers();
        require_once 'app/views/admin/users/index.php';
    }
   

}