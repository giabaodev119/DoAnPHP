<?php
require_once 'app/models/Cart.php';
require_once 'app/models/Product.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}



if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = 'Bạn phải đăng nhập để thanh toán.';
    header('Location: index.php?controller=cart');
    exit;
}


$cartModel = new Cart();
$userId = $_SESSION['user_id'];
$cartItems = $cartModel->getCartItems($userId);
$cartTotal = $cartModel->getCartTotal($userId);

if (!$cartItems) {
    $_SESSION['error'] = 'Giỏ hàng của bạn đang trống!';
    header('Location: index.php?controller=cart');
    exit;
}

// Lưu đơn hàng tạm vào session
$_SESSION['pending_order'] = [
    'user_id' => $userId,
    'total_price' => $cartTotal,
    'items' => $cartItems
];

$title = 'Thanh toán';
// echo "<pre>";
// print_r($_SESSION);
// echo "</pre>";
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .payment-button {
            display: block;
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            text-align: center;
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }

        .payment-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <h2>Xác nhận đơn hàng</h2>

    <!-- Tóm tắt đơn hàng -->
    <table border="1" width="100%">
        <thead>
            <tr>
                <th>Sản phẩm</th>
                <th>Giá</th>
                <th>Số lượng</th>
                <th>Tổng</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cartItems as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item->product_name) ?></td>
                    <td><?= number_format($item->price, 0, ',', '.') ?> VND</td>
                    <td><?= $item->quantity ?></td>
                    <td><?= number_format($item->price * $item->quantity, 0, ',', '.') ?> VND</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h3>Tổng số tiền: <?= number_format($cartTotal, 0, ',', '.') ?> VND</h3>

    <!-- Phương thức thanh toán -->
    <h3>Chọn phương thức thanh toán</h3>
    <form action="index.php?controller=order&action=checkout" method="POST">
        <button type="submit" class="payment-button">Thanh toán khi nhận hàng (COD)</button>
    </form>

    <form action="config/vnpay_create_payment.php" id="frmCreateOrder" method="post">
        <input type="hidden" name="amount" value="<?= $cartTotal ?>">
        <input type="hidden" name="language" value="vn">
        <input type="hidden" name="bankCode" value="">
        <button type="submit" class="payment-button">Thanh toán qua VNPAY</button>
    </form>
</body>

</html>