<?php
require_once 'app/models/Product.php';
require_once 'app/models/Category.php';
require_once 'app/models/Cart.php';

class ProductController
{
    public function index()
    {
        $categoryModel = new Category();
        $categories = $categoryModel->getAllCategories();
        
        $productModel = new Product();
        $products = $productModel->getAllProducts();
        
        // Thêm tên danh mục vào mỗi sản phẩm
        foreach ($products as &$product) {
            foreach ($categories as $category) {
                if ($product['category_id'] == $category['id']) {
                    $product['category_name'] = $category['name'];
                    break;
                }
            }
        }
        
        require_once 'app/views/admin/products.php';
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
    
    


    public function create()
    {

        $categoryModel = new Category();
        $categories = $categoryModel->getAllCategories();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $price = $_POST['price'] ?? 0;
            $description = $_POST['description'] ?? '';
            $category_id = $_POST['category_id'] ?? 0;

            $productModel = new Product();
            $productId = $productModel->create($name, $price, $description, $category_id);

            if ($productId && !empty($_FILES['images'])) {
                $uploadDir = 'public/images/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
                    if ($_FILES['images']['error'][$key] === UPLOAD_ERR_OK) {
                        $fileName = time() . '_' . basename($_FILES['images']['name'][$key]);
                        $filePath = $uploadDir . $fileName;

                        if (move_uploaded_file($tmp_name, $filePath)) {
                            $productModel->addImage($productId, $fileName);
                        }
                    }
                }
            }
            
            header("Location: index.php?controller=product&action=index&success=1");
            exit;
        }

        require_once 'app/views/products/create.php';
    }

    public function show($id)
    {
        $productModel = new Product();
        $product = $productModel->getProductById($id);
        
        if (!$product) {
            header("Location: index.php?controller=product&action=index");
            exit;
        }

        $categoryModel = new Category();
        $categories = $categoryModel->getAllCategories();

        require_once 'app/views/products/show.php';
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