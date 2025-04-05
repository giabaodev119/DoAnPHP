<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Đăng nhập</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #6dd5fa, #2980b9);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .login-box {
            width: 400px;
            margin: 100px auto;
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

    <div class="login-box">
        <h2 class="text-center mb-4">🔐 Đăng nhập</h2>

        <!-- HIỂN THỊ THÔNG BÁO THÀNH CÔNG -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?= $_SESSION['message'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <!-- HIỂN THỊ THÔNG BÁO LỖI -->
        <?php if (!empty($_SESSION['errors'])): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach ($_SESSION['errors'] as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php unset($_SESSION['errors']); ?>
        <?php endif; ?>

        <form action="index.php?controller=user&action=login" method="POST">
            <!-- Email -->
            <div class="mb-3">
                <label for="email" class="form-label">📧 Email</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>

            <!-- Password -->
            <div class="mb-3 position-relative">
                <label for="password" class="form-label">🔑 Mật khẩu</label>
                <input type="password" name="password" id="password" class="form-control" required>
                <span class="toggle-password" onclick="togglePassword()">👁</span>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Đăng nhập</button>
            </div>

            <p class="text-center mt-3">
                Chưa có tài khoản? <a href="index.php?controller=user&action=register">Đăng ký</a>
            </p>
        </form>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const toggle = document.querySelector('.toggle-password');
            if (input.type === 'password') {
                input.type = 'text';
                toggle.textContent = '🙈';
            } else {
                input.type = 'password';
                toggle.textContent = '👁';
            }
        }
    </script>

</body>

</html>