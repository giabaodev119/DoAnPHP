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
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> - Shop Công nghệ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="public/css/style.css">
    <style>
        body {
            background-color: #f8f9fa;
            color: #343a40;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .checkout-container {
            padding: 30px 0;
        }

        .checkout-title {
            text-align: center;
            margin-bottom: 30px;
            color: #212529;
            font-weight: 600;
            position: relative;
            padding-bottom: 15px;
        }

        .checkout-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background-color: #ff6600;
        }

        .checkout-card {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            overflow: hidden;
            margin-bottom: 30px;
        }

        .checkout-card-header {
            background-color: #f1f8ff;
            padding: 15px 20px;
            border-bottom: 1px solid #e3f2fd;
        }

        .checkout-card-title {
            margin: 0;
            color: #0d6efd;
            font-size: 1.2rem;
            font-weight: 600;
        }

        .checkout-card-body {
            padding: 20px;
        }

        .product-table {
            margin-bottom: 0;
        }

        .product-table img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 5px;
            border: 1px solid #dee2e6;
        }

        .product-name {
            font-weight: 500;
            color: #212529;
        }

        .product-price {
            color: #dc3545;
            font-weight: 500;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px dashed #dee2e6;
        }

        .summary-total {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
            font-weight: 700;
            font-size: 1.2rem;
            color: #dc3545;
            border-top: 2px solid #dee2e6;
            padding-top: 15px;
        }

        .payment-method-card {
            cursor: pointer;
            border: 2px solid #dee2e6;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            transition: all 0.3s;
            display: flex;
            align-items: center;
        }

        .payment-method-card:hover {
            border-color: #0d6efd;
            background-color: #f1f8ff;
        }

        .payment-method-card.selected {
            border-color: #0d6efd;
            background-color: #f1f8ff;
        }

        .payment-icon {
            font-size: 2rem;
            margin-right: 15px;
        }

        .cod-icon {
            color: #28a745;
        }

        .vnpay-icon {
            color: #0d6efd;
        }

        .payment-button {
            display: block;
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-cod {
            background-color: #28a745;
            color: white;
        }

        .btn-cod:hover {
            background-color: #218838;
        }

        .btn-vnpay {
            background-color: #0d6efd;
            color: white;
        }

        .btn-vnpay:hover {
            background-color: #0b5ed7;
        }

        .btn-back {
            background-color: #6c757d;
            color: white;
        }

        .btn-back:hover {
            background-color: #5a6268;
        }
    </style>
</head>

<body>
    <?php include 'app/views/partials/header.php'; ?>

    <div class="container checkout-container">
        <h1 class="checkout-title">Thanh toán đơn hàng</h1>

        <div class="row">
            <div class="col-lg-8">
                <!-- Chi tiết đơn hàng -->
                <div class="checkout-card">
                    <div class="checkout-card-header">
                        <h2 class="checkout-card-title">
                            <i class="fas fa-shopping-cart me-2"></i>Chi tiết đơn hàng
                        </h2>
                    </div>
                    <div class="checkout-card-body">
                        <div class="table-responsive">
                            <table class="table table-hover product-table align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Sản phẩm</th>
                                        <th>Giá</th>
                                        <th class="text-center">Số lượng</th>
                                        <th class="text-end">Thành tiền</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($cartItems as $item): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <?php
                                                    $productModel = new Product();
                                                    $images = $productModel->getProductImages($item->product_id);
                                                    $imagePath = !empty($images) ? 'public/images/' . $images[0]['image_path'] : 'public/images/default.jpg';
                                                    if (!file_exists($imagePath)) {
                                                        $imagePath = 'public/images/default.jpg';
                                                    }
                                                    ?>
                                                    <img src="<?= $imagePath ?>" class="me-3" alt="<?= htmlspecialchars($item->product_name) ?>">
                                                    <span class="product-name"><?= htmlspecialchars($item->product_name) ?></span>
                                                </div>
                                            </td>
                                            <td class="product-price"><?= number_format($item->price, 0, ',', '.') ?> ₫</td>
                                            <td class="text-center"><?= $item->quantity ?></td>
                                            <td class="text-end product-price"><?= number_format($item->price * $item->quantity, 0, ',', '.') ?> ₫</td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Phương thức thanh toán -->
                <div class="checkout-card">
                    <div class="checkout-card-header">
                        <h2 class="checkout-card-title">
                            <i class="fas fa-credit-card me-2"></i>Phương thức thanh toán
                        </h2>
                    </div>
                    <div class="checkout-card-body">
                        <div class="payment-methods">
                            <div class="payment-method-card mb-4" id="cod-method" onclick="selectPaymentMethod('cod')">
                                <div class="payment-icon cod-icon">
                                    <i class="fas fa-money-bill-wave"></i>
                                </div>
                                <div class="payment-info">
                                    <h5 class="mb-1">Thanh toán khi nhận hàng (COD)</h5>
                                    <p class="mb-0 text-muted">Bạn sẽ thanh toán khi nhận được hàng</p>
                                </div>
                            </div>

                            <div class="payment-method-card" id="vnpay-method" onclick="selectPaymentMethod('vnpay')">
                                <div class="payment-icon vnpay-icon">
                                    <i class="fas fa-university"></i>
                                </div>
                                <div class="payment-info">
                                    <h5 class="mb-1">Thanh toán qua VNPAY</h5>
                                    <p class="mb-0 text-muted">Thanh toán an toàn với VNPay-QR</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Tóm tắt đơn hàng -->
                <div class="checkout-card">
                    <div class="checkout-card-header">
                        <h2 class="checkout-card-title">
                            <i class="fas fa-file-invoice-dollar me-2"></i>Tóm tắt đơn hàng
                        </h2>
                    </div>
                    <div class="checkout-card-body">
                        <?php
                        $subtotal = $cartTotal;
                        $shipping = 0; // Có thể thay đổi nếu có phí vận chuyển
                        $total = $subtotal + $shipping;
                        ?>
                        <div class="summary-item">
                            <span>Tạm tính:</span>
                            <span><?= number_format($subtotal, 0, ',', '.') ?> ₫</span>
                        </div>
                        <div class="summary-item">
                            <span>Phí vận chuyển:</span>
                            <span><?= $shipping > 0 ? number_format($shipping, 0, ',', '.') . ' ₫' : 'Miễn phí' ?></span>
                        </div>
                        <div class="summary-total">
                            <span>Tổng cộng:</span>
                            <span><?= number_format($total, 0, ',', '.') ?> ₫</span>
                        </div>

                        <div class="d-grid gap-3 mt-4">
                            <form action="index.php?controller=order&action=checkout" method="POST" id="codForm">
                                <button type="submit" class="payment-button btn-cod" id="codButton">
                                    <i class="fas fa-money-bill-wave me-2"></i>Thanh toán khi nhận hàng
                                </button>
                            </form>

                            <form action="config/vnpay_create_payment.php" id="vnpayForm" method="post">
                                <input type="hidden" name="amount" value="<?= $cartTotal ?>">
                                <input type="hidden" name="language" value="vn">
                                <input type="hidden" name="bankCode" value="">
                                <button type="submit" class="payment-button btn-vnpay" id="vnpayButton">
                                    <i class="fas fa-university me-2"></i>Thanh toán qua VNPAY
                                </button>
                            </form>

                            <a href="index.php?controller=cart" class="payment-button btn-back text-decoration-none text-center">
                                <i class="fas fa-arrow-left me-2"></i>Quay lại giỏ hàng
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'app/views/partials/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function selectPaymentMethod(method) {
            // Xóa trạng thái đã chọn của tất cả các phương thức
            document.getElementById('cod-method').classList.remove('selected');
            document.getElementById('vnpay-method').classList.remove('selected');

            // Ẩn tất cả các nút thanh toán
            document.getElementById('codButton').style.display = 'none';
            document.getElementById('vnpayButton').style.display = 'none';

            // Thêm trạng thái đã chọn cho phương thức được chọn và hiển thị nút tương ứng
            if (method === 'cod') {
                document.getElementById('cod-method').classList.add('selected');
                document.getElementById('codButton').style.display = 'block';
            } else if (method === 'vnpay') {
                document.getElementById('vnpay-method').classList.add('selected');
                document.getElementById('vnpayButton').style.display = 'block';
            }
        }

        // Mặc định chọn phương thức COD khi trang được tải
        document.addEventListener('DOMContentLoaded', function() {
            selectPaymentMethod('cod');
        });
    </script>
</body>

</html>