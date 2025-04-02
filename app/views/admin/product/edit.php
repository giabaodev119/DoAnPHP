<!-- filepath: c:\xampp\htdocs\DoAnPHP\app\views\products\edit.php -->
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Admin Dashboard - Chỉnh sửa sản phẩm</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no" />
    <link rel="icon" href="public/img/kaiadmin/favicon.ico" type="image/x-icon" />

    <!-- Fonts and icons -->
    <script src="public/js/plugin/webfont/webfont.min.js"></script>
    <script>
      WebFont.load({
        google: { families: ["Public Sans:300,400,500,600,700"] },
        custom: {
          families: [
            "Font Awesome 5 Solid",
            "Font Awesome 5 Regular",
            "Font Awesome 5 Brands",
            "simple-line-icons",
          ],
          urls: ["public/css/fonts.min.css"],
        },
        active: function () {
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
            max-width: 800px;
            margin: 0 auto;
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
            <div class="card form-card">
                <div class="card-header">
                    <h4 class="card-title">Chỉnh sửa sản phẩm</h4>
                </div>
                <div class="card-body">
                    <?php if (isset($_GET['success'])): ?>
                        <div class="alert alert-success">Đã cập nhật sản phẩm thành công!</div>
                    <?php endif; ?>
                    
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>
                    
                    <form method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Tên sản phẩm</label>
                                    <input type="text" class="form-control" id="name" name="name" 
                                           value="<?= htmlspecialchars($product['name'] ?? '') ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="price" class="form-label">Giá (VNĐ)</label>
                                    <input type="number" class="form-control" id="price" name="price" 
                                           value="<?= $product['price'] ?? 0 ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="stock" class="form-label">Số lượng tồn kho</label>
                                    <input type="number" class="form-control" id="stock" name="stock" 
                                           value="<?= $product['stock'] ?? 0 ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="category_id" class="form-label">Danh mục</label>
                                    <select class="form-select" id="category_id" name="category_id" required>
                                        <?php foreach ($categories as $category): ?>
                                            <option value="<?= $category['id'] ?>" 
                                                <?= ($product['category_id'] ?? 0) == $category['id'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($category['name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="featured" class="form-label">Trạng thái nổi bật</label>
                                    <select class="form-select" id="featured" name="featured">
                                        <option value="1" <?= ($product['featured'] ?? 0) == 1 ? 'selected' : '' ?>>Nổi bật</option>
                                        <option value="0" <?= ($product['featured'] ?? 0) == 0 ? 'selected' : '' ?>>Bình thường</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="images" class="form-label">Hình ảnh sản phẩm</label>
                                    <input type="file" class="form-control" id="images" name="images[]" multiple>
                                    <small class="text-muted">Có thể chọn nhiều ảnh cùng lúc</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Mô tả sản phẩm</label>
                            <textarea class="form-control" id="description" name="description" rows="5"><?= htmlspecialchars($product['description'] ?? '') ?></textarea>
                        </div>
                        
                        <!-- Hiển thị hình ảnh hiện tại -->
                        <?php if (!empty($images)): ?>
                            <div class="mb-3">
                                <label class="form-label">Hình ảnh hiện tại</label>
                                <div class="d-flex flex-wrap gap-3">
                                    <?php foreach ($images as $image): ?>
                                        <div class="position-relative" style="width: 150px;">
                                            <img src="public/images/<?= htmlspecialchars($image['image_path']) ?>" 
                                                 class="img-thumbnail" style="height: 150px; object-fit: cover;">
                                            <a href="index.php?controller=product&action=deleteImage&id=<?= $image['id'] ?>&product_id=<?= $product['id'] ?>" 
                                               class="position-absolute top-0 end-0 btn btn-sm btn-danger"
                                               onclick="return confirm('Bạn có chắc muốn xóa ảnh này?')">
                                               ×
                                            </a>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <div class="d-flex justify-content-between mt-4">
                            <button type="submit" class="btn btn-primary">Cập nhật sản phẩm</button>
                            <a href="index.php?controller=admin&action=products" class="btn btn-secondary">Quay lại</a>
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
        // Initialize all collapses
        var collapses = document.querySelectorAll('.collapse');
        collapses.forEach(function(collapse) {
          // Close all collapses except the active one
          if (!collapse.classList.contains('show')) {
            new bootstrap.Collapse(collapse, {toggle: false});
          }
        });
        
        // Set active menu item
        document.querySelector('a[href="index.php?controller=admin&action=products"]').parentElement.classList.add('active');
      });
    </script>
    
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>