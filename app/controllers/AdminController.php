<?php
require_once 'app/models/Product.php';
require_once 'app/models/Category.php';

class AdminController {
    public function dashboard() {
        require_once 'app/views/admin/dashboard.php';
    }

    public function products() {
        $productModel = new Product();
        $products = $productModel->getAllProducts();
        require_once 'app/views/admin/products.php';
    }

    public function categories() {
        $categoryModel = new Category();
        $categories = $categoryModel->getAll();
        require_once 'app/views/admin/categories.php';
    }
}
