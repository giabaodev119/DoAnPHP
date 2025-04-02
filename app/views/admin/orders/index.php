<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý đơn hàng - Admin</title>
    <link rel="stylesheet" href="public/css/bootstrap.min.css" />
    <link rel="stylesheet" href="public/css/plugins.min.css" />
    <link rel="stylesheet" href="public/css/kaiadmin.min.css" />
    <link rel="stylesheet" href="public/css/demo.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        .order-status {
            display: inline-block;
            padding: 0.35em 0.65em;
            font-size: 0.75em;
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

        .orders-table th,
        .orders-table td {
            vertical-align: middle;
        }

        .order-id {
            font-weight: 600;
            color: #0d6efd;
        }

        .search-orders {
            max-width: 400px;
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
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Quản lý đơn hàng</h1>
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

                <div class="card shadow-sm mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Danh sách đơn hàng</h5>
                        <div class="search-orders">
                            <input type="text" class="form-control" id="searchOrders" placeholder="Tìm kiếm đơn hàng..." oninput="filterOrders()">
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover orders-table">
                                <thead>
                                    <tr>
                                        <th>Mã đơn</th>
                                        <th>Khách hàng</th>
                                        <th>Tổng tiền</th>
                                        <th>Số SP</th>
                                        <th>Trạng thái</th>
                                        <th>Ngày đặt</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($orders)): ?>
                                        <tr>
                                            <td colspan="7" class="text-center py-4">Không có đơn hàng nào</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($orders as $order): ?>
                                            <tr>
                                                <td class="order-id">#<?= $order['id'] ?></td>
                                                <td>
                                                    <div><?= htmlspecialchars($order['user_name']) ?></div>
                                                    <small class="text-muted"><?= htmlspecialchars($order['user_email']) ?></small>
                                                </td>
                                                <td class="fw-bold text-danger">
                                                    <?= number_format($order['total_price'], 0, ',', '.') ?> đ
                                                </td>
                                                <td><?= $order['total_items'] ?></td>
                                                <td>
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
                                                </td>
                                                <td><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
                                                <td>
                                                    <a href="index.php?controller=admin&action=orderDetail&id=<?= $order['id'] ?>" class="btn btn-sm btn-primary">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function filterOrders() {
            const searchInput = document.getElementById('searchOrders').value.toLowerCase();
            const rows = document.querySelectorAll('.orders-table tbody tr');

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(searchInput)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
    </script>
</body>

</html>