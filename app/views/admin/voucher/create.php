<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Admin Dashboard - Thêm voucher mới</title>
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
        .form-container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .form-header {
            margin-bottom: 2rem;
            border-bottom: 1px solid #eee;
            padding-bottom: 1rem;
        }

        .category-list {
            max-height: 200px;
            overflow-y: auto;
            border: 1px solid #ced4da;
            border-radius: 5px;
            padding: 10px;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <?php
        require_once 'app/views/partials/sidebar.php';
        ?>
        <!-- Main Content -->
        <div class="main-panel">
            <div class="content">
                <div class="container-fluid">
                    <div class="form-container">
                        <div class="form-header">
                            <h2><i class="fas fa-ticket-alt me-2"></i>Thêm voucher mới</h2>
                            <p class="text-muted">Tạo mã giảm giá mới cho khách hàng</p>
                        </div>

                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?= $_SESSION['error'] ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            <?php unset($_SESSION['error']); ?>
                        <?php endif; ?>

                        <form method="post" action="index.php?controller=admin&action=createVoucher">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="code" class="form-label"><i class="fas fa-barcode me-1"></i> Mã voucher <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="code" name="code" required
                                            placeholder="VD: SUMMER2023" maxlength="20">
                                        <small class="form-text text-muted">Mã voucher nên viết liền, không dấu, không có ký tự đặc biệt</small>
                                    </div>

                                    <div class="mb-3">
                                        <label for="discount_type" class="form-label"><i class="fas fa-percent me-1"></i> Loại giảm giá <span class="text-danger">*</span></label>
                                        <select class="form-select" id="discount_type" name="discount_type" required onchange="toggleMaxDiscount()">
                                            <option value="percentage">Phần trăm (%)</option>
                                            <option value="fixed">Số tiền cố định (VNĐ)</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="discount_value" class="form-label"><i class="fas fa-tag me-1"></i> Giá trị giảm giá <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="discount_value" name="discount_value" min="1" required>
                                        <small class="form-text text-muted" id="discount_value_help">Giá trị phần trăm giảm giá (1-100)</small>
                                    </div>

                                    <div class="mb-3" id="max_discount_container">
                                        <label for="max_discount" class="form-label"><i class="fas fa-money-bill me-1"></i> Giảm giá tối đa (VNĐ)</label>
                                        <input type="number" class="form-control" id="max_discount" name="max_discount" min="0">
                                        <small class="form-text text-muted">Giới hạn số tiền tối đa có thể giảm (áp dụng cho giảm giá theo %)</small>
                                    </div>

                                    <div class="mb-3">
                                        <label for="min_purchase" class="form-label"><i class="fas fa-shopping-cart me-1"></i> Giá trị đơn hàng tối thiểu (VNĐ)</label>
                                        <input type="number" class="form-control" id="min_purchase" name="min_purchase" min="0" value="0">
                                        <small class="form-text text-muted">Giá trị đơn hàng tối thiểu để áp dụng voucher (0 = không giới hạn)</small>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="quantity" class="form-label"><i class="fas fa-box me-1"></i> Số lượng <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="quantity" name="quantity" min="1" required>
                                        <small class="form-text text-muted">Tổng số voucher có thể sử dụng</small>
                                    </div>

                                    <div class="mb-3">
                                        <label for="start_date" class="form-label"><i class="fas fa-calendar-alt me-1"></i> Ngày bắt đầu</label>
                                        <input type="datetime-local" class="form-control" id="start_date" name="start_date">
                                        <small class="form-text text-muted">Ngày bắt đầu có hiệu lực (để trống = hiệu lực ngay)</small>
                                    </div>

                                    <div class="mb-3">
                                        <label for="end_date" class="form-label"><i class="fas fa-calendar-alt me-1"></i> Ngày kết thúc</label>
                                        <input type="datetime-local" class="form-control" id="end_date" name="end_date">
                                        <small class="form-text text-muted">Ngày hết hạn (để trống = không giới hạn)</small>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label"><i class="fas fa-list me-1"></i> Áp dụng cho danh mục</label>
                                        <div class="category-list">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="select_all" onchange="toggleAllCategories()">
                                                <label class="form-check-label fw-bold" for="select_all">
                                                    Chọn tất cả danh mục
                                                </label>
                                            </div>
                                            <hr class="my-2">

                                            <?php foreach ($categories as $category): ?>
                                                <div class="form-check">
                                                    <input class="form-check-input category-checkbox" type="checkbox" name="categories[]"
                                                        value="<?= $category['id'] ?>" id="category_<?= $category['id'] ?>">
                                                    <label class="form-check-label" for="category_<?= $category['id'] ?>">
                                                        <?= htmlspecialchars($category['name']) ?>
                                                    </label>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                        <small class="form-text text-muted">Để trống = áp dụng cho tất cả danh mục</small>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="description" class="form-label"><i class="fas fa-info-circle me-1"></i> Mô tả</label>
                                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                            </div>

                            <div class="d-flex justify-content-between mt-5">
                                <a href="index.php?controller=admin&action=vouchers" class="btn btn-outline-secondary btn-lg">
                                    <i class="fas fa-arrow-left me-1"></i> Quay lại
                                </a>
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-save me-1"></i> Lưu voucher
                                </button>
                            </div>
                        </form>
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

    <script>
        function toggleMaxDiscount() {
            const discountType = document.getElementById('discount_type').value;
            const maxDiscountContainer = document.getElementById('max_discount_container');
            const discountValueHelp = document.getElementById('discount_value_help');

            if (discountType === 'percentage') {
                maxDiscountContainer.style.display = 'block';
                discountValueHelp.textContent = 'Giá trị phần trăm giảm giá (1-100)';
                document.getElementById('discount_value').max = 100;
            } else {
                maxDiscountContainer.style.display = 'none';
                discountValueHelp.textContent = 'Số tiền cố định giảm giá (VNĐ)';
                document.getElementById('discount_value').removeAttribute('max');
            }
        }

        function toggleAllCategories() {
            const selectAll = document.getElementById('select_all');
            const categoryCheckboxes = document.getElementsByClassName('category-checkbox');

            Array.from(categoryCheckboxes).forEach(checkbox => {
                checkbox.checked = selectAll.checked;
            });
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            toggleMaxDiscount();
        });
    </script>
</body>

</html>