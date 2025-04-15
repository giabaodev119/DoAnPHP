<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        title {
            color: white!important;
        }
        /* CSS cho dropdown hiện khi hover */
        .dropdown:hover .dropdown-menu {
            display: block;
        }

        .dropdown-menu {
            margin-top: 0;
        }

        .dropdown-item {
            padding: 8px 20px;
        }

        .dropdown-item i {
            margin-right: 8px;
            width: 20px;
            text-align: center;
        }
       
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light" style="background-color: rgb(255,102,0) !important;">
        <a class="navbar-brand" href="index.php?controller=home&action=index">Website</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php?controller=cart&action=index" style="color: white;">
                        <i class="fas fa-shopping-cart"></i> Giỏ hàng
                    </a>
                </li>
                <?php if (!empty($_SESSION['logged_in'])): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" style="color: white;" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-user-circle" ></i> <?= htmlspecialchars($_SESSION['user_name']) ?>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                            <a class="dropdown-item" href="index.php?controller=user&action=profile">
                                <i class="fas fa-user"></i> Thông tin tài khoản
                            </a>
                            <a class="dropdown-item" href="index.php?controller=order&action=history">
                                <i class="fas fa-history"></i> Lịch sử mua hàng
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-danger" href="index.php?controller=user&action=logout">
                                <i class="fas fa-sign-out-alt"></i> Đăng xuất
                            </a>
                        </div>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link btn btn-primary" href="index.php?controller=user&action=login" style="color: white;">
                            <i class="fas fa-sign-in-alt"></i> Đăng nhập
                        </a>
                    </li>
                    <li class="nav-item ml-2">
                        <a class="nav-link btn btn-success" href="index.php?controller=user&action=register" style="color: white;">
                            <i class="fas fa-user-plus"></i> Đăng ký
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <!-- Thêm jQuery và Bootstrap JS trước closing body tag -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>