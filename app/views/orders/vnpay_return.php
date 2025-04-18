<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>VNPAY RESPONSE</title>
    <link href="../../../public/assets/bootstrap.min.css" rel="stylesheet" />
    <link href="../../../public/assets/jumbotron-narrow.css" rel="stylesheet">
    <script src="../../../public/assets/jquery-1.11.3.min.js"></script>
</head>

<body>
    <?php
    //  require_once __DIR__ . '/config.php'; // Kết nối DB
    require_once '../../../config/config.php';
    require_once '../../../config/vnpayconfig.php';
    ini_set('session.cookie_samesite', 'None');
    ini_set('session.cookie_secure', '1');
    session_start();

    // echo "<pre>";
    // print_r($_SESSION);
    // echo "</pre>";
    $orderSuccess = false;
    $message = "Thanh toán không thành công!";
    $orderId = null;

    if (isset($_GET['vnp_ResponseCode']) && $_GET['vnp_ResponseCode'] == '00') {
        if (isset($_SESSION['pending_order'])) {
            $order = $_SESSION['pending_order'];
            $userId = $order['user_id'];
            $totalPrice = $order['total_price'];
            $items = $order['items'];
            try {
                // Bắt đầu transaction để đảm bảo dữ liệu chính xác
                $conn->beginTransaction();

                // Lưu vào bảng orders
                $stmt = $conn->prepare("INSERT INTO orders (user_id, total_price, status) VALUES (:user_id, :total_price, :status)");
                $stmt->execute([
                    ':user_id' => $userId,
                    ':total_price' => $totalPrice,
                    ':status' => 'pending'
                ]);

                $orderId = $conn->lastInsertId(); // Lấy ID đơn hàng mới tạo

                // Lưu vào bảng order_items
                $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) 
                                        VALUES (:order_id, :product_id, :quantity, :price)");
                foreach ($items as $item) {
                    $stmt->execute([
                        ':order_id' => $orderId,
                        ':product_id' => $item->product_id,
                        ':quantity' => $item->quantity,
                        ':price' => $item->price
                    ]);

                    // Cập nhật số lượng tồn kho
                    $updateStockStmt = $conn->prepare("UPDATE products 
                                                     SET stock = stock - :quantity
                                                     WHERE id = :product_id AND stock >= :quantity");
                    $updateStockStmt->execute([
                        ':product_id' => $item->product_id,
                        ':quantity' => $item->quantity
                    ]);
                }

                // Thêm đoạn này sau khi tạo đơn hàng và lấy orderId
                // Xử lý voucher nếu có
                if (isset($order['voucher_id']) && isset($order['discount_amount'])) {
                    $voucherId = $order['voucher_id'];
                    $discountAmount = $order['discount_amount'];

                    // Ghi nhận việc sử dụng voucher
                    $stmt = $conn->prepare("INSERT INTO voucher_usage (voucher_id, user_id, order_id, discount_amount) 
                                            VALUES (:voucher_id, :user_id, :order_id, :discount_amount)");
                    $stmt->execute([
                        ':voucher_id' => $voucherId,
                        ':user_id' => $userId,
                        ':order_id' => $orderId,
                        ':discount_amount' => $discountAmount
                    ]);

                    // Tăng số lượng đã sử dụng của voucher
                    $stmt = $conn->prepare("UPDATE vouchers SET used_count = used_count + 1 WHERE id = :voucher_id");
                    $stmt->execute([':voucher_id' => $voucherId]);
                }

                // Xóa giỏ hàng sau khi đơn hàng được lưu
                $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = :user_id");
                $stmt->execute([':user_id' => $userId]);

                // Commit transaction
                $conn->commit();

                // Xóa session pending_order
                unset($_SESSION['pending_order']);

                $orderSuccess = true;
                $message = "Thanh toán thành công! Đơn hàng của bạn đã được ghi nhận.";
            } catch (PDOException $e) {
                $conn->rollBack();
                echo "Error inserting order: " . $e->getMessage();
            }
        } else {
            $message = "Không tìm thấy đơn hàng tạm!";
        }
    }
    ?>

    <div class="container">
        <div class="header clearfix">
            <h3 class="text-muted">Kết quả thanh toán</h3>
        </div>

        <div class="alert <?php echo $orderSuccess ? 'alert-success' : 'alert-danger'; ?>">
            <strong><?php echo $message; ?></strong>
        </div>

        <?php if ($orderSuccess && $orderId): ?>
            <h4>Thông tin đơn hàng</h4>
            <table class="table table-bordered">
                <tr>
                    <th>Mã đơn hàng</th>
                    <td><?php echo htmlspecialchars($orderId); ?></td>
                </tr>
                <tr>
                    <th>Số tiền</th>
                    <td><?php echo number_format($_GET['vnp_Amount'] / 100, 0, ',', '.'); ?> VND</td>
                </tr>
                <tr>
                    <th>Nội dung thanh toán</th>
                    <td><?php echo htmlspecialchars($_GET['vnp_OrderInfo']); ?></td>
                </tr>
                <tr>
                    <th>Mã GD tại VNPAY</th>
                    <td><?php echo htmlspecialchars($_GET['vnp_TransactionNo']); ?></td>
                </tr>
                <tr>
                    <th>Mã ngân hàng</th>
                    <td><?php echo htmlspecialchars($_GET['vnp_BankCode']); ?></td>
                </tr>
                <tr>
                    <th>Thời gian thanh toán</th>
                    <td><?php echo htmlspecialchars($_GET['vnp_PayDate']); ?></td>
                </tr>
                <?php if (isset($order['voucher_code']) && isset($order['discount_amount'])): ?>
                    <tr>
                        <th>Mã giảm giá</th>
                        <td><?php echo htmlspecialchars($order['voucher_code']); ?> (-<?php echo number_format($order['discount_amount'], 0, ',', '.'); ?> VND)</td>
                    </tr>
                <?php endif; ?>
            </table>

            <h4>Chi tiết sản phẩm</h4>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Tổng</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item->product_name); ?></td>
                            <td><?php echo number_format($item->price, 0, ',', '.'); ?> VND</td>
                            <td><?php echo $item->quantity; ?></td>
                            <td><?php echo number_format($item->price * $item->quantity, 0, ',', '.'); ?> VND</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
        <a href="http://localhost/DoAnPHP/index.php">Về trang chủ</a>

    </div>

    <footer class="footer text-center">
        <p>&copy; VNPAY <?php echo date('Y'); ?></p>
    </footer>
</body>

</html>