<?php
require_once 'app/models/Product.php';
require_once 'app/models/Category.php';
require_once 'app/models/Cart.php';

class ProductController
{
    public function index()
    {
        $productModel = new Product();
        $products = $productModel->getAllProducts();
        require_once 'app/views/products/index.php';
    }

    public function detail($id)
    {
        $productModel = new Product();
        $product = $productModel->getProductById($id);
        require_once 'app/views/products/detail.php';
    }

    public function create()
    {
        $categoryModel = new Category();
        $categories = $categoryModel->getAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productModel = new Product();
            $productId = $productModel->create($_POST['name'], $_POST['price'], $_POST['description'], $_POST['category_id']);

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

    // add to cart
    public function addToCart($productId)
    {
        $cartModel = new Cart();
        
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?controller=auth&action=login");
            exit;
        }

        // Get user ID from session
        $userId = $_SESSION['user_id'];

        // Get quantity if provided, default to 1
        $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

        // Add item to cart
        $cartModel->addToCart($userId, $productId, $quantity);
        
        header(header: "Location: index.php?controller=cart&action=index");
        exit;
    }




}
?>