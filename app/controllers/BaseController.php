<?php
class BaseController
{
    /**
     * Đảm bảo người dùng không phải là admin
     * Nếu là admin, chuyển hướng về trang dashboard admin
     */
    protected function ensureNotAdmin()
    {
        if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
            $_SESSION['error'] = "Tài khoản admin không thể sử dụng các chức năng của người dùng thông thường.";
            header("Location: index.php?controller=admin&action=dashboard");
            exit();
        }
    }

    /**
     * Đảm bảo người dùng đã đăng nhập
     * Nếu chưa đăng nhập, chuyển hướng đến trang đăng nhập
     */
    protected function ensureUserLoggedIn()
    {
        if (!isset($_SESSION['logged_in']) || !isset($_SESSION['user_id'])) {
            $_SESSION['error'] = "Bạn cần đăng nhập để thực hiện hành động này.";
            header("Location: index.php?controller=user&action=login");
            exit();
        }
    }
}
