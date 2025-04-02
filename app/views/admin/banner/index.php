<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Admin Dashboard - Quản lý Banner</title>
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
      .banner-table {
          box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
          border-radius: 10px;
          overflow: hidden;
      }
      .banner-table thead {
          background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
          color: white;
      }
      .banner-table th {
          padding: 15px;
          font-weight: 500;
      }
      .banner-table td {
          vertical-align: middle;
          padding: 12px 15px;
      }
      .banner-table tbody tr {
          transition: all 0.3s ease;
      }
      .banner-table tbody tr:hover {
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
      .banner-img {
          max-width: 150px;
          border-radius: 5px;
          box-shadow: 0 2px 5px rgba(0,0,0,0.1);
          transition: transform 0.3s;
      }
      .banner-img:hover {
          transform: scale(1.05);
      }
      .status-badge {
          padding: 6px 10px;
          border-radius: 20px;
          font-size: 0.85rem;
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
              <h2><i class="fas fa-images me-2"></i>Quản lý Banner</h2>
              <a href="index.php?controller=admin&action=createBanner" class="btn btn-success">
                <i class="fas fa-plus me-1"></i> Thêm Banner
              </a>
            </div>
            
            <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
              <div class="alert alert-success alert-dismissible fade show" role="alert">
                Banner đã được thêm/cập nhật thành công!
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
      <table class="table table-hover banner-table mb-0">
        <thead>
          <tr>
            <th><i class="fas fa-hashtag me-1"></i> ID</th>
            <th><i class="fas fa-image me-1"></i> Hình ảnh</th>
            <th><i class="fas fa-heading me-1"></i> Tiêu đề</th>
            <th><i class="fas fa-eye me-1"></i> Trạng thái</th>
            <th><i class="fas fa-cogs me-1"></i> Hành động</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($banners)): ?>
            <?php foreach ($banners as $banner): ?>
              <tr>
                <td>#<?= htmlspecialchars($banner['id']) ?></td>
                <td>
                  <img src="public/images/banners/<?= htmlspecialchars($banner['image_path']) ?>" 
                       class="banner-img" alt="Banner">
                </td>
                <td><?= htmlspecialchars($banner['title']) ?></td>
                <td>
                  <span class="status-badge <?= $banner['status'] === 'active' ? 'bg-success' : 'bg-secondary' ?>">
                    <i class="fas <?= $banner['status'] === 'active' ? 'fa-eye' : 'fa-eye-slash' ?> me-1"></i>
                    <?= $banner['status'] === 'active' ? 'Hiển thị' : 'Ẩn' ?>
                  </span>
                </td>
                <td>
                  <div class="d-flex flex-wrap">
                    <a href="index.php?controller=admin&action=editBanner&id=<?= $banner['id'] ?>" 
                      class="btn btn-info btn-sm action-btn">
                      <i class="fas fa-edit me-1"></i> Sửa
                    </a>
                    <a href="index.php?controller=admin&action=toggleBannerStatus&id=<?= $banner['id'] ?>" 
                      class="btn btn-secondary btn-sm action-btn">
                      <i class="fas <?= $banner['status'] === 'active' ? 'fa-eye-slash' : 'fa-eye' ?> me-1"></i>
                      <?= $banner['status'] === 'active' ? 'Ẩn' : 'Hiển thị' ?>
                    </a>
                    <a href="index.php?controller=admin&action=deleteBanner&id=<?= $banner['id'] ?>" 
                      class="btn btn-danger btn-sm action-btn"
                      onclick="return confirm('Bạn có chắc chắn muốn xóa banner này?')">
                      <i class="fas fa-trash me-1"></i> Xóa
                    </a>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="5" class="text-center">Không có banner nào</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <!-- Phân trang -->
    <nav class="mt-4">
      <ul class="pagination justify-content-center">
        <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
          <a class="page-link" href="index.php?controller=admin&action=banners&page=<?= $currentPage - 1 ?>" tabindex="-1">Trước</a>
        </li>
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
          <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
            <a class="page-link" href="index.php?controller=admin&action=banners&page=<?= $i ?>"><?= $i ?></a>
          </li>
        <?php endfor; ?>
        <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">
          <a class="page-link" href="index.php?controller=admin&action=banners&page=<?= $currentPage + 1 ?>">Tiếp</a>
        </li>
      </ul>
    </nav>
  </div>
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