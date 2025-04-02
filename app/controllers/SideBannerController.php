<?php
require_once 'app/models/Banner.php';

class SideBannerController {
    private $bannerModel;

    public function __construct() {
        if (!isset($_SESSION['logged_in']) || $_SESSION['user_role'] !== 'admin') {
            $_SESSION['error'] = "Bạn không có quyền truy cập!";
            header('Location: index.php');
            exit();
        }
        $this->bannerModel = new Banner();
    }

    public function index() {
        $banners = $this->bannerModel->getAllBanners();
         $itemsPerPage = 5; // Số lượng banner hiển thị trên mỗi trang
    $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($currentPage - 1) * $itemsPerPage;

    // Lấy tổng số banner
    $totalBanners = $this->bannerModel->getTotalBanners();

    // Tính tổng số trang
    $totalPages = ceil($totalBanners / $itemsPerPage);

    // Lấy danh sách banner cho trang hiện tại
    $banners = $this->bannerModel->getBannersByPage($itemsPerPage, $offset);
        require_once 'app/views/admin/banner/index.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = $_POST['title'] ?? '';
            $status = $_POST['status'] ?? 'active';

            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'public/images/banners/';
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $fileName = time() . '_' . $_FILES['image']['name'];
                $filePath = $uploadDir . $fileName;

                if (move_uploaded_file($_FILES['image']['tmp_name'], $filePath)) {
                    if ($this->bannerModel->create($title, $fileName, $status)) {
                        $_SESSION['success'] = "Thêm banner thành công!";
                        header('Location: index.php?controller=admin&action=banners');
                        exit();
                    }
                }
            }
            $_SESSION['error'] = "Có lỗi xảy ra khi thêm banner!";
        }
        require_once 'app/views/admin/banner/create.php';
    }

    public function delete($id) {
        $banner = $this->bannerModel->getBannerById($id);
        if ($banner && $this->bannerModel->delete($id)) {
            $imagePath = 'public/images/banners/' . $banner['image_path'];
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
            $_SESSION['success'] = "Xóa banner thành công!";
        } else {
            $_SESSION['error'] = "Có lỗi xảy ra khi xóa banner!";
        }
        header('Location: index.php?controller=admin&action=banners');
        exit();
    }

    public function toggleStatus($id) {
        $banner = $this->bannerModel->getBannerById($id);
        if ($banner) {
            $newStatus = $banner['status'] === 'active' ? 'inactive' : 'active';
            if ($this->bannerModel->updateStatus($id, $newStatus)) {
                $_SESSION['success'] = "Cập nhật trạng thái thành công!";
            } else {
                $_SESSION['error'] = "Có lỗi xảy ra khi cập nhật trạng thái!";
            }
        }
        header('Location: index.php?controller=admin&action=banners');
        exit();
    }
    public function edit($id) {
    $banner = $this->bannerModel->getBannerById($id);

    if (!$banner) {
        $_SESSION['error'] = "Banner không tồn tại!";
        header('Location: index.php?controller=admin&action=banners');
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = $_POST['title'] ?? '';
        $status = $_POST['status'] ?? 'active';
        $imagePath = $banner['image_path'];

        // Xử lý upload ảnh mới (nếu có)
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'public/images/banners/';
            $fileName = time() . '_' . $_FILES['image']['name'];
            $filePath = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $filePath)) {
                // Xóa ảnh cũ
                if (file_exists($uploadDir . $imagePath)) {
                    unlink($uploadDir . $imagePath);
                }
                $imagePath = $fileName;
            }
        }

        // Cập nhật banner
        if ($this->bannerModel->update($id, $title, $imagePath, $status)) {
            $_SESSION['success'] = "Cập nhật banner thành công!";
            header('Location: index.php?controller=admin&action=banners');
            exit();
        } else {
            $_SESSION['error'] = "Có lỗi xảy ra khi cập nhật banner!";
        }
    }

    require_once 'app/views/admin/banner/edit.php';
}
}