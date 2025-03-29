<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Admin Dashboard - Thêm danh mục</title>
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
            max-width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
      <!-- Sidebar -->
      <div class="sidebar" data-background-color="dark">
        <div class="sidebar-logo">
          <!-- Logo Header -->
          <div class="logo-header" data-background-color="dark">
            <a href="index.html" class="logo">
              <img
                src="public/img/kaiadmin/logo_light.svg"
                alt="navbar brand"
                class="navbar-brand"
                height="20"
              />
            </a>
            <div class="nav-toggle">
              <button class="btn btn-toggle toggle-sidebar">
                <i class="gg-menu-right"></i>
              </button>
              <button class="btn btn-toggle sidenav-toggler">
                <i class="gg-menu-left"></i>
              </button>
            </div>
            <button class="topbar-toggler more">
              <i class="gg-more-vertical-alt"></i>
            </button>
          </div>
          <!-- End Logo Header -->
        </div>
        <div class="sidebar-wrapper scrollbar scrollbar-inner">
          <div class="sidebar-content">
            <ul class="nav nav-secondary">
              <li class="nav-section">
                <span class="sidebar-mini-icon">
                  <i class="fa fa-ellipsis-h"></i>
                </span>
                <h4 class="text-section">Quản Lý</h4>
              </li>
              <li class="nav-item">
                <a data-bs-toggle="collapse" href="#base">
                  <i class="fas fa-layer-group"></i>
                  <p>Danh Mục</p>
                  <span class="caret"></span>
                </a>
                <div class="collapse" id="base">
                  <ul class="nav nav-collapse">
                    <li>
                      <a href="index.php?controller=admin&action=categories">
                        <span class="sub-item">Danh sách</span>
                      </a>
                    </li>
                    <li>
                      <a href="index.php?controller=admin&action=products">
                        <span class="sub-item">Sản Phẩm</span>
                      </a>
                    </li>
                    <li>
                      <a href="components/gridsystem.html">
                        <span class="sub-item">Người Dùng</span>
                      </a>
                    </li>
                    <li>
                      <a href="components/panels.html">
                        <span class="sub-item">Đơn Hàng</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <!-- End Sidebar -->

      <!-- Main Content -->
      <div class="main-panel">
        <div class="content">
          <div class="container-fluid">
            <div class="card form-card">
                <div class="card-header">
                    <h4 class="card-title">Thêm danh mục mới</h4>
                </div>
                <div class="card-body">
                    <?php if (isset($_GET['success'])): ?>
                        <div class="alert alert-success">Đã thêm danh mục thành công!</div>
                    <?php endif; ?>
                    
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>
                    
                    <form method="post" action="">
                        <div class="form-group">
                            <label for="name">Tên danh mục</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="d-flex justify-content-between mt-4">
                            <button type="submit" class="btn btn-primary">Thêm</button>
                            <a href="index.php?controller=admin&action=categories" class="btn btn-secondary">Quay lại</a>
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
        document.querySelector('a[href="index.php?controller=category&action=create"]').parentElement.classList.add('active');
      });
    </script>
    
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>