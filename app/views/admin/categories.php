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
        .table-container {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0,0,0,0.05);
            padding: 20px;
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
            <div class="table-container">
                <h2>Quản lý danh mục</h2>
                <a href="index.php?controller=category&action=create" class="btn btn-primary mb-3">➕ Thêm danh mục</a>
                
                <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
                    <div id="success-alert" class="alert alert-success">
                        Danh mục đã được thêm/cập nhật thành công!
                    </div>
                <?php elseif (isset($_GET['error']) && $_GET['error'] == 1): ?>
                    <div id="error-alert" class="alert alert-danger">
                        Có lỗi xảy ra. Vui lòng thử lại.
                    </div>
                <?php endif; ?>
                
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Tên danh mục</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($categories)): ?>
                            <?php foreach ($categories as $cat): ?>
                                <tr>
                                    <td><?= htmlspecialchars($cat['id']) ?></td>
                                    <td><?= htmlspecialchars($cat['name']) ?></td>
                                    <td>
                                        <!-- Nút sửa -->
                                        <a href="index.php?controller=category&action=edit&id=<?= $cat['id'] ?>" 
                                           class="btn btn-sm btn-warning">Sửa</a>
                                        
                                        <!-- Nút xóa -->
                                        <a href="index.php?controller=category&action=delete&id=<?= $cat['id'] ?>" 
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('Bạn có chắc chắn muốn xóa danh mục này?')">Xóa</a>
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

        const successAlert = document.getElementById('success-alert');
        const errorAlert = document.getElementById('error-alert');

        if (successAlert || errorAlert) {
            setTimeout(() => {
                if (successAlert) {
                    successAlert.style.transition = 'opacity 0.5s';
                    successAlert.style.opacity = '0';
                    setTimeout(() => successAlert.remove(), 500); // Xóa hẳn sau khi ẩn
                }
                if (errorAlert) {
                    errorAlert.style.transition = 'opacity 0.5s';
                    errorAlert.style.opacity = '0';
                    setTimeout(() => errorAlert.remove(), 500); // Xóa hẳn sau khi ẩn
                }
            }, 10000); // 10 giây
        }
      });
    </script>
    
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>