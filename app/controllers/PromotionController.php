<?php
require_once 'app/models/Promotion.php';
require_once 'app/models/Product.php';
require_once 'app/models/Category.php';

class PromotionController
{
    private $promotionModel;
    private $productModel;
    private $categoryModel;

    public function __construct()
    {
        // Ensure session is started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Check if user is logged in and has admin role
        if (!isset($_SESSION['logged_in']) || $_SESSION['user_role'] !== 'admin') {
            $_SESSION['error'] = "Bạn cần đăng nhập với quyền admin để truy cập trang quản trị.";
            require_once 'app/views/partials/adminaccesserror.php';
            exit();
        }

        $this->promotionModel = new Promotion();
        $this->productModel = new Product();
        $this->categoryModel = new Category();
    }

    // Hiển thị danh sách chương trình khuyến mãi
    public function index()
    {
        // Xác định trang hiện tại
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $page = max(1, $page);

        // Thiết lập số lượng mục mỗi trang
        $itemsPerPage = 10;

        // Tính offset
        $offset = ($page - 1) * $itemsPerPage;

        // Lấy tổng số chương trình khuyến mãi
        $totalPromotions = $this->promotionModel->getTotalPromotions();

        // Tính tổng số trang
        $totalPages = ceil($totalPromotions / $itemsPerPage);

        // Lấy danh sách khuyến mãi cho trang hiện tại
        $promotions = $this->promotionModel->getPromotionsByPage($itemsPerPage, $offset);

        require_once 'app/views/admin/promotion/index.php';
    }

    // Hiển thị form tạo chương trình khuyến mãi mới
    public function create()
    {
        $products = $this->productModel->getAllProducts();
        $categories = $this->categoryModel->getAllCategories();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $data = [
                    'name' => trim($_POST['name']),
                    'description' => trim($_POST['description']),
                    'discount_type' => $_POST['discount_type'],
                    'discount_value' => (float)$_POST['discount_value'],
                    'start_date' => !empty($_POST['start_date']) ? $_POST['start_date'] : null,
                    'end_date' => !empty($_POST['end_date']) ? $_POST['end_date'] : null,
                    'status' => $_POST['status'],
                    'target_type' => $_POST['target_type'],
                    'product_ids' => isset($_POST['product_ids']) ? $_POST['product_ids'] : [],
                    'category_ids' => isset($_POST['category_ids']) ? $_POST['category_ids'] : []
                ];

                // Validate dữ liệu
                if (empty($data['name'])) {
                    throw new Exception("Vui lòng nhập tên chương trình khuyến mãi");
                }

                if ($data['discount_value'] <= 0) {
                    throw new Exception("Giá trị giảm giá phải lớn hơn 0");
                }

                if ($data['discount_type'] === 'percentage' && $data['discount_value'] > 100) {
                    throw new Exception("Phần trăm giảm giá không được vượt quá 100%");
                }

                if ($data['target_type'] === 'product' && empty($data['product_ids'])) {
                    throw new Exception("Vui lòng chọn ít nhất một sản phẩm");
                }

                if ($data['target_type'] === 'category' && empty($data['category_ids'])) {
                    throw new Exception("Vui lòng chọn ít nhất một danh mục");
                }

                // Lưu dữ liệu
                $result = $this->promotionModel->create($data);

                if ($result) {
                    // Áp dụng khuyến mãi ngay lập tức
                    $this->promotionModel->applyPromotionsToProducts();

                    $_SESSION['message'] = "Đã tạo chương trình khuyến mãi thành công";
                    header("Location: index.php?controller=promotion&action=index");
                    exit();
                } else {
                    throw new Exception("Không thể tạo chương trình khuyến mãi");
                }
            } catch (Exception $e) {
                $error = $e->getMessage();
            }
        }

        require_once 'app/views/admin/promotion/create.php';
    }

    // Hiển thị form sửa chương trình khuyến mãi
    public function edit()
    {
        if (!isset($_GET['id'])) {
            $_SESSION['error'] = "ID chương trình khuyến mãi không hợp lệ";
            header("Location: index.php?controller=promotion&action=index");
            exit();
        }

        $id = (int)$_GET['id'];
        $promotion = $this->promotionModel->getPromotionById($id);

        if (!$promotion) {
            $_SESSION['error'] = "Không tìm thấy chương trình khuyến mãi";
            header("Location: index.php?controller=promotion&action=index");
            exit();
        }

        $products = $this->productModel->getAllProducts();
        $categories = $this->categoryModel->getAllCategories();

        // Lấy danh sách sản phẩm/danh mục đã chọn
        $selectedProducts = $this->promotionModel->getPromotionProducts($id);
        $selectedCategories = $this->promotionModel->getPromotionCategories($id);

        $selectedProductIds = array_column($selectedProducts, 'id');
        $selectedCategoryIds = array_column($selectedCategories, 'id');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $data = [
                    'name' => trim($_POST['name']),
                    'description' => trim($_POST['description']),
                    'discount_type' => $_POST['discount_type'],
                    'discount_value' => (float)$_POST['discount_value'],
                    'start_date' => !empty($_POST['start_date']) ? $_POST['start_date'] : null,
                    'end_date' => !empty($_POST['end_date']) ? $_POST['end_date'] : null,
                    'status' => $_POST['status'],
                    'target_type' => $_POST['target_type'],
                    'product_ids' => isset($_POST['product_ids']) ? $_POST['product_ids'] : [],
                    'category_ids' => isset($_POST['category_ids']) ? $_POST['category_ids'] : []
                ];

                // Validate dữ liệu tương tự như phương thức create

                // Cập nhật dữ liệu
                $result = $this->promotionModel->update($id, $data);

                if ($result) {
                    // Áp dụng khuyến mãi
                    $this->promotionModel->applyPromotionsToProducts();

                    $_SESSION['message'] = "Đã cập nhật chương trình khuyến mãi thành công";
                    header("Location: index.php?controller=promotion&action=index");
                    exit();
                } else {
                    throw new Exception("Không thể cập nhật chương trình khuyến mãi");
                }
            } catch (Exception $e) {
                $error = $e->getMessage();
            }
        }

        require_once 'app/views/admin/promotion/edit.php';
    }

    // Xóa chương trình khuyến mãi
    public function delete()
    {
        if (!isset($_GET['id'])) {
            $_SESSION['error'] = "ID chương trình khuyến mãi không hợp lệ";
            header("Location: index.php?controller=promotion&action=index");
            exit();
        }

        $id = (int)$_GET['id'];
        $result = $this->promotionModel->delete($id);

        if ($result) {
            // Áp dụng lại khuyến mãi để cập nhật giá sản phẩm
            $this->promotionModel->applyPromotionsToProducts();

            $_SESSION['message'] = "Đã xóa chương trình khuyến mãi thành công";
        } else {
            $_SESSION['error'] = "Không thể xóa chương trình khuyến mãi";
        }

        header("Location: index.php?controller=promotion&action=index");
        exit();
    }
}
