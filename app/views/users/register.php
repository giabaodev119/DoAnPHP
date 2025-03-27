
<?php session_start(); ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng ký</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #6dd5fa, #2980b9);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .register-box {
            width: 450px;
            margin: 50px auto;
            background-color: #fff;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        .toggle-password {
            position: absolute;
            top: 50%;
            right: 15px;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 0.9rem;
            color: #888;
        }
        .position-relative input {
            padding-right: 40px;
        }
    </style>
</head>
<body>

<div class="register-box">
    <h2 class="text-center mb-4">📝 Đăng ký tài khoản</h2>

    <!-- HIỂN THỊ THÔNG BÁO LỖI -->
     <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- HIỂN THỊ THÔNG BÁO THÀNH CÔNG -->
    <?php if (!empty($success_message)): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($success_message) ?>
        </div>
    <?php endif; ?>

    <!-- FORM ĐĂNG KÝ -->

    <form action="index.php?controller=user&action=register" method="POST">
        <!-- Họ tên -->
        <div class="mb-3">
            <label for="name" class="form-label">👤 Họ và tên</label>
            <input type="text" name="name" class="form-control" value="<?= $_SESSION['old']['name'] ?? '' ?>" required>
            <?php if (isset($_SESSION['errors']['name'])): ?>
                <small class="text-danger"><?= $_SESSION['errors']['name'] ?></small>
            <?php endif; ?>
        </div>

        <!-- Email -->
        <div class="mb-3">
            <label for="email" class="form-label">📧 Email</label>
            <input type="email" name="email" class="form-control" value="<?= $_SESSION['old']['email'] ?? '' ?>" required>
            <?php if (isset($_SESSION['errors']['email'])): ?>
                <small class="text-danger"><?= $_SESSION['errors']['email'] ?></small>
            <?php endif; ?>
        </div>

        <!-- Password -->
        <div class="mb-3 position-relative">
            <label for="password" class="form-label">🔒 Mật khẩu</label>
            <input type="password" name="password" id="password" class="form-control" required>
            <?php if (isset($_SESSION['errors']['password'])): ?>
                <small class="text-danger"><?= $_SESSION['errors']['password'] ?></small>
            <?php endif; ?>
            <span class="toggle-password" onclick="togglePassword('password', this)">👁</span>
        </div>

        <!-- Confirm Password -->
        <div class="mb-3 position-relative">
            <label for="confirm_password" class="form-label">🔒 Xác nhận mật khẩu</label>
            <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
            <?php if (isset($_SESSION['errors']['confirm_password'])): ?>
                <small class="text-danger"><?= $_SESSION['errors']['confirm_password'] ?></small>
            <?php endif; ?>
            <span class="toggle-password" onclick="togglePassword('confirm_password', this)">👁</span>
        </div>

        <!-- Submit -->
        <div class="d-grid">
            <button type="submit" class="btn btn-success">Đăng ký</button>
        </div>

        <p class="text-center mt-3">Đã có tài khoản? <a href="index.php?controller=user&action=login">Đăng nhập</a></p>
    </form>
</div>

<script>
    function togglePassword(fieldId, btn) {
        const input = document.getElementById(fieldId);
        if (input.type === 'password') {
            input.type = 'text';
            btn.textContent = '🙈';
        } else {
            input.type = 'password';
            btn.textContent = '👁';
        }
    }
</script>

<!-- UNSET SESSION CHỈ SAU KHI TRANG HIỂN THỊ XONG -->
<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    unset($_SESSION['errors'], $_SESSION['old'], $_SESSION['success']);
}
?>
</body>
</html>
