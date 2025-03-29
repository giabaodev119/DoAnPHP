<?php
require_once 'app/models/Product.php';
require_once 'app/models/Category.php';

class ProductController {
    public function index() {
        $productModel = new Product();
        $products = $productModel->getAllProducts();
        require_once 'app/views/products/index.php';
    }

    public function detail($id) {
        $productModel = new Product();
        $product = $productModel->getProductById($id);
        require_once 'app/views/products/detail.php';
    }
    public function search() {
        $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
        $category = isset($_GET['category']) ? $_GET['category'] : '';
    
        // Gọi model để tìm kiếm sản phẩm
        $productModel = new Product();
        $products = $productModel->searchProducts($keyword, $category);
    
        // Load giao diện kết quả tìm kiếm
        require_once 'app/views/home/search.php';
        //var_dump($products);
exit();
    }
    
    
    
    public function create() {
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
            header("Location: index.php?controller=product&action=create&success=1");
            exit;
        }
    
        require_once 'app/views/products/create.php';
    }
}
?>
