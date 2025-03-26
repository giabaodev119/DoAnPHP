<?php
require_once 'app/models/Product.php';

class HomeController {
    public function index() {
        $productModel = new Product();
        $featuredProducts = $productModel->getFeaturedProducts();
        require_once 'app/views/home/index.php';
    }
}
?>
