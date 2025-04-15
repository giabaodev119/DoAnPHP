<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Admin - Thêm chương trình khuyến mãi</title>
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
        .form-card {
            max-width: 900px;
            margin: 0 auto;
        }

        .product-list,
        .category-list {
            max-height: 300px;
            overflow-y: auto;
            border: 1px solid #eee;
            padding: 15px;
            border-radius: 5px;
        }

        .form-check-input:checked+.form-check-label {
            font-weight: 600;
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
                        <h4 class="page-title">Thêm chương trình khuyến mãi mới</h4>
                    </div>

                    <div class="page-category">
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?= $error ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <div class="card shadow-sm form-card">
                            <div class="card-body">
                                <form method="post">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="name" class="form-label"><i class="fas fa-tag me-1"></i> Tên chương trình <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="name" name="name" required
                                                    placeholder="VD: Giảm giá mùa hè" maxlength="255">
                                            </div>

                                            <div class="mb-3">
                                                <label for="discount_type" class="form-label"><i class="fas fa-percent me-1"></i> Loại giảm giá <span class="text-danger">*</span></label>
                                                <select class="form-select" id="discount_type" name="discount_type" required>
                                                    <option value="percentage">Phần trăm (%)</option>
                                                    <option value="fixed">Số tiền cố định (VNĐ)</option>
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label for="discount_value" class="form-label"><i class="fas fa-tag me-1"></i> Giá trị giảm giá <span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" id="discount_value" name="discount_value" min="1" max="100" required>
                                                <small class="form-text text-muted" id="discount_value_help">Giá trị phần trăm giảm giá (1-100)</small>
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
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="description" class="form-label"><i class="fas fa-info-circle me-1"></i> Mô tả</label>
                                                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                                            </div>

                                            <div class="mb-3">
                                                <label for="status" class="form-label"><i class="fas fa-toggle-on me-1"></i> Trạng thái <span class="text-danger">*</span></label>
                                                <select class="form-select" id="status" name="status" required>
                                                    <option value="active">Kích hoạt</option>
                                                    <option value="inactive">Không kích hoạt</option>
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label for="target_type" class="form-label"><i class="fas fa-bullseye me-1"></i> Áp dụng cho <span class="text-danger">*</span></label>
                                                <select class="form-select" id="target_type" name="target_type" required onchange="toggleTargetSelection()">
                                                    <option value="product">Sản phẩm cụ thể</option>
                                                    <option value="category">Danh mục sản phẩm</option>
                                                </select>
                                            </div>

                                            <div id="product_selection" class="mb-3">
                                                <label class="form-label"><i class="fas fa-boxes me-1"></i> Chọn sản phẩm <span class="text-danger">*</span></label>
                                                <div class="product-list">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="select_all_products" onchange="toggleAllItems('products')">
                                                        <label class="form-check-label fw-bold" for="select_all_products">
                                                            Chọn tất cả sản phẩm
                                                        </label>
                                                    </div>
                                                    <hr class="my-2">

                                                    <?php foreach ($products as $product): ?>
                                                        <div class="form-check">
                                                            <input class="form-check-input product-checkbox" type="checkbox" name="product_ids[]"
                                                                value="<?= $product['id'] ?>" id="product_<?= $product['id'] ?>">
                                                            <label class="form-check-label" for="product_<?= $product['id'] ?>">
                                                                <?= htmlspecialchars($product['name']) ?> - <?= number_format($product['price'], 0, ',', '.') ?> đ
                                                            </label>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>

                                            <div id="category_selection" class="mb-3" style="display: none;">
                                                <label class="form-label"><i class="fas fa-tags me-1"></i> Chọn danh mục <span class="text-danger">*</span></label>
                                                <div class="category-list">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="select_all_categories" onchange="toggleAllItems('categories')">
                                                        <label class="form-check-label fw-bold" for="select_all_categories">
                                                            Chọn tất cả danh mục
                                                        </label>
                                                    </div>
                                                    <hr class="my-2">

                                                    <?php foreach ($categories as $category): ?>
                                                        <div class="form-check">
                                                            <input class="form-check-input category-checkbox" type="checkbox" name="category_ids[]"
                                                                value="<?= $category['id'] ?>" id="category_<?= $category['id'] ?>">
                                                            <label class="form-check-label" for="category_<?= $category['id'] ?>">
                                                                <?= htmlspecialchars($category['name']) ?>
                                                            </label>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between mt-4">
                                        <a href="index.php?controller=promotion&action=index" class="btn btn-outline-secondary">
                                            <i class="fas fa-arrow-left me-1"></i> Quay lại
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-1"></i> Lưu chương trình
                                        </button>
                                    </div>
                                </form>
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

    <script>
        function toggleTargetSelection() {
            const targetType = document.getElementById('target_type').value;

            if (targetType === 'product') {
                document.getElementById('product_selection').style.display = 'block';
                document.getElementById('category_selection').style.display = 'none';
            } else {
                document.getElementById('product_selection').style.display = 'none';
                document.getElementById('category_selection').style.display = 'block';
            }
        }

        function toggleAllItems(type) {
            if (type === 'products') {
                const selectAll = document.getElementById('select_all_products');
                const checkboxes = document.getElementsByClassName('product-checkbox');

                Array.from(checkboxes).forEach(checkbox => {
                    checkbox.checked = selectAll.checked;
                });
            } else {
                const selectAll = document.getElementById('select_all_categories');
                const checkboxes = document.getElementsByClassName('category-checkbox');

                Array.from(checkboxes).forEach(checkbox => {
                    checkbox.checked = selectAll.checked;
                });
            }
        }

        document.getElementById('discount_type').addEventListener('change', function() {
            const discountType = this.value;
            const discountValue = document.getElementById('discount_value');
            const discountHelp = document.getElementById('discount_value_help');

            if (discountType === 'percentage') {
                discountValue.max = 100;
                discountHelp.textContent = 'Giá trị phần trăm giảm giá (1-100)';
            } else {
                discountValue.removeAttribute('max');
                discountHelp.textContent = 'Số tiền cố định giảm giá (VNĐ)';
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            toggleTargetSelection();
        });
    </script>
</body>

</html>