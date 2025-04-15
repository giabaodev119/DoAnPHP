<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Admin - Chương trình khuyến mãi</title>
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
        .promotion-badge {
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-active {
            background-color: #e8f5e9;
            color: #2e7d32;
        }

        .badge-inactive {
            background-color: #f5f5f5;
            color: #757575;
        }

        .badge-expired {
            background-color: #ffebee;
            color: #c62828;
        }

        .badge-pending {
            background-color: #fff8e1;
            color: #f57f17;
        }

        .promotion-table th,
        .promotion-table td {
            vertical-align: middle;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <?php
        require_once 'app/views/partials/sidebar.php';
        ?>
        <div class="main-panel">
            <div class="content">
                <div class="page-inner">
                    <div class="page-header">
                        <h4 class="page-title">Quản lý chương trình khuyến mãi</h4>
                    </div>

                    <div class="page-category">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <span>Danh sách tất cả chương trình khuyến mãi</span>
                            <a href="index.php?controller=promotion&action=create" class="btn btn-primary">
                                <i class="fas fa-plus-circle me-1"></i> Thêm khuyến mãi mới
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
                                    <table class="table table-hover promotion-table">
                                        <thead>
                                            <tr>
                                                <th><i class="fas fa-hashtag me-1"></i> ID</th>
                                                <th><i class="fas fa-tag me-1"></i> Tên khuyến mãi</th>
                                                <th><i class="fas fa-percent me-1"></i> Giảm giá</th>
                                                <th><i class="fas fa-bullseye me-1"></i> Áp dụng cho</th>
                                                <th><i class="fas fa-calendar-alt me-1"></i> Thời gian</th>
                                                <th><i class="fas fa-info-circle me-1"></i> Trạng thái</th>
                                                <th><i class="fas fa-cogs me-1"></i> Thao tác</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($promotions)): ?>
                                                <?php foreach ($promotions as $promotion): ?>
                                                    <?php
                                                    // Xác định trạng thái khuyến mãi
                                                    $now = new DateTime();
                                                    $startDate = !empty($promotion['start_date']) ? new DateTime($promotion['start_date']) : null;
                                                    $endDate = !empty($promotion['end_date']) ? new DateTime($promotion['end_date']) : null;

                                                    if ($promotion['status'] === 'inactive') {
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
                                                    if ($promotion['discount_type'] === 'percentage') {
                                                        $discountText = $promotion['discount_value'] . '%';
                                                    } else {
                                                        $discountText = number_format($promotion['discount_value'], 0, ',', '.') . 'đ';
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

                                                    // Loại áp dụng
                                                    $targetText = $promotion['target_type'] === 'product' ? 'Sản phẩm' : 'Danh mục';
                                                    ?>
                                                    <tr>
                                                        <td><?= $promotion['id'] ?></td>
                                                        <td>
                                                            <div class="fw-bold"><?= htmlspecialchars($promotion['name']) ?></div>
                                                            <?php if (!empty($promotion['description'])): ?>
                                                                <small class="text-muted"><?= htmlspecialchars($promotion['description']) ?></small>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td><?= $discountText ?></td>
                                                        <td><?= $targetText ?></td>
                                                        <td><?= $timeText ?></td>
                                                        <td><span class="promotion-badge <?= $statusClass ?>"><?= $statusText ?></span></td>
                                                        <td>
                                                            <div class="d-flex">
                                                                <a href="index.php?controller=promotion&action=edit&id=<?= $promotion['id'] ?>"
                                                                    class="btn btn-info btn-sm action-btn">
                                                                    <i class="fas fa-edit me-1"></i> Sửa
                                                                </a>
                                                                <a href="index.php?controller=promotion&action=delete&id=<?= $promotion['id'] ?>"
                                                                    class="btn btn-danger btn-sm action-btn ms-1"
                                                                    onclick="return confirm('Bạn có chắc chắn muốn xóa chương trình khuyến mãi này?')">
                                                                    <i class="fas fa-trash me-1"></i> Xóa
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="7" class="text-center py-4">
                                                        <i class="fas fa-percentage fa-2x text-muted mb-2"></i>
                                                        <p class="mb-0">Không có chương trình khuyến mãi nào</p>
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Phân trang -->
                                <?php if ($totalPages > 1): ?>
                                    <div class="d-flex justify-content-center mt-4">
                                        <nav aria-label="Điều hướng trang khuyến mãi">
                                            <ul class="pagination">
                                                <!-- Nút Previous -->
                                                <?php if ($page > 1): ?>
                                                    <li class="page-item">
                                                        <a class="page-link" href="index.php?controller=promotion&action=index&page=<?= $page - 1 ?>" aria-label="Trang trước">
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
                                                    echo '<li class="page-item"><a class="page-link" href="index.php?controller=promotion&action=index&page=1">1</a></li>';
                                                    if ($startPage > 2) {
                                                        echo '<li class="page-item disabled"><a class="page-link" href="#">...</a></li>';
                                                    }
                                                }

                                                // Hiển thị các trang trong phạm vi
                                                for ($i = $startPage; $i <= $endPage; $i++) {
                                                    echo '<li class="page-item ' . ($i == $page ? 'active' : '') . '">';
                                                    echo '<a class="page-link" href="index.php?controller=promotion&action=index&page=' . $i . '">' . $i . '</a>';
                                                    echo '</li>';
                                                }

                                                // Hiển thị trang cuối nếu không nằm trong phạm vi
                                                if ($endPage < $totalPages) {
                                                    if ($endPage < $totalPages - 1) {
                                                        echo '<li class="page-item disabled"><a class="page-link" href="#">...</a></li>';
                                                    }
                                                    echo '<li class="page-item"><a class="page-link" href="index.php?controller=promotion&action=index&page=' . $totalPages . '">' . $totalPages . '</a></li>';
                                                }
                                                ?>

                                                <!-- Nút Next -->
                                                <?php if ($page < $totalPages): ?>
                                                    <li class="page-item">
                                                        <a class="page-link" href="index.php?controller=promotion&action=index&page=<?= $page + 1 ?>" aria-label="Trang sau">
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

                                    <div class="text-center text-muted mt-2">
                                        Hiển thị <?= count($promotions) ?> khuyến mãi trên tổng số <?= $totalPromotions ?> khuyến mãi
                                        (Trang <?= $page ?>/<?= $totalPages ?>)
                                    </div>
                                <?php endif; ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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