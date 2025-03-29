<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Truy cập bị từ chối</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .error-container {
            max-width: 500px;
            padding: 2rem;
            text-align: center;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        .error-icon {
            font-size: 5rem;
            color: #dc3545;
            margin-bottom: 1rem;
        }
        .error-title {
            font-size: 1.75rem;
            margin-bottom: 1rem;
            font-weight: 600;
        }
        .error-message {
            color: #6c757d;
            margin-bottom: 2rem;
        }
        .btn-container {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        @media (min-width: 576px) {
            .btn-container {
                flex-direction: row;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="error-container">
            <div class="error-icon">
                <i class="fas fa-lock"></i>
            </div>
            
            <h1 class="error-title">Truy cập bị từ chối</h1>
            
            <p class="error-message">
                Bạn không có quyền truy cập vào trang quản trị. 
                Chỉ tài khoản có quyền admin mới có thể sử dụng chức năng này.
            </p>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger mb-4">
                    <?= $_SESSION['error']; ?>
                    <?php unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>
            
            <div class="btn-container">
                <a href="index.php" class="btn btn-secondary">
                    <i class="fas fa-home me-2"></i>Về trang chủ
                </a>
                
                <?php if (!isset($_SESSION['logged_in'])): ?>
                    <a href="index.php?controller=user&action=login" class="btn btn-primary">
                        <i class="fas fa-sign-in-alt me-2"></i>Đăng nhập
                    </a>
                <?php else: ?>
                    <a href="index.php?controller=user&action=logout" class="btn btn-outline-primary">
                        <i class="fas fa-user-alt me-2"></i>Đổi tài khoản
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>