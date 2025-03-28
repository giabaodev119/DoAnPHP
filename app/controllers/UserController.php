<?php
require_once 'config/config.php';

class UserController
{
    public function __construct()
    {
        // Ensure session is started at the beginning
        if (session_status() === PHP_SESSION_NONE) {
            // Set session cookie parameters for better security and reliability
            session_set_cookie_params([
                'lifetime' => 86400, // 24 hours
                'path' => '/',
                'domain' => '',
                'secure' => false,
                'httponly' => true
            ]);
            session_start();
        }
    }
    public function login()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = [];

            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            // ✅ Kiểm tra dữ liệu rỗng
            if (empty($email)) {
                $errors['email'] = "Vui lòng nhập email.";
            }
            if (empty($password)) {
                $errors['password'] = "Vui lòng nhập mật khẩu.";
            }

            // ✅ Kiểm tra định dạng email
            if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = "Email không hợp lệ.";
            }

            // ✅ Nếu không có lỗi, kiểm tra email có tồn tại không
            if (empty($errors)) {
                $stmt = $GLOBALS['conn']->prepare("SELECT id, name,role, password FROM users WHERE email = ?");
                $stmt->execute([$email]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($user && password_verify($password, $user['password'])) {
                    // ✅ Đăng nhập thành công
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['name'];
                    $_SESSION['user_role'] = $user['role'];
                    $_SESSION['logged_in'] = true;
                    
                    // Force session write
                    session_write_close();
                    
                    // Restart session to verify data was saved
                    session_start();
                    
                    // Redirect based on role
                    if ($user['role'] === 'admin') {
                        header("Location: index.php?controller=admin&action=dashboard");
                    } else {
                        header("Location: index.php"); // Chuyển về trang chủ
                    }
                    exit();
                } else {
                    // ❌ Email hoặc mật khẩu sai
                    $errors['login'] = "Email hoặc mật khẩu không đúng.";
                }
            }

            // ✅ Lưu lỗi vào SESSION và chuyển hướng lại form đăng nhập
            if (!empty($errors)) {
                $_SESSION['errors'] = $errors;
                header("Location: index.php?controller=user&action=login");
                exit();
            }
        }

        // ✅ Nếu có lỗi từ trước thì lấy từ SESSION
        $errors = $_SESSION['errors'] ?? [];
        unset($_SESSION['errors']);

        require_once 'app/views/users/login.php';
    }


    public function register()
    {
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';

            // ✅ Kiểm tra dữ liệu rỗng
            if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
                $errors['general'] = "Vui lòng điền đầy đủ thông tin.";
            }

            // ✅ Kiểm tra định dạng email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = "Email không hợp lệ.";
            }

            // ✅ Kiểm tra mật khẩu mạnh
            $passwordPattern = "/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/";
            if (!preg_match($passwordPattern, $password)) {
                $errors['password'] = "Mật khẩu yếu (≥ 8 ký tự, 1 chữ hoa, 1 số, 1 ký tự đặc biệt).";
            }

            // ✅ Kiểm tra xác nhận mật khẩu
            if ($password !== $confirm_password) {
                $errors['confirm_password'] = "Mật khẩu xác nhận không khớp.";
            }

            // ✅ Kiểm tra email đã tồn tại nếu không có lỗi trước đó
                $stmt = $GLOBALS['conn']->prepare("SELECT id FROM users WHERE email = ?");
                $stmt->execute([$email]);
                if ($stmt->rowCount() > 0) {
                    $errors['email'] = "Email đã tồn tại.";
                }
            // ✅ Nếu không có lỗi, thực hiện đăng ký
            if (empty($errors)) {
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                $stmt = $GLOBALS['conn']->prepare("INSERT INTO users (name, email, password, role, created_at) VALUES (?, ?, ?, 'customer', NOW())");
                $stmt->execute([$name, $email, $hashedPassword]);

                // Hiển thị thông báo thành công
                $success_message = "Đăng ký thành công!.";
            }
        }

        // ✅ Hiển thị giao diện đăng ký
        require_once 'app/views/users/register.php';
    }



    public function logout()
    {
        session_destroy();

        header("Location: index.php");
        exit();
    }
}
