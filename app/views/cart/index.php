<?php
// Make sure we have the cart data
$cartItems = $data['cart_items'] ?? [];
$cartTotal = $data['cart_total'] ?? 0;
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ hàng của bạn</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .cart-container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            margin-bottom: 30px;
        }

        .cart-header {
            background-color: #f8f9fa;
            padding: 20px 30px;
            border-bottom: 1px solid #e9ecef;
        }

        .cart-title {
            margin: 0;
            color: #343a40;
            font-weight: 600;
        }

        .cart-body {
            padding: 20px 30px;
        }

        .cart-footer {
            background-color: #f8f9fa;
            padding: 15px 30px;
            border-top: 1px solid #e9ecef;
        }

        .product-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 5px;
            border: 1px solid #e9ecef;
            transition: transform 0.3s;
        }

        .product-image:hover {
            transform: scale(1.05);
        }

        .product-name {
            font-weight: 500;
            color: #212529;
            transition: color 0.3s;
        }

        .product-name:hover {
            color: #007bff;
            text-decoration: none;
        }

        .price {
            font-weight: 600;
            color: #dc3545;
        }

        .total-price {
            font-size: 1.2rem;
            font-weight: 700;
            color: #dc3545;
        }

        .btn-quantity {
            background-color: #f8f9fa;
            border: 1px solid #ced4da;
            color: #212529;
            width: 30px;
            height: 30px;
            padding: 0;
            font-size: 14px;
            border-radius: 4px;
        }

        .quantity-input {
            width: 50px;
            text-align: center;
            border: 1px solid #ced4da;
            border-radius: 4px;
            padding: 4px 0;
            margin: 0 5px;
        }

        .btn-cart-action {
            transition: all 0.3s;
        }

        .btn-continue {
            background-color: #6c757d;
            border-color: #6c757d;
            color: white;
        }

        .btn-continue:hover {
            background-color: #5a6268;
            border-color: #545b62;
            color: white;
        }

        .btn-clear {
            background-color: #ffc107;
            border-color: #ffc107;
            color: #212529;
        }

        .btn-clear:hover {
            background-color: #e0a800;
            border-color: #d39e00;
            color: #212529;
        }

        .btn-checkout {
            background-color: #28a745;
            border-color: #28a745;
            color: white;
        }

        .btn-checkout:hover {
            background-color: #218838;
            border-color: #1e7e34;
            color: white;
        }

        .empty-cart {
            padding: 40px 20px;
            text-align: center;
        }

        .empty-cart i {
            font-size: 60px;
            color: #6c757d;
            margin-bottom: 20px;
        }

        .empty-cart h3 {
            margin-bottom: 15px;
            color: #343a40;
        }

        .cart-item {
            transition: background-color 0.3s;
        }

        .cart-item:hover {
            background-color: #f8f9fa;
        }

        .quantity-control {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-remove {
            color: white;
            background-color: #dc3545;
            border-color: #dc3545;
            transition: all 0.3s;
        }

        .btn-remove:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }

        .voucher-container {
            margin-top: 20px;
        }

        .voucher-form {
            margin-top: 10px;
        }

        .cart-summary {
            margin-top: 20px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .subtotal span,
        .total span {
            font-weight: 600;
        }

        .discount span {
            font-weight: 600;
            color: #dc3545;
        }

        @media (max-width: 768px) {
            .cart-body {
                padding: 15px;
            }

            .product-image {
                width: 60px;
                height: 60px;
            }
        }

        .voucher-container {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 8px;
        }

        .voucher-form {
            margin-top: 10px;
        }

        #voucherMessage {
            margin-top: 8px;
            font-size: 14px;
        }

        .voucher-success {
            color: #28a745;
        }

        .voucher-error {
            color: #dc3545;
        }

        .summary-row.discount {
            color: #28a745;
            font-weight: 500;
        }
    </style>
</head>

