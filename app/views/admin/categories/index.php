<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <title>Admin Dashboard - Quản lý danh mục</title>
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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <style>
    .category-table {
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
      border-radius: 10px;
      overflow: hidden;
    }

    .category-table thead {
      background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
      color: white;
    }

    .category-table th {
      padding: 15px;
      font-weight: 500;
    }

    .category-table td {
      vertical-align: middle;
      padding: 12px 15px;
    }

    .category-table tbody tr {
      transition: all 0.3s ease;
    }

    .category-table tbody tr:hover {
      background-color: #f8f9fa;
      transform: translateX(5px);
    }

    .action-btn {
      min-width: 90px;
      margin: 2px;
    }

    .table-responsive {
      border-radius: 10px;
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
            <h2><i class="fas fa-tags me-2"></i>Quản lý danh mục</h2>
            <a href="index.php?controller=category&action=create" class="btn btn-success">
              <i class="fas fa-plus me-1"></i> Thêm danh mục
            </a>
          </div>

          <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              Danh mục đã được thêm/cập nhật thành công!
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          <?php elseif (isset($_GET['error']) && $_GET['error'] == 1): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              Có lỗi xảy ra. Vui lòng thử lại.
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          <?php endif; ?>

          <div class="card shadow-sm">
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-hover category-table mb-0">
                  <thead>
                    <tr>
                      <th><i class="fas fa-hashtag me-1"></i> ID</th>
                      <th><i class="fas fa-tag me-1"></i> Tên danh mục</th>
                      <th><i class="fas fa-bolt me-1"></i> Hành động</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (!empty($categories)): ?>
                      <?php foreach ($categories as $cat): ?>
                        <tr>
                          <td>#<?= htmlspecialchars($cat['id']) ?></td>
                          <td><?= htmlspecialchars($cat['name']) ?></td>
                          <td>
                            <div class="d-flex">
                              <a href="index.php?controller=category&action=edit&id=<?= $cat['id'] ?>"
                                class="btn btn-info btn-sm action-btn">
                                <i class="fas fa-edit me-1"></i> Sửa
                              </a>
                              <a href="index.php?controller=category&action=delete&id=<?= $cat['id'] ?>"
                                class="btn btn-danger btn-sm action-btn ms-1"
                                onclick="return confirm('Bạn có chắc chắn muốn xóa danh mục này?')">
                                <i class="fas fa-trash me-1"></i> Xóa
                              </a>
                            </div>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    <?php else: ?>
                      <tr>
                        <td colspan="3" class="text-center">Không có danh mục nào</td>
                      </tr>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>

              <!-- Phân trang -->
              <div class="d-flex justify-content-center mt-4">
                <nav aria-label="Điều hướng trang danh mục">
                  <ul class="pagination">
                    <!-- Nút Previous -->
                    <?php if ($page > 1): ?>
                      <li class="page-item">
                        <a class="page-link" href="index.php?controller=admin&action=categories&page=<?= $page - 1 ?>" aria-label="Trang trước">
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
                      echo '<li class="page-item"><a class="page-link" href="index.php?controller=admin&action=categories&page=1">1</a></li>';
                      if ($startPage > 2) {
                        echo '<li class="page-item disabled"><a class="page-link" href="#">...</a></li>';
                      }
                    }

                    // Hiển thị các trang trong phạm vi
                    for ($i = $startPage; $i <= $endPage; $i++) {
                      echo '<li class="page-item ' . ($i == $page ? 'active' : '') . '">';
                      echo '<a class="page-link" href="index.php?controller=admin&action=categories&page=' . $i . '">' . $i . '</a>';
                      echo '</li>';
                    }

                    // Hiển thị trang cuối nếu không nằm trong phạm vi
                    if ($endPage < $totalPages) {
                      if ($endPage < $totalPages - 1) {
                        echo '<li class="page-item disabled"><a class="page-link" href="#">...</a></li>';
                      }
                      echo '<li class="page-item"><a class="page-link" href="index.php?controller=admin&action=categories&page=' . $totalPages . '">' . $totalPages . '</a></li>';
                    }
                    ?>

                    <!-- Nút Next -->
                    <?php if ($page < $totalPages): ?>
                      <li class="page-item">
                        <a class="page-link" href="index.php?controller=admin&action=categories&page=<?= $page + 1 ?>" aria-label="Trang sau">
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
                Hiển thị <?= count($categories) ?> danh mục trên tổng số <?= $totalCategories ?> danh mục
                (Trang <?= $page ?>/<?= $totalPages ?>)
              </div>

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
          new bootstrap.Collapse(collapse, {
            toggle: false
          });
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