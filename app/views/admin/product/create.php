<!-- filepath: c:\xampp\htdocs\DoAnPHP\app\views\products\create.php -->
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Admin Dashboard - Thêm sản phẩm mới</title>
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
        .product-form-card {
            max-width: 800px;
            margin: 0 auto;
        }

        .preview-image {
            max-width: 150px;
            max-height: 150px;
            margin-right: 10px;
            margin-bottom: 10px;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 5px;
        }

        .image-preview-container {
            display: flex;
            flex-wrap: wrap;
            margin-top: 10px;
        }

        /* Thêm vào phần style hiện có */
        .price-original {
            text-decoration: line-through;
            color: #999;
            font-size: 0.9em;
        }

        .price-discounted {
            color: #dc3545;
            font-weight: bold;
        }

        .discount-badge {
            background-color: #dc3545;
            color: white;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 0.8em;
            margin-left: 8px;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <?php require_once 'app/views/partials/sidebar.php'; ?>
        <!-- End Sidebar -->

        <!-- Main Content -->
        <div class="main-panel">
            <div class="content">
                <div class="container-fluid">
                    <div class="page-header">
                        <h2 class="page-title">Thêm sản phẩm mới</h2>
                        <div class="page-actions">
                            <a href="index.php?controller=admin&action=products" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Quay lại
                            </a>
                        </div>
                    </div>

                    <div class="card product-form-card">
                        <div class="card-header">
                            <h4 class="card-title">Thông tin sản phẩm</h4>
                        </div>
                        <div class="card-body">
                            <?php if (isset($_GET['success'])): ?>
                                <div class="alert alert-success">
                                    <i class="fas fa-check-circle"></i> Sản phẩm đã được thêm thành công!
                                </div>
                            <?php endif; ?>

                            <?php if (isset($error)): ?>
                                <div class="alert alert-danger">
                                    <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?>
                                </div>
                            <?php endif; ?>

                            <form method="post" enctype="multipart/form-data" id="productForm">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Tên sản phẩm <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="name" name="name" required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="price" class="form-label">Giá gốc (VNĐ) <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" id="price" name="price" min="0" step="1000" required>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Sizes và Số lượng tồn kho <span class="text-danger">*</span></label>
                                            <div id="sizesContainer">
                                                <div class="size-row d-flex gap-2 mb-2">
                                                    <select class="form-select" name="sizes[]" style="width: 100px;" required>
                                                        <option value="">Size</option>
                                                        <option value="S">S</option>
                                                        <option value="M">M</option>
                                                        <option value="L">L</option>
                                                        <option value="XL">XL</option>
                                                        <option value="XXL">XXL</option>
                                                    </select>
                                                    <input type="number" class="form-control" name="stock[]"
                                                        placeholder="Số lượng tồn" min="1" required>
                                                    <button type="button" class="btn btn-danger btn-sm" onclick="removeSize(this)">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <button type="button" class="btn btn-sm btn-secondary mt-2" onclick="addSize()">
                                                <i class="fas fa-plus"></i> Thêm size
                                            </button>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="category_id" class="form-label">Danh mục <span class="text-danger">*</span></label>
                                                <select class="form-select" id="category_id" name="category_id" required>
                                                    <option value="">-- Chọn danh mục --</option>
                                                    <?php foreach ($categories as $category): ?>
                                                        <option value="<?= htmlspecialchars($category['id']) ?>">
                                                            <?= htmlspecialchars($category['name']) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label for="featured" class="form-label">Trạng thái nổi bật</label>
                                                <select class="form-select" id="featured" name="featured">
                                                    <option value="0">Bình thường</option>
                                                    <option value="1">Nổi bật</option>
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label for="images" class="form-label">Hình ảnh sản phẩm <span class="text-danger">*</span></label>
                                                <input type="file" class="form-control" id="images" name="images[]" multiple accept="image/*" required>
                                                <small class="text-muted">Chọn ít nhất 1 hình ảnh (có thể chọn nhiều ảnh)</small>
                                                <div class="image-preview-container" id="imagePreview"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="description" class="form-label">Mô tả sản phẩm</label>
                                        <textarea class="form-control" id="description" name="description" rows="5"></textarea>
                                    </div>

                                    <div class="d-flex justify-content-between mt-4">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save"></i> Lưu sản phẩm
                                        </button>
                                        <a href="index.php?controller=admin&action=products" class="btn btn-secondary">
                                            <i class="fas fa-times"></i> Hủy bỏ
                                        </a>
                                    </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Main Content -->
    </div>

    <!-- Custom JS for sidebar functionality -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Set active menu item
            document.querySelector('a[href="index.php?controller=admin&action=products"]').parentElement.classList.add('active');

            // Image preview functionality
            const imageInput = document.getElementById('images');
            const imagePreview = document.getElementById('imagePreview');

            imageInput.addEventListener('change', function() {
                imagePreview.innerHTML = '';

                if (this.files && this.files.length > 0) {
                    for (let i = 0; i < this.files.length; i++) {
                        const reader = new FileReader();

                        reader.onload = function(e) {
                            const img = document.createElement('img');
                            img.src = e.target.result;
                            img.classList.add('preview-image');
                            imagePreview.appendChild(img);
                        }

                        reader.readAsDataURL(this.files[i]);
                    }
                }
            });

            // Form validation
            document.getElementById('productForm').addEventListener('submit', function(e) {
                const price = document.getElementById('price').value;
                if (price <= 0) {
                    alert('Giá sản phẩm phải lớn hơn 0');
                    e.preventDefault();
                }

                const images = document.getElementById('images').files;
                if (images.length === 0) {
                    alert('Vui lòng chọn ít nhất 1 hình ảnh');
                    e.preventDefault();
                }
            });

            // Cập nhật validation trong form submit
            document.getElementById('productForm').addEventListener('submit', function(e) {
                const price = parseFloat(document.getElementById('price').value);
                const discountType = document.getElementById('discount_type').value;
            });

        });

        function addSize() {
            const container = document.getElementById('sizesContainer');
            const sizeRow = document.createElement('div');
            sizeRow.className = 'size-row d-flex gap-2 mb-2';
            sizeRow.innerHTML = `
            <select class="form-select" name="sizes[]" style="width: 100px;" required>
                <option value="">Size</option>
                <option value="S">S</option>
                <option value="M">M</option>
                <option value="L">L</option>
                <option value="XL">XL</option>
                <option value="XXL">XXL</option>
            </select>
            <input type="number" class="form-control" name="stock[]" 
                   placeholder="Số lượng tồn" min="1" required>
            <button type="button" class="btn btn-danger btn-sm" onclick="removeSize(this)">
                <i class="fas fa-times"></i>
            </button>
        `;
            container.appendChild(sizeRow);
        }

        function removeSize(button) {
            const sizeRows = document.querySelectorAll('.size-row');
            if (sizeRows.length > 1) {
                // Chỉ xóa nếu có nhiều hơn 1 size
                button.closest('.size-row').remove();
            } else {
                alert('Phải có ít nhất một size cho sản phẩm');
            }
        }

        // Cập nhật validation
        document.getElementById('productForm').addEventListener('submit', function(e) {
            const sizes = document.getElementsByName('sizes[]');
            const stocks = document.getElementsByName('stock[]');
            const selectedSizes = new Set();

            for (let i = 0; i < sizes.length; i++) {
                if (sizes[i].value === '') {
                    alert('Vui lòng chọn size');
                    e.preventDefault();
                    return;
                }
                if (selectedSizes.has(sizes[i].value)) {
                    alert('Không được chọn trùng size');
                    e.preventDefault();
                    return;
                }
                selectedSizes.add(sizes[i].value);

                if (stocks[i].value <= 0) {
                    alert('Số lượng tồn phải lớn hơn 0');
                    e.preventDefault();
                    return;
                }
            }
        });
    </script>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>

</html>