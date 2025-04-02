<?php
require_once 'app/models/Product.php';
require_once 'app/models/Category.php';
require_once 'app/models/Cart.php';

class ProductController
{
    // Hiển thị danh sách sản phẩm
    public function index()
    {
        $itemsPerPage = 5; // Số lượng sản phẩm hiển thị trên mỗi trang
        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($currentPage - 1) * $itemsPerPage;

        $categoryModel = new Category();
        $productModel = new Product();
        // Lấy tổng số sản phẩm
        //$totalProducts = $productModel->getTotalProducts();

        // Tính tổng số trang
        //$productTotalPages = ceil($totalProducts / $itemsPerPage);

        // Lấy danh sách sản phẩm cho trang hiện tại
        $products = $productModel->getProductsByPage($itemsPerPage, $offset);
        // Lấy danh sách danh mục và sản phẩm
        $categories = $categoryModel->getAllCategories();
        $products = $productModel->getAllProducts();

        // Tạo mảng ánh xạ danh mục để tối ưu hóa
        $categoryMap = [];
        foreach ($categories as $category) {
            $categoryMap[$category['id']] = $category['name'];
        }

        // Gán tên danh mục vào từng sản phẩm
        foreach ($products as &$product) {
            $product['category_name'] = $categoryMap[$product['category_id']] ?? 'N/A';
        }

        // Thực hiện tìm kiếm nếu có
        $keyword = $_GET['keyword'] ?? '';
        $categoryId = $_GET['category'] ?? '';

        if (!empty($keyword) || !empty($categoryId)) {
            $products = $productModel->searchProducts($keyword, $categoryId);
        }

        // Truyền dữ liệu sang view
        require_once 'app/views/products/index.php';
    }

    // Tìm kiếm sản phẩm
    public function search()
    {
        $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
        $category = isset($_GET['category']) ? $_GET['category'] : '';

        // Gọi model để tìm kiếm sản phẩm
        $productModel = new Product();
        $products = $productModel->searchProducts($keyword, $category);

        // Load giao diện kết quả tìm kiếm
        require_once 'app/views/home/search.php';
    }

    // Tạo sản phẩm mới
    public function create()
    {
        $categoryModel = new Category();
        $categories = $categoryModel->getAllCategories();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $price = $_POST['price'] ?? 0;
            $description = $_POST['description'] ?? '';
            $category_id = $_POST['category_id'] ?? 0;
            $stock = $_POST['stock'] ?? 0;
            $featured = $_POST['featured'] ?? 0;

            $productModel = new Product();
            $productId = $productModel->create($name, $price, $description, $category_id, $stock, $featured);

            // Xử lý upload hình ảnh
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

            header("Location: index.php?controller=admin&action=products&success=1");
            exit;
        }

        require_once 'app/views/admin/product/create.php';
    }

    // Hiển thị chi tiết sản phẩm
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

    // Chỉnh sửa sản phẩm
    public function edit($id)
    {
        $productModel = new Product();
        $categoryModel = new Category();

        $product = $productModel->getProductById($id);
        $categories = $categoryModel->getAllCategories();
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $price = $_POST['price'] ?? 0;
            $description = $_POST['description'] ?? '';
            $category_id = $_POST['category_id'] ?? 0;
            $stock = $_POST['stock'] ?? 0;
            $featured = $_POST['featured'] ?? 0;

            if ($productModel->update($id, $name, $price, $description, $category_id, $stock, $featured)) {
                header("Location: index.php?controller=admin&action=products&success=1");
                exit;
            } else {
                $error = "Lỗi khi cập nhật sản phẩm. Vui lòng thử lại.";
            }
        }

        require_once 'app/views/admin/product/edit.php';
    }

    // Thêm sản phẩm vào giỏ hàng
    public function addToCart($productId)
    {
        $cartModel = new Cart();
        $productModel = new Product();

        // Kiểm tra người dùng đã đăng nhập chưa
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?controller=user&action=login");
            exit;
        }

        // Lấy user ID từ session
        $userId = $_SESSION['user_id'];

        // Lấy số lượng, mặc định là 1
        $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

        // Kiểm tra sản phẩm có tồn tại không
        $product = $productModel->getProductById($productId);
        if (!$product) {
            $_SESSION['error'] = "Sản phẩm không tồn tại";
            header("Location: index.php?controller=product&action=index");
            exit;
        }

        // Kiểm tra số lượng tồn kho
        if ($product['stock'] <= 0) {
            $_SESSION['error'] = "Sản phẩm đã hết hàng";
            header("Location: index.php?controller=product&action=detail&id=" . $productId);
            exit;
        }

        // Kiểm tra số lượng đặt mua có vượt quá tồn kho không
        if ($quantity > $product['stock']) {
            $_SESSION['error'] = "Số lượng yêu cầu vượt quá tồn kho. Hiện chỉ còn " . $product['stock'] . " sản phẩm.";
            header("Location: index.php?controller=product&action=detail&id=" . $productId);
            exit;
        }

        // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
        $cartItems = $cartModel->getCartItems($userId);
        $totalQuantity = $quantity;

        foreach ($cartItems as $item) {
            if ($item->product_id == $productId) {
                $totalQuantity += $item->quantity;
                break;
            }
        }

        // Kiểm tra tổng số lượng (hiện tại + thêm mới) có vượt quá tồn kho không
        if ($totalQuantity > $product['stock']) {
            $_SESSION['error'] = "Tổng số lượng sản phẩm trong giỏ hàng vượt quá tồn kho. Hiện chỉ còn " . $product['stock'] . " sản phẩm.";
            header("Location: index.php?controller=product&action=detail&id=" . $productId);
            exit;
        }

        // Thêm sản phẩm vào giỏ hàng
        $cartModel->addToCart($userId, $productId, $quantity);

        $_SESSION['message'] = "Đã thêm sản phẩm vào giỏ hàng thành công!";
        header("Location: index.php?controller=cart&action=index");
        exit;
    }

    // Xóa sản phẩm
    public function delete($id)
    {
        $productModel = new Product();

        if ($productModel->delete($id)) {
            header("Location: index.php?controller=admin&action=products&success=1");
            exit;
        } else {
            header("Location: index.php?controller=admin&action=products&error=1");
            exit;
        }
    }

    // Hiển thị chi tiết sản phẩm trong admin
    public function detailAdmin($id)
    {
        $productModel = new Product();

        $product = $productModel->getProductById($id);
        $images = $productModel->getProductImages($id);

        require_once 'app/views/admin/products/detail.php';
    }
    //Hiển thị chi tiết sản phẩm trong trang người dùng
    public function detail($id)
    {
        $productModel = new Product();

        $product = $productModel->getProductById($id);
        $images = $productModel->getProductImages($id);
        $relatedProducts = $productModel->getRelatedProducts($product['category_id'], $id);
        require_once 'app/views/products/detail.php';
    }
}
