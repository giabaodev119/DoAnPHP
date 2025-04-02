<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Admin Dashboard</title>
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
      .product-table {
          box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
          border-radius: 10px;
          overflow: hidden;
      }
      .product-table thead {
          background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
          color: white;
      }
      .product-table th {
          padding: 15px;
          font-weight: 500;
      }
      .product-table td {
          vertical-align: middle;
          padding: 12px 15px;
      }
      .product-table tbody tr {
          transition: all 0.3s ease;
      }
      .product-table tbody tr:hover {
          background-color: #f8f9fa;
          transform: translateX(5px);
      }
      .action-btn {
          min-width: 80px;
          margin: 2px;
      }
      .table-responsive {
          border-radius: 10px;
      }
      .price-badge {
          background-color: #e6f7ff;
          color: #1890ff;
          padding: 4px 8px;
          border-radius: 4px;
          font-weight: 500;
      }
      .product-img {
          width: 50px;
          height: 50px;
          object-fit: cover;
          border-radius: 4px;
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
            <div class="d-flex justify-content-between align-items-center mb-4">
              <h2><i class="fas fa-boxes me-2"></i>Quản lý sản phẩm</h2>
              <a href="index.php?controller=product&action=create" class="btn btn-success">
                <i class="fas fa-plus me-1"></i> Thêm sản phẩm
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
                  <table class="table table-hover product-table mb-0">
                    <thead>
                      <tr>
                        <th><i class="fas fa-hashtag me-1"></i> ID</th>
                        <th><i class="fas fa-box me-1"></i> Hình ảnh</th>
                        <th><i class="fas fa-tag me-1"></i> Tên sản phẩm</th>
                        <th><i class="fas fa-money-bill-wave me-1"></i> Giá</th>
                        <th><i class="fas fa-list me-1"></i> Danh mục</th>
                        <th><i class="fas fa-bolt me-1"></i> Hành động</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if (!empty($products)): ?>
                        <?php foreach ($products as $product): ?>
                          <tr>
                            <td>#<?= htmlspecialchars($product['id']) ?></td>
                            <td>
                              <?php if (!empty($product['image'])): ?>
                                <img src="<?= htmlspecialchars($product['image']) ?>" class="product-img" alt="<?= htmlspecialchars($product['name']) ?>">
                              <?php else: ?>
                                <div class="product-img bg-light d-flex align-items-center justify-content-center">
                                  <i class="fas fa-box-open text-muted"></i>
                                </div>
                              <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($product['name']) ?></td>
                            <td>
                              <span class="price-badge">
                                <?= number_format($product['price'], 0, ',', '.') ?> đ
                              </span>
                            </td>
                            <td>
                              <span class="badge bg-secondary">
                                <?= htmlspecialchars($product['category_name'] ?? 'N/A') ?>
                              </span>
                            </td>
                            <td>
                              <div class="d-flex">
                                <a href="index.php?controller=product&action=edit&id=<?= $product['id'] ?>" 
                                  class="btn btn-warning btn-sm action-btn">
                                  <i class="fas fa-edit me-1"></i> Sửa
                                </a>
                                <a href="index.php?controller=product&action=delete&id=<?= $product['id'] ?>" 
                                  class="btn btn-danger btn-sm action-btn ms-1"
                                  onclick="return confirm('Bạn chắc chắn muốn xóa sản phẩm này?')">
                                  <i class="fas fa-trash me-1"></i> Xóa
                                </a>
                              </div>
                            </td>
                          </tr>
                        <?php endforeach; ?>
                      <?php else: ?>
                        <tr>
                          <td colspan="6" class="text-center py-4">
                            <i class="fas fa-box-open fa-2x text-muted mb-2"></i>
                            <p class="mb-0">Không có sản phẩm nào</p>
                          </td>
                        </tr>
                      <?php endif; ?>
                    </tbody>
                  </table>
                </div>

                <!-- Phân trang -->
                <!-- filepath: c:\xampp\htdocs\DoAnPHP\app\views\admin\product\index.php -->
<!-- Phân trang -->
<nav class="mt-4">
  <ul class="pagination justify-content-center">
    <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
      <a class="page-link" href="index.php?controller=product&action=index&page=<?= $currentPage - 1 ?>" tabindex="-1">Trước</a>
    </li>
    <?php for ($i = 1; $i <= $productTotalPages; $i++): ?>
      <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
        <a class="page-link" href="index.php?controller=product&action=index&page=<?= $i ?>"><?= $i ?></a>
      </li>
    <?php endfor; ?>
    <li class="page-item <?= $currentPage >= $productTotalPages ? 'disabled' : '' ?>">
      <a class="page-link" href="index.php?controller=product&action=index&page=<?= $currentPage + 1 ?>">Tiếp</a>
    </li>
  </ul>
</nav>
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
        
        // Add active class to parent li when clicked
        var navLinks = document.querySelectorAll('.nav-item a');
        navLinks.forEach(function(link) {
          link.addEventListener('click', function() {
            navLinks.forEach(function(l) {
              l.parentElement.classList.remove('active');
            });
            this.parentElement.classList.add('active');
          });
        });

        // Auto dismiss alerts
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
          setTimeout(() => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
          }, 5000);
        });
      });
    </script>
    
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="public/js/core/bootstrap.min.js"></script>
    <script src="public/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
    <script src="public/js/core/popper.min.js"></script>
    <script src="public/js/plugin/chartist/chartist.min.js"></script>
    <script src="public/js/plugin/chartist/plugin/chartist-plugin-tooltip.min.js"></script>
    <script src="public/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>
    <script src="public/js/kaiadmin.min.js"></script>
    <script src="public/js/demo.js"></script>
  </body>
</html>