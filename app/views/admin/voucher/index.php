<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Admin Dashboard - Quản lý voucher</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no" />
    <link rel="icon" href="public/img/kaiadmin/favicon.ico" type="image/x-icon" />

    <!-- Fonts and icons -->
    <script src="public/js/plugin/webfont/webfont.min.js"></script>
    <script>
        WebFont.load({
            google: {
                families: ["Public Sans:300,400,500,600,700"]
            },
            custom: {
                families: [
                    "Font Awesome 5 Solid",
                    "Font Awesome 5 Regular",
                    "Font Awesome 5 Brands",
                    "simple-line-icons",
                ],
                urls: ["public/css/fonts.min.css"],
            },
            active: function() {
                sessionStorage.fonts = true;
            },
        });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="public/css/bootstrap.min.css" />
    <link rel="stylesheet" href="public/css/plugins.min.css" />
    <link rel="stylesheet" href="public/css/kaiadmin.min.css" />
    <link rel="stylesheet" href="public/css/demo.css" />
    <style>
        .voucher-badge {
            padding: 0.35em 0.65em;
            border-radius: 0.25rem;
            font-size: 0.75em;
            font-weight: 600;
        }

        .badge-active {
            background-color: #198754;
            color: #fff;
        }

        .badge-inactive {
            background-color: #dc3545;
            color: #fff;
        }

        .badge-expired {
            background-color: #6c757d;
            color: #fff;
        }

        .badge-pending {
            background-color: #ffc107;
            color: #212529;
        }

        .voucher-code {
            font-family: monospace;
            font-weight: 600;
            font-size: 1.1em;
            letter-spacing: 0.5px;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <?php require_once 'app/views/partials/sidebar.php'; ?>
        <!-- Main Content -->
        <div class="main-panel">
            <div class="content">
                <div class="container-fluid">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2><i class="fas fa-ticket-alt me-2"></i>Quản lý voucher</h2>
                        <a href="index.php?controller=admin&action=createVoucher" class="btn btn-success">
                            <i class="fas fa-plus me-1"></i> Thêm voucher
                        </a>
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

                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th><i class="fas fa-hashtag me-1"></i> ID</th>
                                            <th><i class="fas fa-barcode me-1"></i> Mã voucher</th>
                                            <th><i class="fas fa-tag me-1"></i> Giảm giá</th>
                                            <th><i class="fas fa-box me-1"></i> Còn lại</th>
                                            <th><i class="fas fa-calendar-alt me-1"></i> Thời gian</th>
                                            <th><i class="fas fa-info-circle me-1"></i> Trạng thái</th>
                                            <th><i class="fas fa-cogs me-1"></i> Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($vouchers)): ?>
                                            <?php foreach ($vouchers as $voucher): ?>
                                                <?php
                                                // Xác định trạng thái voucher
                                                $now = new DateTime();
                                                $startDate = !empty($voucher['start_date']) ? new DateTime($voucher['start_date']) : null;
                                                $endDate = !empty($voucher['end_date']) ? new DateTime($voucher['end_date']) : null;

                                                if ($voucher['status'] === 'inactive') {
                                                    $statusClass = 'badge-inactive';
                                                    $statusText = 'Không hoạt động';
                                                } elseif ($endDate && $now > $endDate) {
                                                    $statusClass = 'badge-expired';
                                                    $statusText = 'Hết hạn';
                                                } elseif ($startDate && $now < $startDate) {
                                                    $statusClass = 'badge-pending';
                                                    $statusText = 'Chưa bắt đầu';
                                                } else {
                                                    $statusClass = 'badge-active';
                                                    $statusText = 'Đang hoạt động';
                                                }

                                                // Định dạng giảm giá
                                                if ($voucher['discount_type'] === 'percentage') {
                                                    $discountText = $voucher['discount_value'] . '%';
                                                    if (!empty($voucher['max_discount'])) {
                                                        $discountText .= ' (tối đa ' . number_format($voucher['max_discount'], 0, ',', '.') . 'đ)';
                                                    }
                                                } else {
                                                    $discountText = number_format($voucher['discount_value'], 0, ',', '.') . 'đ';
                                                }

                                                // Định dạng thời gian
                                                $timeText = '';
                                                if ($startDate) {
                                                    $timeText .= 'Từ: ' . $startDate->format('d/m/Y');
                                                }
                                                if ($endDate) {
                                                    $timeText .= ($startDate ? '<br>' : '') . 'Đến: ' . $endDate->format('d/m/Y');
                                                }
                                                if (empty($timeText)) {
                                                    $timeText = 'Không giới hạn';
                                                }

                                                // Số lượng còn lại
                                                $remaining = $voucher['quantity'] - $voucher['used_count'];
                                                ?>
                                                <tr>
                                                    <td><?= $voucher['id'] ?></td>
                                                    <td><span class="voucher-code"><?= $voucher['code'] ?></span></td>
                                                    <td><?= $discountText ?></td>
                                                    <td><?= $remaining ?>/<?= $voucher['quantity'] ?></td>
                                                    <td><?= $timeText ?></td>
                                                    <td><span class="voucher-badge <?= $statusClass ?>"><?= $statusText ?></span></td>
                                                    <td>
                                                        <div class="d-flex">
                                                            <a href="index.php?controller=admin&action=editVoucher&id=<?= $voucher['id'] ?>"
                                                                class="btn btn-info btn-sm action-btn">
                                                                <i class="fas fa-edit me-1"></i> Sửa
                                                            </a>
                                                            <a href="index.php?controller=admin&action=deleteVoucher&id=<?= $voucher['id'] ?>"
                                                                class="btn btn-danger btn-sm action-btn ms-1"
                                                                onclick="return confirm('Bạn có chắc chắn muốn xóa voucher này?')">
                                                                <i class="fas fa-trash me-1"></i> Xóa
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="7" class="text-center py-4">
                                                    <i class="fas fa-ticket-alt fa-2x text-muted mb-2"></i>
                                                    <p class="mb-0">Không có voucher nào</p>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Phân trang -->
                            <?php if ($totalPages > 1): ?>
                                <div class="d-flex justify-content-center mt-4">
                                    <nav aria-label="Điều hướng trang voucher">
                                        <ul class="pagination">
                                            <!-- Nút Previous -->
                                            <?php if ($page > 1): ?>
                                                <li class="page-item">
                                                    <a class="page-link" href="index.php?controller=admin&action=vouchers&page=<?= $page - 1 ?>" aria-label="Trang trước">
                                                        <span aria-hidden="true">Trước</span>
                                                    </a>
                                                </li>
                                            <?php else: ?>
                                                <li class="page-item disabled">
                                                    <a class="page-link" href="#" aria-label="Trang trước">
                                                        <span aria-hidden="true">Trước</span>
                                                    </a>
                                                </li>
                                            <?php endif; ?>

                                            <!-- Các nút số trang -->
                                            <?php
                                            $startPage = max(1, $page - 2);
                                            $endPage = min($totalPages, $page + 2);

                                            // Hiển thị trang đầu nếu không nằm trong phạm vi
                                            if ($startPage > 1) {
                                                echo '<li class="page-item"><a class="page-link" href="index.php?controller=admin&action=vouchers&page=1">1</a></li>';
                                                if ($startPage > 2) {
                                                    echo '<li class="page-item disabled"><a class="page-link" href="#">...</a></li>';
                                                }
                                            }

                                            // Hiển thị các trang trong phạm vi
                                            for ($i = $startPage; $i <= $endPage; $i++) {
                                                echo '<li class="page-item ' . ($i == $page ? 'active' : '') . '">';
                                                echo '<a class="page-link" href="index.php?controller=admin&action=vouchers&page=' . $i . '">' . $i . '</a>';
                                                echo '</li>';
                                            }

                                            // Hiển thị trang cuối nếu không nằm trong phạm vi
                                            if ($endPage < $totalPages) {
                                                if ($endPage < $totalPages - 1) {
                                                    echo '<li class="page-item disabled"><a class="page-link" href="#">...</a></li>';
                                                }
                                                echo '<li class="page-item"><a class="page-link" href="index.php?controller=admin&action=vouchers&page=' . $totalPages . '">' . $totalPages . '</a></li>';
                                            }
                                            ?>

                                            <!-- Nút Next -->
                                            <?php if ($page < $totalPages): ?>
                                                <li class="page-item">
                                                    <a class="page-link" href="index.php?controller=admin&action=vouchers&page=<?= $page + 1 ?>" aria-label="Trang sau">
                                                        <span aria-hidden="true">Sau</span>
                                                    </a>
                                                </li>
                                            <?php else: ?>
                                                <li class="page-item disabled">
                                                    <a class="page-link" href="#" aria-label="Trang sau">
                                                        <span aria-hidden="true">Sau</span>
                                                    </a>
                                                </li>
                                            <?php endif; ?>
                                        </ul>
                                    </nav>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Main Content -->
    </div>

    <!-- Core JS Files -->
    <script src="public/js/jquery.min.js"></script>
    <script src="public/js/popper.min.js"></script>
    <script src="public/js/bootstrap.min.js"></script>

    <!-- KaiAdmin JS -->
    <script src="public/js/jquery.scrollbar.min.js"></script>
    <script src="public/js/jquery-ui.min.js"></script>
    <script src="public/js/kaiadmin.min.js"></script>
</body>

</html>