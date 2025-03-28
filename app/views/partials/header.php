<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="index.php?controller=home&action=index">Website</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="index.php?controller=admin&action=products">Danh sách sản phẩm</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?controller=cart&action=index">Giỏ hàng</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn btn-primary" href="index.php?controller=user&action=login">Đăng nhập</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn btn-success" href="index.php?controller=user&action=register">Đăng ký</a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container mt-4"> 

    <?php
// Add this to your header.php file, near the top before HTML output
// Debug session data (remove in production)
if (!empty($_SESSION['logged_in'])) {
    echo '<div style="background:#dff0d8;padding:5px;text-align:center;">
        Logged in as: ' . htmlspecialchars($_SESSION['user_name']) . 
        ' (Role: ' . htmlspecialchars($_SESSION['user_role']) . ')
    </div>';
}
?>