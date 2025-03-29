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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
              <h2>Danh sách sản phẩm</h2>
              <a href="index.php?controller=product&action=create" class="btn btn-success">+ Thêm sản phẩm</a>
            </div>
            
            <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
              <div id="success-alert" class="alert alert-success">
                Sản phẩm đã được thêm thành công!
              </div>
            <?php endif; ?>
            
            <div class="card">
              <div class="card-body">
                <table class="table table-striped table-hover">
                  <thead class="table-dark">
                    <tr>
                      <th>ID</th>
                      <th>Tên sản phẩm</th>
                      <th>Giá</th>
                      <th>Danh mục</th>
                      <th>Hành động</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (!empty($products)): ?>
                      <?php foreach ($products as $product): ?>
                        <tr>
                          <td><?= htmlspecialchars($product['id']) ?></td>
                          <td><?= htmlspecialchars($product['name']) ?></td>
                          <td><?= number_format($product['price'], 0, ',', '.') ?> đ</td>
                          <td><?= htmlspecialchars($product['category_name'] ?? 'N/A') ?></td>
                          <td>
                            <a href="index.php?controller=product&action=edit&id=<?= $product['id'] ?>" class="btn btn-sm btn-warning">Sửa</a>
                            <a href="index.php?controller=product&action=delete&id=<?= $product['id'] ?>" class="btn btn-sm btn-danger" 
                              onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này?')">Xóa</a>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    <?php else: ?>
                      <tr>
                        <td colspan="5" class="text-center">Không có sản phẩm nào</td>
                      </tr>
                    <?php endif; ?>
                  </tbody>
                </table>
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

        const successAlert = document.getElementById('success-alert');
        if (successAlert) {
            setTimeout(() => {
                successAlert.style.transition = 'opacity 0.5s';
                successAlert.style.opacity = '0';
                setTimeout(() => successAlert.remove(), 500); // Xóa hẳn sau khi ẩn
            }, 10000); // 10 giây
        }
      });
    </script>
    
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>