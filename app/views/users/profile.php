<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin tài khoản</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="public/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 50px;
        }
        .card {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<?php
    require_once 'app/views/partials/header.php';

?>

<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-body">
            <h4 class="card-title fw-bold mb-3">Cài đặt thông tin cá nhân</h4>
            <p class="text-muted"><span class="text-danger fw-bold">(*)</span> Các thông tin bắt buộc</p>

            <form action="index.php?controller=user&action=updateProfile" method="POST">
                <div class="mb-3">
                    <label for="name" class="form-label">Họ và tên <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="name" name="name"
                        value="<?= htmlspecialchars($user['name']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email"
                        value="<?= htmlspecialchars($user['email']) ?>" readonly style="background-color: #f5f5f5;">
                </div>
                <div class="mb-3">
                    <label for="status" class="form-label">Trạng thái:</label>
                    <input type="text" class="form-control" id="status" name="status"
                        value="<?= $user['status'] === 'banned' ? 'Bị khóa' : 'Đang hoạt động' ?>" readonly>
                </div>


<!-- filepath: d:\xampp\htdocs\DoAnPHP\app\views\users\profile.php -->
                <button type="submit" class="btn btn-success px-4">Lưu</button>
            </form>
        </div>
    </div>
</div>

</body>
<?php require_once 'app/views/partials/footer.php'; ?>
