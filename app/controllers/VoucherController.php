<?php
require_once 'app/models/Voucher.php';
require_once 'app/models/Category.php';
require_once 'app/controllers/BaseController.php';

class VoucherController extends BaseController
{
    private $voucherModel;
    private $categoryModel;

    public function __construct()
    {
        $this->voucherModel = new Voucher();
        $this->categoryModel = new Category();
    }

    // Danh sách voucher (Admin)
    public function index()
    {
        if (!isset($_SESSION['logged_in']) || $_SESSION['user_role'] !== 'admin') {
            $_SESSION['error'] = "Bạn không có quyền truy cập tính năng này.";
            header("Location: index.php");
            exit();
        }

        // Xác định trang hiện tại
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $page = max(1, $page);

        // Thiết lập số lượng voucher mỗi trang
        $itemsPerPage = 10;

        // Tính offset
        $offset = ($page - 1) * $itemsPerPage;

        // Lấy tổng số voucher
        $totalVouchers = $this->voucherModel->getTotalVouchers();

        // Tính tổng số trang
        $totalPages = ceil($totalVouchers / $itemsPerPage);

        // Lấy voucher cho trang hiện tại
        $vouchers = $this->voucherModel->getVouchersByPage($itemsPerPage, $offset);

        require_once 'app/views/admin/voucher/index.php';
    }

    // Form tạo voucher mới
    public function create()
    {
        if (!isset($_SESSION['logged_in']) || $_SESSION['user_role'] !== 'admin') {
            $_SESSION['error'] = "Bạn không có quyền truy cập tính năng này.";
            header("Location: index.php");
            exit();
        }

        // Lấy danh sách tất cả danh mục sản phẩm
        $categories = $this->categoryModel->getAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Lấy dữ liệu từ form
            $code = strtoupper(trim($_POST['code']));
            $discount_type = $_POST['discount_type'];
            $discount_value = (float)$_POST['discount_value'];
            $min_purchase = isset($_POST['min_purchase']) ? (float)$_POST['min_purchase'] : 0;
            $max_discount = !empty($_POST['max_discount']) ? (float)$_POST['max_discount'] : null;
            $quantity = (int)$_POST['quantity'];
            $start_date = !empty($_POST['start_date']) ? $_POST['start_date'] : null;
            $end_date = !empty($_POST['end_date']) ? $_POST['end_date'] : null;
            $description = $_POST['description'] ?? '';
            $categories = isset($_POST['categories']) ? $_POST['categories'] : [];

            // Kiểm tra code đã tồn tại chưa
            $existingVoucher = $this->voucherModel->getVoucherByCode($code);
            if ($existingVoucher) {
                $_SESSION['error'] = "Mã voucher đã tồn tại. Vui lòng chọn mã khác.";
                header("Location: index.php?controller=voucher&action=create");
                exit();
            }

            // Thêm voucher vào database
            $result = $this->voucherModel->create($code, $discount_type, $discount_value, $min_purchase, $max_discount, $quantity, $start_date, $end_date, $description, $categories);

            if ($result) {
                $_SESSION['message'] = "Đã thêm voucher thành công.";
                header("Location: index.php?controller=admin&action=vouchers");
                exit();
            } else {
                $_SESSION['error'] = "Không thể thêm voucher. Vui lòng thử lại.";
            }
        }

