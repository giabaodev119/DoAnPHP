<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết đơn hàng #<?= $order['id'] ?> - Admin</title>
    <link rel="stylesheet" href="public/css/bootstrap.min.css" />
    <link rel="stylesheet" href="public/css/plugins.min.css" />
    <link rel="stylesheet" href="public/css/kaiadmin.min.css" />
    <link rel="stylesheet" href="public/css/demo.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        .order-status {
            display: inline-block;
            padding: 0.5em 0.8em;
            font-size: 0.85em;
            font-weight: 700;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 0.25rem;
        }

        .status-pending,
        .status-pendings {
            background-color: #ffc107;
            color: #212529;
        }

        .status-processing {
            background-color: #0dcaf0;
            color: #fff;
        }

        .status-ready_to_ship {
            background-color: #6f42c1;
            color: #fff;
        }

        .status-shipping {
            background-color: #0d6efd;
            color: #fff;
        }

        .status-completed {
            background-color: #198754;
            color: #fff;
        }

        .status-cancelled {
            background-color: #dc3545;
            color: #fff;
        }

        .order-detail-card {
            margin-bottom: 1.5rem;
            border-radius: 0.5rem;
            overflow: hidden;
        }

        .order-detail-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
        }

        .order-detail-title i {
            margin-right: 0.5rem;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #e9ecef;
            border-radius: 50%;
        }

        .order-info-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.75rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px dashed #dee2e6;
        }

        .order-info-item:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }

        .order-info-label {
            color: #6c757d;
            font-weight: 500;
        }

        .order-info-value {
            font-weight: 500;
        }

        .product-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 4px;
        }

        .product-name {
            font-weight: 500;
        }

        .status-history-item {
            position: relative;
            padding-left: 30px;
            padding-bottom: 1.5rem;
            margin-bottom: 0.5rem;
        }

        .status-history-item::before {
            content: '';
            position: absolute;
            left: 10px;
            top: 0;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: #0d6efd;
        }

        .status-history-item::after {
            content: '';
            position: absolute;
            left: 14px;
            top: 10px;
            width: 2px;
            height: calc(100% - 10px);
            background-color: #dee2e6;
        }

        .status-history-item:last-child::after {
            display: none;
        }

        .status-history-date {
            font-size: 0.85rem;
            color: #6c757d;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="wrapper">
            <!-- Sidebar -->
            <?php
            require_once 'app/views/partials/sidebar.php';
            ?>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php?controller=admin&action=dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="index.php?controller=admin&action=orders">Đơn hàng</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Chi tiết đơn hàng #<?= $order['id'] ?></li>
                    </ol>
                </nav>

                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Chi tiết đơn hàng #<?= $order['id'] ?></h1>
                    <div>
                        <?php
                        $statusText = '';
                        $statusClass = '';
                        switch ($order['status']) {
                            case 'pending':
                            case 'pendings':
                                $statusText = 'Chờ xử lý';
                                $statusClass = 'pending';
                                break;
                            case 'processing':
                                $statusText = 'Đang xử lý';
                                $statusClass = 'processing';
                                break;
                            case 'ready_to_ship':
                                $statusText = 'Sẵn sàng giao';
                                $statusClass = 'ready_to_ship';
                                break;
                            case 'shipping':
                                $statusText = 'Đang giao hàng';
                                $statusClass = 'shipping';
                                break;
                            case 'completed':
                                $statusText = 'Hoàn thành';
                                $statusClass = 'completed';
                                break;
                            case 'cancelled':
                                $statusText = 'Đã hủy';
                                $statusClass = 'cancelled';
                                break;
                            default:
                                $statusText = ucfirst($order['status']);
                                $statusClass = $order['status'];
                        }
                        ?>
                        <span class="order-status status-<?= $statusClass ?>"><?= $statusText ?></span>
                    </div>
                </div>

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

                <div class="row">
                    <div class="col-md-8">
                        <!-- Thông tin đơn hàng -->
                        <div class="card shadow-sm order-detail-card">
                            <div class="card-body">
                                <h3 class="order-detail-title">
                                    <i class="fas fa-info-circle"></i> Thông tin đơn hàng
                                </h3>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="order-info-item">
                                            <div class="order-info-label">Mã đơn hàng</div>
                                            <div class="order-info-value">#<?= $order['id'] ?></div>
                                        </div>
                                        <div class="order-info-item">
                                            <div class="order-info-label">Ngày tạo</div>
                                            <div class="order-info-value"><?= date('d/m/Y H:i:s', strtotime($order['created_at'])) ?></div>
                                        </div>
                                        <div class="order-info-item">
                                            <div class="order-info-label">Phương thức thanh toán</div>
                                            <div class="order-info-value">
                                                <?php if ($order['status'] === 'pendings' || $order['status'] === 'completed'): ?>
                                                    <span class="badge bg-primary">VNPAY</span>
                                                <?php else: ?>
                                                    <span class="badge bg-success">COD</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="order-info-item">
                                            <div class="order-info-label">Trạng thái</div>
                                            <div class="order-info-value">
                                                <span class="badge bg-<?= $statusClass === 'pending' ? 'warning text-dark' : ($statusClass === 'cancelled' ? 'danger' : ($statusClass === 'completed' ? 'success' : 'primary')) ?>">
                                                    <?= $statusText ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="order-info-item">
                                            <div class="order-info-label">Cập nhật lần cuối</div>
                                            <div class="order-info-value"><?= date('d/m/Y H:i:s', strtotime($order['updated_at'] ?? $order['created_at'])) ?></div>
                                        </div>
                                        <div class="order-info-item">
                                            <div class="order-info-label">Tổng tiền</div>
                                            <div class="order-info-value text-danger fw-bold"><?= number_format($order['total_price'], 0, ',', '.') ?> đ</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Thông tin khách hàng -->
                        <div class="card shadow-sm order-detail-card">
                            <div class="card-body">
                                <h3 class="order-detail-title">
                                    <i class="fas fa-user"></i> Thông tin khách hàng
                                </h3>
                                <div class="order-info-item">
                                    <div class="order-info-label">Tên khách hàng</div>
                                    <div class="order-info-value"><?= htmlspecialchars($order['user_name'] ?? 'N/A') ?></div>
                                </div>
                                <div class="order-info-item">
                                    <div class="order-info-label">Email</div>
                                    <div class="order-info-value"><?= htmlspecialchars($order['user_email'] ?? 'N/A') ?></div>
                                </div>
                            </div>
                        </div>

                        <!-- Danh sách sản phẩm -->
                        <div class="card shadow-sm order-detail-card">
                            <div class="card-body">
                                <h3 class="order-detail-title">
                                    <i class="fas fa-box"></i> Sản phẩm đã đặt
                                </h3>
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th style="width: 50%;">Sản phẩm</th>
                                                <th class="text-center">Giá</th>
                                                <th class="text-center">Số lượng</th>
                                                <th class="text-end">Thành tiền</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $total = 0; ?>
                                            <?php foreach ($orderItems as $item): ?>
                                                <?php $itemTotal = $item['price'] * $item['quantity'];
                                                $total += $itemTotal; ?>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <?php if (!empty($item['image_path'])): ?>
                                                                <img src="public/images/<?= htmlspecialchars($item['image_path']) ?>" class="product-image me-3" alt="<?= htmlspecialchars($item['product_name']) ?>" onerror="this.src='public/images/default.jpg'">
                                                            <?php else: ?>
                                                                <img src="public/images/default.jpg" class="product-image me-3" alt="<?= htmlspecialchars($item['product_name']) ?>">
                                                            <?php endif; ?>
                                                            <div>
                                                                <p class="product-name mb-0"><?= htmlspecialchars($item['product_name']) ?></p>
                                                                <small class="text-muted">ID: <?= $item['product_id'] ?></small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-center"><?= number_format($item['price'], 0, ',', '.') ?> đ</td>
                                                    <td class="text-center"><?= $item['quantity'] ?></td>
                                                    <td class="text-end fw-bold"><?= number_format($itemTotal, 0, ',', '.') ?> đ</td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="3" class="text-end fw-bold">Tổng cộng:</td>
                                                <td class="text-end fw-bold text-danger"><?= number_format($total, 0, ',', '.') ?> đ</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <!-- Cập nhật trạng thái -->
                        <div class="card shadow-sm order-detail-card">
                            <div class="card-body">
                                <h3 class="order-detail-title">
                                    <i class="fas fa-edit"></i> Cập nhật trạng thái
                                </h3>
                                <form action="index.php?controller=admin&action=updateOrderStatus" method="post">
                                    <input type="hidden" name="order_id" value="<?= $order['id'] ?>">

                                    <div class="mb-3">
                                        <label for="status" class="form-label">Trạng thái đơn hàng</label>
                                        <select class="form-select" name="status" id="status">
                                            <option value="pending" <?= $order['status'] === 'pending' ? 'selected' : '' ?>>Chờ xử lý</option>
                                            <option value="processing" <?= $order['status'] === 'processing' ? 'selected' : '' ?>>Đang xử lý</option>
                                            <option value="ready_to_ship" <?= $order['status'] === 'ready_to_ship' ? 'selected' : '' ?>>Sẵn sàng giao</option>
                                            <option value="shipping" <?= $order['status'] === 'shipping' ? 'selected' : '' ?>>Đang giao hàng</option>
                                            <option value="completed" <?= $order['status'] === 'completed' ? 'selected' : '' ?>>Hoàn thành</option>
                                            <option value="cancelled" <?= $order['status'] === 'cancelled' ? 'selected' : '' ?>>Đã hủy</option>
                                        </select>
                                    </div>

                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-save me-2"></i>Cập nhật trạng thái
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Ghi chú và chỉ dẫn trạng thái -->
                        <div class="card shadow-sm order-detail-card">
                            <div class="card-body">
                                <h3 class="order-detail-title">
                                    <i class="fas fa-info-circle"></i> Hướng dẫn trạng thái
                                </h3>
                                <div class="status-guide mb-3">
                                    <div class="d-flex align-items-center mb-2">
                                        <span class="order-status status-pending me-2" style="width: 100px;">Chờ xử lý</span>
                                        <span>Đơn hàng mới được tạo.</span>
                                    </div>
                                    <div class="d-flex align-items-center mb-2">
                                        <span class="order-status status-processing me-2" style="width: 100px;">Đang xử lý</span>
                                        <span>Đang chuẩn bị sản phẩm.</span>
                                    </div>
                                    <div class="d-flex align-items-center mb-2">
                                        <span class="order-status status-ready_to_ship me-2" style="width: 100px;">Sẵn sàng giao</span>
                                        <span>Sẵn sàng giao cho đơn vị vận chuyển.</span>
                                    </div>
                                    <div class="d-flex align-items-center mb-2">
                                        <span class="order-status status-shipping me-2" style="width: 100px;">Đang giao</span>
                                        <span>Sản phẩm đang được giao đến.</span>
                                    </div>
                                    <div class="d-flex align-items-center mb-2">
                                        <span class="order-status status-completed me-2" style="width: 100px;">Hoàn thành</span>
                                        <span>Đơn hàng đã giao thành công.</span>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="order-status status-cancelled me-2" style="width: 100px;">Đã hủy</span>
                                        <span>Đơn hàng đã bị hủy.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>