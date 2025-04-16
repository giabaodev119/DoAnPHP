<?php
// filepath: c:\xampp\htdocs\DoAnPHP\app\views\orders\success.php
// Kiểm tra nếu không có thông tin đơn hàng
if (!isset($orderId) && isset($_SESSION['order_id'])) {
    $orderId = $_SESSION['order_id'];
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt hàng thành công - Shop Công nghệ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="public/css/style.css">
    <style>
        body {
            background-color: #f8f9fa;
            color: #343a40;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .success-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 50px 0;
        }

        .success-card {
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 100%;
            max-width: 600px;
            text-align: center;
        }

        .success-header {
            background-color: #d1e7dd;
            padding: 30px 20px;
            position: relative;
        }

        .success-icon {
            width: 100px;
            height: 100px;
            background-color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .success-icon i {
            font-size: 50px;
            color: #198754;
        }

        .success-title {
            color: #0f5132;
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .success-message {
            color: #0f5132;
            font-size: 18px;
            margin-bottom: 0;
        }

        .success-body {
            padding: 30px;
        }

        .order-info {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
        }

        .order-info-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px dashed #dee2e6;
        }

        .order-info-item:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }

        .order-info-label {
            font-weight: 500;
            color: #6c757d;
        }

        .order-info-value {
            font-weight: 600;
            color: #212529;
        }

        .btn-home {
            background-color: #ff6600;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 5px;
            font-weight: 500;
            margin-right: 10px;
            transition: all 0.3s;
        }

        .btn-home:hover {
            background-color: #e55d00;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            color: white;
        }

        .btn-order {
            background-color: #6c757d;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 5px;
            font-weight: 500;
            transition: all 0.3s;
        }

        .btn-order:hover {
            background-color: #5a6268;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            color: white;
        }
    </style>
</head>

<body>
    <?php include 'app/views/partials/header.php'; ?>

    <div class="success-container">
        <div class="success-card">
            <div class="success-header">
                <div class="success-icon">
                    <i class="fas fa-check"></i>
                </div>
                <h1 class="success-title">Đặt hàng thành công!</h1>
                <p class="success-message">Cảm ơn bạn đã mua sắm tại Shop Công nghệ</p>
            </div>

            <div class="success-body">
                <div class="order-info">
                    <?php if (isset($orderId)): ?>
                        <div class="order-info-item">
                            <span class="order-info-label">Mã đơn hàng:</span>
                            <span class="order-info-value">#<?= $orderId ?></span>
                        </div>
                    <?php endif; ?>

                    <div class="order-info-item">
                        <span class="order-info-label">Trạng thái:</span>
                        <span class="order-info-value text-success">Đã xác nhận</span>
                    </div>

                    <div class="order-info-item">
                        <span class="order-info-label">Thời gian:</span>
                        <span class="order-info-value"><?= date('H:i:s d/m/Y') ?></span>
                    </div>
                </div>

                <!-- Chi tiết sản phẩm trong đơn hàng -->
                <?php if (isset($orderItems) && !empty($orderItems)): ?>
                    <div class="order-products mt-4">
                        <h5 class="mb-3">Chi tiết sản phẩm</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Sản phẩm</th>
                                        <th class="text-center">Giá</th>
                                        <th class="text-center">Số lượng</th>
                                        <th class="text-end">Thành tiền</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $total = 0;
                                    foreach ($orderItems as $item):
                                        $itemTotal = $item['price'] * $item['quantity'];
                                        $total += $itemTotal;
                                    ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <?php if (!empty($item['image_path'])): ?>
                                                        <img src="public/images/<?= htmlspecialchars($item['image_path']) ?>"
                                                            class="me-3" alt="<?= htmlspecialchars($item['product_name']) ?>"
                                                            style="width: 60px; height: 60px; object-fit: cover;">
                                                    <?php else: ?>
                                                        <img src="public/images/default.jpg"
                                                            class="me-3" alt="<?= htmlspecialchars($item['product_name']) ?>"
                                                            style="width: 60px; height: 60px; object-fit: cover;">
                                                    <?php endif; ?>
                                                    <div>
                                                        <div class="fw-bold"><?= htmlspecialchars($item['product_name']) ?></div>
                                                        <?php if (!empty($item['size'])): ?>
                                                            <small class="text-muted">Size: <?= htmlspecialchars($item['size']) ?></small>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center"><?= number_format($item['price'], 0, ',', '.') ?> đ</td>
                                            <td class="text-center"><?= $item['quantity'] ?></td>
                                            <td class="text-end"><?= number_format($itemTotal, 0, ',', '.') ?> đ</td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot class="table-light">
                                    <?php if (isset($orderInfo['has_voucher']) && $orderInfo['has_voucher']): ?>
                                        <tr>
                                            <td colspan="3" class="text-end">Tạm tính:</td>
                                            <td class="text-end"><?= number_format($total, 0, ',', '.') ?> đ</td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="text-end">Giảm giá (Mã: <?= $orderInfo['voucher_code'] ?>):</td>
                                            <td class="text-end text-danger">-<?= number_format($orderInfo['discount_amount'], 0, ',', '.') ?> đ</td>
                                        </tr>
                                    <?php endif; ?>
                                    <tr>
                                        <td colspan="3" class="text-end fw-bold">Tổng cộng:</td>
                                        <td class="text-end fw-bold"><?= isset($orderInfo['total_price']) ? number_format($orderInfo['total_price'], 0, ',', '.') : number_format($total, 0, ',', '.') ?> đ</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                <?php endif; ?>

                <p class="mb-4">Chúng tôi sẽ xử lý đơn hàng của bạn trong thời gian sớm nhất. Bạn có thể theo dõi trạng thái đơn hàng trong mục "Lịch sử đơn hàng" tại trang cá nhân.</p>

                <div class="d-flex justify-content-center flex-wrap gap-3">
                    <a href="index.php" class="btn-home text-decoration-none">
                        <i class="fas fa-home me-2"></i>Về trang chủ
                    </a>

                    <?php if (isset($orderId)): ?>
                        <a href="index.php?controller=order&action=history" class="btn-order text-decoration-none">
                            <i class="fas fa-receipt me-2"></i>Xem lịch sử đơn hàng
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <?php include 'app/views/partials/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>