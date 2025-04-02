<!-- filepath: c:\xampp\htdocs\DoAnPHP\app\views\products\detail.php -->
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Admin Dashboard - Chi tiết sản phẩm</title>
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
        .product-detail-card {
            max-width: 900px;
            margin: 0 auto;
        }
        .product-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            margin-bottom: 15px;
        }
        .detail-label {
            font-weight: 600;
            color: #495057;
            min-width: 150px;
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
                <h2 class="page-title">Chi tiết sản phẩm</h2>
                <div class="page-actions">
                    <a href="index.php?controller=product&action=edit&id=<?= $product['id'] ?>" class="btn btn-primary">
                        <i class="fas fa-edit"></i> Chỉnh sửa
                    </a>
                    <a href="index.php?controller=admin&action=products" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </a>
                </div>
            </div>
            
            <div class="card product-detail-card">
                <div class="card-header">
                    <h4 class="card-title"><?= htmlspecialchars($product['name']) ?></h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="d-flex mb-3">
                                <span class="detail-label">ID sản phẩm:</span>
                                <span><?= htmlspecialchars($product['id']) ?></span>
                            </div>
                            <div class="d-flex mb-3">
                                <span class="detail-label">Giá bán:</span>
                                <span class="text-danger fw-bold"><?= number_format($product['price'], 0, ',', '.') ?> đ</span>
                            </div>
                            <div class="d-flex mb-3">
                                <span class="detail-label">Số lượng tồn kho:</span>
                                <span><?= htmlspecialchars($product['stock'] ?? 0) ?></span>
                            </div>
                            <div class="d-flex mb-3">
                                <span class="detail-label">Danh mục:</span>
                                <span><?= htmlspecialchars($product['category_name'] ?? 'Chưa phân loại') ?></span>
                            </div>
                            <div class="d-flex mb-3">
                                <span class="detail-label">Trạng thái:</span>
                                <span class="badge <?= $product['featured'] ? 'bg-success' : 'bg-secondary' ?>">
                                    <?= $product['featured'] ? 'Nổi bật' : 'Bình thường' ?>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <span class="detail-label d-block mb-2">Ngày tạo:</span>
                                <span><?= date('d/m/Y H:i', strtotime($product['created_at'] ?? 'now')) ?></span>
                            </div>
                            <div class="mb-3">
                                <span class="detail-label d-block mb-2">Ngày cập nhật:</span>
                                <span><?= date('d/m/Y H:i', strtotime($product['updated_at'] ?? 'now')) ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <h5 class="mb-3"><i class="fas fa-align-left"></i> Mô tả sản phẩm</h5>
                        <div class="p-3 bg-light rounded">
                            <?= nl2br(htmlspecialchars($product['description'] ?? 'Chưa có mô tả')) ?>
                        </div>
                    </div>
                    
                    <div>
                        <h5 class="mb-3"><i class="fas fa-images"></i> Hình ảnh sản phẩm</h5>
                        <?php if (!empty($images)): ?>
                            <div class="row">
                                <?php foreach ($images as $image): ?>
                                    <div class="col-md-3 mb-3">
                                        <div class="card">
                                            <img src="public/images/<?= htmlspecialchars($image['image_path']) ?>" 
                                                 class="card-img-top product-image" 
                                                 alt="Hình ảnh sản phẩm">
                                            <div class="card-footer text-center">
                                                <a href="public/images/<?= htmlspecialchars($image['image_path']) ?>" 
                                                   target="_blank" 
                                                   class="btn btn-sm btn-info me-1">
                                                   <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="index.php?controller=product&action=deleteImage&id=<?= $image['id'] ?>&product_id=<?= $product['id'] ?>" 
                                                   class="btn btn-sm btn-danger"
                                                   onclick="return confirm('Bạn có chắc muốn xóa ảnh này?')">
                                                   <i class="fas fa-trash"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-warning">Sản phẩm chưa có hình ảnh</div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <a href="index.php?controller=product&action=edit&id=<?= $product['id'] ?>" class="btn btn-warning me-2">
                        <i class="fas fa-edit"></i> Chỉnh sửa
                    </a>
                    <a href="index.php?controller=admin&action=products" class="btn btn-secondary">
                        <i class="fas fa-list"></i> Danh sách sản phẩm
                    </a>
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
      });
    </script>
    
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>
</html>