<body>
    <?php include 'app/views/partials/header.php'; ?>

    <div class="container py-5">
        <div class="row">
            <div class="col-md-12">
                <div class="cart-container">
                    <div class="cart-header">
                        <h1 class="cart-title">
                            <i class="fas fa-shopping-cart me-2"></i>Giỏ hàng của bạn
                        </h1>
                    </div>

                    <?php if (isset($_SESSION['message'])): ?>
                        <div id="alertMessage" class="alert alert-success m-3 fade show">
                            <i class="fas fa-check-circle me-2"></i>
                            <?php
                            echo $_SESSION['message'];
                            unset($_SESSION['message']);
                            ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <script>
                            // Tự động ẩn thông báo sau 5 giây
                            document.addEventListener('DOMContentLoaded', function() {
                                const alertMessage = document.getElementById('alertMessage');
                                if (alertMessage) {
                                    // Hiển thị thanh tiến trình đếm ngược
                                    let timeLeft = 5;
                                    const progressBar = document.createElement('div');
                                    progressBar.style.height = '3px';
                                    progressBar.style.backgroundColor = '#28a745';
                                    progressBar.style.width = '100%';
                                    progressBar.style.position = 'absolute';
                                    progressBar.style.bottom = '0';
                                    progressBar.style.left = '0';
                                    progressBar.style.transition = 'width 5s linear';
                                    alertMessage.style.position = 'relative';
                                    alertMessage.appendChild(progressBar);

                                    // Bắt đầu đếm ngược
                                    setTimeout(() => {
                                        progressBar.style.width = '0';
                                    }, 100);

                                    // Ẩn thông báo sau 5 giây
                                    setTimeout(() => {
                                        const bsAlert = new bootstrap.Alert(alertMessage);
                                        bsAlert.close();
                                    }, 5000);
                                }
                            });
                        </script>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['error'])): ?>
                        <div id="errorMessage" class="alert alert-danger m-3 fade show">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <?php
                            echo $_SESSION['error'];
                            unset($_SESSION['error']);
                            ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <script>
                            // Tự động ẩn thông báo lỗi sau 8 giây (để người dùng có thời gian đọc)
                            document.addEventListener('DOMContentLoaded', function() {
                                const errorMessage = document.getElementById('errorMessage');
                                if (errorMessage) {
                                    // Hiển thị thanh tiến trình đếm ngược
                                    let timeLeft = 8;
                                    const progressBar = document.createElement('div');
                                    progressBar.style.height = '3px';
                                    progressBar.style.backgroundColor = '#dc3545';
                                    progressBar.style.width = '100%';
                                    progressBar.style.position = 'absolute';
                                    progressBar.style.bottom = '0';
                                    progressBar.style.left = '0';
                                    progressBar.style.transition = 'width 8s linear';
                                    errorMessage.style.position = 'relative';
                                    errorMessage.appendChild(progressBar);

                                    // Bắt đầu đếm ngược
                                    setTimeout(() => {
                                        progressBar.style.width = '0';
                                    }, 100);

                                    // Ẩn thông báo sau 8 giây
                                    setTimeout(() => {
                                        const bsAlert = new bootstrap.Alert(errorMessage);
                                        bsAlert.close();
                                    }, 8000);
                                }
                            });
                        </script>
                    <?php endif; ?>

                    <div class="cart-body">
                        <?php if (empty($cartItems)): ?>
                            <div class="empty-cart">
                                <i class="fas fa-shopping-cart"></i>
                                <h3>Giỏ hàng của bạn đang trống</h3>
                                <p class="text-muted">Hãy thêm một số sản phẩm vào giỏ hàng của bạn và quay lại.</p>
                                <a href="index.php?controller=home&action=index" class="btn btn-primary mt-3">
                                    <i class="fas fa-shopping-bag me-2"></i>Tiếp tục mua sắm
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Sản phẩm</th>
                                            <th class="text-center">Giá</th>
                                            <th class="text-center">Số lượng</th>
                                            <th class="text-end">Thành tiền</th>
                                            <th class="text-center">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($cartItems as $item): ?>
                                            <tr class="cart-item">
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <a href="index.php?controller=product&action=detail&id=<?= $item->product_id ?>">
                                                            <img src="<?= !empty($item->image_path) ? 'public/images/' . htmlspecialchars($item->image_path) : 'public/images/default.jpg' ?>"
                                                                alt="<?= htmlspecialchars($item->product_name) ?>" class="product-image me-3">
                                                        </a>
                                                        <div>
                                                            <a href="index.php?controller=product&action=detail&id=<?= $item->product_id ?>" class="product-name">
                                                                <?= htmlspecialchars($item->product_name) ?>
                                                            </a>
                                                            <?php if (isset($item->category_name)): ?>
                                                                <div class="small text-muted">
                                                                    Danh mục: <?= htmlspecialchars($item->category_name) ?>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center price"><?= number_format($item->price, 0, ',', '.') ?> đ</td>
                                                <td class="text-center">
                                                    <form method="post" action="index.php?controller=cart&action=update" class="quantity-control">
                                                        <input type="hidden" name="cart_id" value="<?= $item->cart_id ?>">
                                                        <button type="button" class="btn-quantity" onclick="this.parentNode.querySelector('input[type=number]').stepDown(); this.parentNode.submit();">
                                                            <i class="fas fa-minus"></i>
                                                        </button>
                                                        <input type="number" class="quantity-input" name="quantity" value="<?= $item->quantity ?>" min="1" max="99" onchange="this.form.submit()">
                                                        <button type="button" class="btn-quantity" onclick="this.parentNode.querySelector('input[type=number]').stepUp(); this.parentNode.submit();">
                                                            <i class="fas fa-plus"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                                <td class="text-end price"><?= number_format($item->price * $item->quantity, 0, ',', '.') ?> đ</td>
                                                <td class="text-center">
                                                    <form method="post" action="index.php?controller=cart&action=remove">
                                                        <input type="hidden" name="cart_id" value="<?= $item->cart_id ?>">
                                                        <button type="submit" class="btn btn-sm btn-remove" title="Xóa sản phẩm">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <tfoot class="table-light">
                                        <tr>
                                            <td colspan="3" class="text-end fw-bold">Tổng cộng:</td>
                                            <td class="text-end total-price"><?= number_format($cartTotal, 0, ',', '.') ?> đ</td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <!-- Thêm form nhập voucher -->
                            <div class="voucher-container">
                                <h4><i class="fas fa-tags me-2"></i>Mã giảm giá</h4>
                                <div class="voucher-form">
                                    <div class="input-group">
                                        <input type="text" id="voucherCode" class="form-control" placeholder="Nhập mã giảm giá..."
                                            value="<?= isset($_SESSION['voucher']) ? $_SESSION['voucher']['code'] : '' ?>"
                                            <?= isset($_SESSION['voucher']) ? 'disabled' : '' ?>>
                                        <?php if (!isset($_SESSION['voucher'])): ?>
                                            <button type="button" id="applyVoucher" class="btn btn-primary">Áp dụng</button>
                                        <?php else: ?>
                                            <button type="button" id="removeVoucher" class="btn btn-danger">Hủy</button>
                                        <?php endif; ?>
                                    </div>
                                    <div id="voucherMessage"></div>
                                </div>
                            </div>

                            <!-- Tổng tiền giỏ hàng -->
                            <div class="cart-summary">
                                <div class="summary-row subtotal">
                                    <span>Tạm tính:</span>
                                    <span id="subtotal"><?= number_format($cartTotal, 0, ',', '.') ?> đ</span>
                                </div>

                                <!-- Hiển thị giảm giá từ voucher nếu có -->
                                <?php
                                $discount = 0;
                                if (isset($_SESSION['voucher'])) {
                                    $discount = $_SESSION['voucher']['discount_amount'];
                                }
                                ?>

                                <div id="discountRow" class="summary-row discount <?= $discount > 0 ? '' : 'd-none' ?>">
                                    <span>Giảm giá:</span>
                                    <span id="discount" class="text-danger">-<?= number_format($discount, 0, ',', '.') ?> đ</span>
                                </div>

                                <div class="summary-row total">
                                    <span>Thành tiền:</span>
                                    <span id="total"><?= number_format($cartTotal - $discount, 0, ',', '.') ?> đ</span>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <?php if (!empty($cartItems)): ?>
                        <div class="cart-footer d-flex justify-content-between">
                            <a href="index.php?controller=home&action=index" class="btn btn-continue">
                                <i class="fas fa-arrow-left me-2"></i>Tiếp tục mua sắm
                            </a>
                            <div>
                                <form method="post" action="index.php?controller=cart&action=clear" class="d-inline">
                                    <button type="submit" class="btn btn-clear me-2" onclick="return confirm('Bạn có chắc muốn xóa tất cả sản phẩm khỏi giỏ hàng?')">
                                        <i class="fas fa-trash-alt me-2"></i>Xóa giỏ hàng
                                    </button>
                                </form>
                                <a href="index.php?controller=order&action=checkout" class="btn btn-checkout">
                                    <i class="fas fa-shopping-bag me-2"></i>Thanh toán
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <?php include 'app/views/partials/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Xử lý áp dụng voucher
            const applyVoucherBtn = document.getElementById('applyVoucher');
            if (applyVoucherBtn) {
                applyVoucherBtn.addEventListener('click', function() {
                    const voucherCode = document.getElementById('voucherCode').value.trim();
                    if (!voucherCode) {
                        showVoucherMessage('Vui lòng nhập mã giảm giá', 'error');
                        return;
                    }

                    applyVoucher(voucherCode);
                });
            }

            // Xử lý xóa voucher
            const removeVoucherBtn = document.getElementById('removeVoucher');
            if (removeVoucherBtn) {
                removeVoucherBtn.addEventListener('click', function() {
                    removeVoucher();
                });
            }

            // Hàm áp dụng voucher
            function applyVoucher(code) {
                // Hiện loading
                showVoucherMessage('Đang kiểm tra mã...', '');

                const formData = new FormData();
                formData.append('voucher_code', code);

                fetch('index.php?controller=voucher&action=apply', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Cập nhật UI khi áp dụng thành công
                            document.getElementById('voucherCode').disabled = true;
                            document.getElementById('applyVoucher').innerHTML = 'Hủy';
                            document.getElementById('applyVoucher').id = 'removeVoucher';
                            document.getElementById('removeVoucher').classList.replace('btn-primary', 'btn-danger');
                            document.getElementById('removeVoucher').addEventListener('click', removeVoucher);

                            // Hiển thị thông tin giảm giá
                            document.getElementById('discount').innerHTML = '-' + data.discount_formatted;
                            document.getElementById('discountRow').classList.remove('d-none');
                            document.getElementById('total').innerHTML = data.new_total_formatted;

                            showVoucherMessage(data.message, 'success');
                        } else {
                            showVoucherMessage(data.message, 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showVoucherMessage('Có lỗi xảy ra, vui lòng thử lại sau', 'error');
                    });
            }

            // Hàm xóa voucher
            function removeVoucher() {
                // Hiện loading
                showVoucherMessage('Đang xóa mã...', '');

                fetch('index.php?controller=voucher&action=remove', {
                        method: 'POST'
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Cập nhật UI khi xóa thành công
                            document.getElementById('voucherCode').disabled = false;
                            document.getElementById('voucherCode').value = '';
                            document.getElementById('removeVoucher').innerHTML = 'Áp dụng';
                            document.getElementById('removeVoucher').id = 'applyVoucher';
                            document.getElementById('applyVoucher').classList.replace('btn-danger', 'btn-primary');
                            document.getElementById('applyVoucher').addEventListener('click', function() {
                                const code = document.getElementById('voucherCode').value.trim();
                                if (code) applyVoucher(code);
                            });

                            // Ẩn thông tin giảm giá
                            document.getElementById('discountRow').classList.add('d-none');
                            document.getElementById('total').innerHTML = document.getElementById('subtotal').innerHTML;

                            showVoucherMessage('', '');
                        } else {
                            showVoucherMessage(data.message, 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showVoucherMessage('Có lỗi xảy ra, vui lòng thử lại sau', 'error');
                    });
            }

            // Hiển thị thông báo
            function showVoucherMessage(message, type) {
                const messageEl = document.getElementById('voucherMessage');
                messageEl.textContent = message;

                // Xóa các class hiện tại
                messageEl.classList.remove('voucher-success', 'voucher-error');

                if (type === 'success') {
                    messageEl.classList.add('voucher-success');
                } else if (type === 'error') {
                    messageEl.classList.add('voucher-error');
                }
            }
        });
    </script>
</body>

</html>