        require_once 'app/views/admin/voucher/create.php';
    }

    // Form sửa voucher
    public function edit()
    {
        if (!isset($_SESSION['logged_in']) || $_SESSION['user_role'] !== 'admin') {
            $_SESSION['error'] = "Bạn không có quyền truy cập tính năng này.";
            header("Location: index.php");
            exit();
        }

        if (!isset($_GET['id'])) {
            $_SESSION['error'] = "ID voucher không hợp lệ.";
            header("Location: index.php?controller=admin&action=vouchers");
            exit();
        }

        $voucherId = (int)$_GET['id'];
        $voucher = $this->voucherModel->getVoucherById($voucherId);
        $categories = $this->categoryModel->getAll();

        if (!$voucher) {
            $_SESSION['error'] = "Không tìm thấy voucher.";
            header("Location: index.php?controller=admin&action=vouchers");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Lấy dữ liệu từ form
            $code = strtoupper(trim($_POST['code']));
            $discount_type = $_POST['discount_type'];
            $discount_value = (float)$_POST['discount_value'];
            $min_purchase = isset($_POST['min_purchase']) ? (float)$_POST['min_purchase'] : 0;
            $max_discount = !empty($_POST['max_discount']) ? (float)$_POST['max_discount'] : null;
            $quantity = (int)$_POST['quantity'];
            $start_date = !empty($_POST['start_date']) ? $_POST['start_date'] : null;
            $end_date = !empty($_POST['end_date']) ? $_POST['end_date'] : null;
            $description = $_POST['description'] ?? '';
            $status = $_POST['status'];
            $selectedCategories = isset($_POST['categories']) ? $_POST['categories'] : [];

            // Kiểm tra nếu thay đổi code, đảm bảo code mới không trùng
            if ($code !== $voucher['code']) {
                $existingVoucher = $this->voucherModel->getVoucherByCode($code);
                if ($existingVoucher) {
                    $_SESSION['error'] = "Mã voucher đã tồn tại. Vui lòng chọn mã khác.";
                    header("Location: index.php?controller=voucher&action=edit&id=$voucherId");
                    exit();
                }
            }

            // Cập nhật voucher
            $result = $this->voucherModel->update($voucherId, $code, $discount_type, $discount_value, $min_purchase, $max_discount, $quantity, $start_date, $end_date, $description, $status, $selectedCategories);

            if ($result) {
                $_SESSION['message'] = "Đã cập nhật voucher thành công.";
                header("Location: index.php?controller=admin&action=vouchers");
                exit();
            } else {
                $_SESSION['error'] = "Không thể cập nhật voucher. Vui lòng thử lại.";
            }
        }

        require_once 'app/views/admin/voucher/edit.php';
    }

    // Xóa voucher
    public function delete()
    {
        if (!isset($_SESSION['logged_in']) || $_SESSION['user_role'] !== 'admin') {
            $_SESSION['error'] = "Bạn không có quyền truy cập tính năng này.";
            header("Location: index.php");
            exit();
        }

        if (!isset($_GET['id'])) {
            $_SESSION['error'] = "ID voucher không hợp lệ.";
            header("Location: index.php?controller=admin&action=vouchers");
            exit();
        }

        $voucherId = (int)$_GET['id'];
        $result = $this->voucherModel->delete($voucherId);

        if ($result) {
            $_SESSION['message'] = "Đã xóa voucher thành công.";
        } else {
            $_SESSION['error'] = "Không thể xóa voucher. Vui lòng thử lại.";
        }

        header("Location: index.php?controller=admin&action=vouchers");
        exit();
    }

    /**
     * Áp dụng voucher (AJAX)
     */
    public function apply()
    {
        $this->ensureUserLoggedIn();
        $this->ensureNotAdmin();

        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            exit;
        }

        $code = isset($_POST['voucher_code']) ? trim($_POST['voucher_code']) : '';
        if (empty($code)) {
            echo json_encode(['success' => false, 'message' => 'Vui lòng nhập mã giảm giá']);
            exit;
        }

        // Lấy giỏ hàng của người dùng
        $cartModel = new Cart();
        $userId = $_SESSION['user_id'];
        $cartItems = $cartModel->getCartItems($userId);

        if (empty($cartItems)) {
            echo json_encode(['success' => false, 'message' => 'Giỏ hàng của bạn đang trống']);
            exit;
        }

        // Lấy tổng giỏ hàng
        $cartTotal = $cartModel->getCartTotal($userId);

        // Kiểm tra mã giảm giá
        $result = $this->voucherModel->validateVoucher($code, $userId, $cartItems);

        if (!$result['valid']) {
            echo json_encode(['success' => false, 'message' => $result['message']]);
            exit;
        }

        // Lưu thông tin mã giảm giá vào session
        $_SESSION['voucher'] = [
            'id' => $result['voucher']['id'],
            'code' => $code,
            'discount_amount' => $result['discount_amount'],
            'discount_type' => $result['voucher']['discount_type'],
            'discount_value' => $result['voucher']['discount_value']
        ];

        echo json_encode([
            'success' => true,
            'message' => 'Mã giảm giá đã được áp dụng',
            'discount_amount' => $result['discount_amount'],
            'discount_formatted' => number_format($result['discount_amount'], 0, ',', '.') . ' đ',
            'new_total' => $cartTotal - $result['discount_amount'],
            'new_total_formatted' => number_format($cartTotal - $result['discount_amount'], 0, ',', '.') . ' đ'
        ]);
        exit;
    }

    /**
     * Xóa voucher đã áp dụng (AJAX)
     */
    public function remove()
    {
        $this->ensureUserLoggedIn();
        $this->ensureNotAdmin();

        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            exit;
        }

        // Xóa thông tin mã giảm giá khỏi session
        unset($_SESSION['voucher']);

        echo json_encode(['success' => true, 'message' => 'Đã xóa mã giảm giá']);
        exit;
    }
}
