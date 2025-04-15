<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin tài khoản</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="public/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Roboto', sans-serif;
        }

        .profile-container {
            max-width: 800px;
            margin: 50px auto;
        }

        .profile-header {
            background-color: #fff;
            border-radius: 10px 10px 0 0;
            padding: 20px 30px;
            border-bottom: 1px solid #eee;
        }

        .profile-header h2 {
            margin-bottom: 5px;
            color: #333;
            font-weight: 700;
        }

        .profile-card {
            background-color: #fff;
            border-radius: 0 0 10px 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            border: none;
        }

        .profile-card .card-body {
            padding: 30px;
        }

        .form-label {
            font-weight: 600;
            color: #555;
        }

        .form-control {
            padding: 12px 15px;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .form-control[readonly] {
            background-color: #f8f9fa;
        }

        .btn-success {
            padding: 10px 25px;
            border-radius: 8px;
            font-weight: 600;
            background-color: #28a745;
            border-color: #28a745;
            transition: all 0.3s;
        }

        .btn-success:hover {
            background-color: #218838;
            border-color: #1e7e34;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .btn-secondary {
            padding: 10px 25px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .user-status {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
        }

        .status-active {
            background-color: #e8f5e9;
            color: #2e7d32;
        }

        .status-banned {
            background-color: #ffebee;
            color: #c62828;
        }

        .alert {
            border-radius: 8px;
            padding: 15px 20px;
            margin-bottom: 20px;
            position: relative;
        }

        .btn-actions {
            margin-top: 20px;
            display: flex;
            gap: 15px;
        }
    </style>
</head>

<body>
    <?php require_once 'app/views/partials/header.php'; ?>

    <div class="container profile-container">
        <!-- Hiển thị thông báo thành công -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i><?= $_SESSION['message'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <!-- Hiển thị thông báo lỗi -->
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i><?= $_SESSION['error'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <div class="profile-header">
            <h2><i class="fas fa-user-circle me-2"></i>Thông tin tài khoản</h2>
            <p class="text-muted">Quản lý thông tin cá nhân của bạn</p>
        </div>

        <div class="card profile-card">
            <div class="card-body">
                <form action="index.php?controller=user&action=updateProfile" method="POST">
                    <div class="row">
                        <div class="col-md-12 mb-4">
                            <label for="name" class="form-label">Họ và tên <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="<?= htmlspecialchars($user['name']) ?>" required>
                            </div>
                        </div>

                        <div class="col-md-12 mb-4">
                            <label for="email" class="form-label">Email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                <input type="email" class="form-control" id="email" name="email"
                                    value="<?= htmlspecialchars($user['email']) ?>" readonly>
                            </div>
                            <small class="text-muted">Email không thể thay đổi</small>
                        </div>

                        <div class="col-md-12 mb-4">
                            <label for="status" class="form-label">Trạng thái tài khoản</label>
                            <div>
                                <span class="user-status <?= $user['status'] === 'banned' ? 'status-banned' : 'status-active' ?>">
                                    <i class="fas <?= $user['status'] === 'banned' ? 'fa-lock' : 'fa-check-circle' ?> me-1"></i>
                                    <?= $user['status'] === 'banned' ? 'Tài khoản bị khóa' : 'Đang hoạt động' ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="btn-actions">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-2"></i>Lưu thay đổi
                        </button>
                        <a href="index.php?controller=home&action=index" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Về trang chủ
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Tự động ẩn thông báo sau 5 giây
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');

            alerts.forEach(function(alert) {
                // Thêm thanh tiến trình đếm ngược
                const progressBar = document.createElement('div');
                progressBar.style.height = '3px';
                progressBar.style.backgroundColor = alert.classList.contains('alert-success') ? '#28a745' : '#dc3545';
                progressBar.style.width = '100%';
                progressBar.style.position = 'absolute';
                progressBar.style.bottom = '0';
                progressBar.style.left = '0';
                progressBar.style.transition = 'width 5s linear';
                alert.style.position = 'relative';
                alert.appendChild(progressBar);

                // Bắt đầu đếm ngược sau 100ms (để hiệu ứng hiển thị mượt hơn)
                setTimeout(() => {
                    progressBar.style.width = '0';
                }, 100);

                // Đóng thông báo sau 5 giây
                setTimeout(() => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });
        });
    </script>
</body>
<?php require_once 'app/views/partials/footer.php'; ?>