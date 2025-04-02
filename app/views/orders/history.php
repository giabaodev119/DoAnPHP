<?php
// Kiểm tra nếu không có đơn hàng
if (empty($data['orders'])) {
    $_SESSION['message'] = "Bạn chưa có đơn hàng nào.";
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lịch sử đơn hàng - Shop Công nghệ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="public/css/style.css">
    <style>
        body {
            background-color: #f8f9fa;
            color: #343a40;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .order-history-container {
            padding: 30px 0;
        }

        .order-card {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            margin-bottom: 20px;
            overflow: hidden;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .order-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .order-header {
            background-color: #f8f9fa;
            padding: 15px 20px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .order-id {
            font-weight: 600;
            color: #495057;
            font-size: 1.1rem;
        }

        .order-date {
            color: #6c757d;
            font-size: 0.9rem;
        }

        .order-body {
            padding: 20px;
        }

        .order-footer {
            padding: 15px 20px;
            background-color: #f8f9fa;
            border-top: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .order-total {
            font-weight: 600;
            color: #dc3545;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .btn-view-order {
            background-color: #0d6efd;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 0.9rem;
            transition: background-color 0.2s;
        }

        .btn-view-order:hover {
            background-color: #0b5ed7;
            color: white;
        }

        .no-orders {
            text-align: center;
            padding: 50px 0;
        }

        .no-orders i {
            font-size: 4rem;
            color: #dee2e6;
            margin-bottom: 20px;
        }

        .empty-message {
            font-size: 1.5rem;
            color: #6c757d;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    <?php include 'app/views/partials/header.php'; ?>

    <div class="container order-history-container">
        <h1 class="mb-4 text-center">Lịch sử đơn hàng</h1>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= $_SESSION['message'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= $_SESSION['error'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if (empty($data['orders'])): ?>
            <div class="no-orders">
                <i class="fas fa-shopping-bag"></i>
                <h3 class="empty-message">Bạn chưa có đơn hàng nào</h3>
                <p class="text-muted">Tiếp tục mua sắm để bắt đầu tạo lịch sử đơn hàng của bạn.</p>
                <a href="index.php" class="btn btn-primary mt-3">
                    <i class="fas fa-shopping-cart me-2"></i>Mua sắm ngay
                </a>
            </div>
        <?php else: ?>
            <?php foreach ($data['orders'] as $order): ?>
                <div class="order-card">
                    <div class="order-header">
                        <div class="order-id">
                            <i class="fas fa-receipt me-2"></i>Đơn hàng #<?= $order['id'] ?>
                        </div>
                        <div class="order-date">
                            <i class="far fa-calendar-alt me-1"></i>
                            <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?>
                        </div>
                    </div>

                    <div class="order-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Trạng thái:</strong>
                                    <?php
                                    switch ($order['status']) {
                                        case 'pending':
                                        case 'pendings':
                                            echo '<span class="status-badge bg-warning text-dark">Chờ xử lý</span>';
                                            break;
                                        case 'processing':
                                            echo '<span class="status-badge bg-info text-white">Đang xử lý</span>';
                                            break;
                                        case 'shipping':
                                            echo '<span class="status-badge bg-primary text-white">Đang giao hàng</span>';
                                            break;
                                        case 'completed':
                                            echo '<span class="status-badge bg-success text-white">Hoàn thành</span>';
                                            break;
                                        case 'cancelled':
                                            echo '<span class="status-badge bg-danger text-white">Đã hủy</span>';
                                            break;
                                        default:
                                            echo '<span class="status-badge bg-secondary text-white">' . ucfirst($order['status']) . '</span>';
                                    }
                                    ?>
                                </p>
                                <p class="mb-0"><strong>Phương thức thanh toán:</strong>
                                    <?php echo (strpos($order['status'], 'pending') !== false) ? 'VNPAY' : 'COD'; ?>
                                </p>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <?php if ($order['status'] == 'shipping'): ?>
                                    <form action="index.php?controller=order&action=confirmReceipt" method="post" class="d-inline">
                                        <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                        <button type="submit" class="btn btn-success" onclick="return confirm('Xác nhận bạn đã nhận được hàng?')">
                                            <i class="fas fa-check-circle me-1"></i>Xác nhận đã nhận hàng
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="order-footer">
                        <div class="order-total">
                            Tổng tiền: <?= number_format($order['total_price'], 0, ',', '.') ?> đ
                        </div>
                        <a href="index.php?controller=order&action=details&id=<?= $order['id'] ?>" class="btn-view-order">
                            <i class="fas fa-eye me-1"></i>Xem chi tiết
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <?php include 'app/views/partials/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>