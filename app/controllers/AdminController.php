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
    public function create()
    {
        $categoryModel = new Category();
        $categories = $categoryModel->getAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productModel = new Product();
            $productId = $productModel->create($_POST['name'], $_POST['price'], $_POST['description'], $_POST['category_id'], $_POST['featured'] ?? 1);

            if ($productId && !empty($_FILES['images'])) {
                $uploadDir = 'public/images/';
                foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
                    $fileName = time() . '_' . $_FILES['images']['name'][$key];
                    $filePath = $uploadDir . $fileName;

                    if (move_uploaded_file($tmp_name, $filePath)) {
                        $productModel->addImage($productId, $fileName);
                    }
                }
            }
            header(header: "Location: index.php?controller=product&action=create&success=1");
            exit;
        }

        require_once 'app/views/products/create.php';
    }
}
