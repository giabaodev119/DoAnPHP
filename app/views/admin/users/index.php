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
    .user-table {
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
      border-radius: 10px;
      overflow: hidden;
    }

    .user-table thead {
      background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
      color: white;
    }

    .user-table th {
      padding: 15px;
      font-weight: 500;
    }

    .user-table td {
      vertical-align: middle;
      padding: 12px 15px;
    }

    .user-table tbody tr {
      transition: all 0.3s ease;
    }

    .user-table tbody tr:hover {
      background-color: #f8f9fa;
      transform: translateX(5px);
    }

    .status-badge {
      padding: 5px 10px;
      border-radius: 20px;
      font-size: 0.8rem;
      font-weight: 500;
    }

    .status-active {
      background-color: #d1fae5;
      color: #065f46;
    }

    .status-banned {
      background-color: #fee2e2;
      color: #b91c1c;
    }

    .action-btn {
      min-width: 90px;
      margin: 2px;
    }

    .table-responsive {
      border-radius: 10px;
    }

    .avatar-sm {
      width: 32px;
      height: 32px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
    }

    .avatar-title {
      color: white;
      font-weight: bold;
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
            <h2><i class="fas fa-users me-2"></i>Quản lý người dùng</h2>
            <a href="index.php?controller=admin&action=createUser" class="btn btn-success">
              <i class="fas fa-plus me-1"></i> Thêm người dùng
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
                <table class="table table-hover user-table mb-0">
                  <thead>
                    <tr>
                      <th><i class="fas fa-hashtag me-1"></i> ID</th>
                      <th><i class="fas fa-user me-1"></i> Tên</th>
                      <th><i class="fas fa-envelope me-1"></i> Email</th>
                      <th><i class="fas fa-user-tag me-1"></i> Vai trò</th>
                      <th><i class="fas fa-circle-info me-1"></i> Trạng thái</th>
                      <th><i class="fas fa-calendar-day me-1"></i> Ngày tạo</th>
                      <th><i class="fas fa-bolt me-1"></i> Hành động</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (!empty($users)): ?>
                      <?php foreach ($users as $user): ?>
                        <tr>
                          <td>#<?= htmlspecialchars($user['id']) ?></td>
                          <td>
                            <div class="d-flex align-items-center">
                              <div class="avatar-sm me-2">
                                <span class="avatar-title bg-primary rounded-circle">
                                  <?= strtoupper(substr($user['name'], 0, 1)) ?>
                                </span>
                              </div>
                              <div>
                                <?= htmlspecialchars($user['name']) ?>
                              </div>
                            </div>
                          </td>
                          <td><?= htmlspecialchars($user['email']) ?></td>
                          <td>
                            <span class="badge <?= $user['role'] === 'admin' ? 'bg-primary' : 'bg-secondary' ?>">
                              <?= htmlspecialchars($user['role']) ?>
                            </span>
                          </td>
                          <td>
                            <span class="status-badge <?= $user['status'] === 'banned' ? 'status-banned' : 'status-active' ?>">
                              <i class="fas <?= $user['status'] === 'banned' ? 'fa-lock' : 'fa-check-circle' ?> me-1"></i>
                              <?= $user['status'] === 'banned' ? 'Đã khóa' : 'Hoạt động' ?>
                            </span>
                          </td>
                          <td><?= date('d/m/Y', strtotime($user['created_at'])) ?></td>
                          <td>
                            <div class="d-flex">
                              <?php if ($user['role'] !== 'admin'): ?>
                                <?php if ($user['status'] === 'active'): ?>
                                  <a href="index.php?controller=admin&action=banUser&id=<?= $user['id'] ?>"
                                    class="btn btn-danger btn-sm action-btn"
                                    onclick="return confirm('Bạn chắc chắn muốn khóa tài khoản này?')">
                                    <i class="fas fa-lock me-1"></i> Khóa
                                  </a>
                                <?php else: ?>
                                  <a href="index.php?controller=admin&action=unbanUser&id=<?= $user['id'] ?>"
                                    class="btn btn-success btn-sm action-btn"
                                    onclick="return confirm('Bạn chắc chắn muốn mở khóa tài khoản này?')">
                                    <i class="fas fa-unlock me-1"></i> Mở khóa
                                  </a>
                                <?php endif; ?>
                              <?php endif; ?>
                              <a href="index.php?controller=admin&action=editUser&id=<?= $user['id'] ?>"
                                class="btn btn-info btn-sm action-btn ms-1">
                                <i class="fas fa-edit me-1"></i> Sửa
                              </a>
                            </div>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    <?php else: ?>
                      <tr>
                        <td colspan="7" class="text-center">Không có người dùng nào</td>
                      </tr>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>

              <!-- Phân trang -->
              <div class="d-flex justify-content-center mt-4">
                <nav aria-label="Điều hướng trang người dùng">
                  <ul class="pagination">
                    <!-- Nút Previous -->
                    <?php if ($page > 1): ?>
                      <li class="page-item">
                        <a class="page-link" href="index.php?controller=admin&action=users&page=<?= $page - 1 ?>" aria-label="Trang trước">
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
                      echo '<li class="page-item"><a class="page-link" href="index.php?controller=admin&action=users&page=1">1</a></li>';
                      if ($startPage > 2) {
                        echo '<li class="page-item disabled"><a class="page-link" href="#">...</a></li>';
                      }
                    }

                    // Hiển thị các trang trong phạm vi
                    for ($i = $startPage; $i <= $endPage; $i++) {
                      echo '<li class="page-item ' . ($i == $page ? 'active' : '') . '">';
                      echo '<a class="page-link" href="index.php?controller=admin&action=users&page=' . $i . '">' . $i . '</a>';
                      echo '</li>';
                    }

                    // Hiển thị trang cuối nếu không nằm trong phạm vi
                    if ($endPage < $totalPages) {
                      if ($endPage < $totalPages - 1) {
                        echo '<li class="page-item disabled"><a class="page-link" href="#">...</a></li>';
                      }
                      echo '<li class="page-item"><a class="page-link" href="index.php?controller=admin&action=users&page=' . $totalPages . '">' . $totalPages . '</a></li>';
                    }
                    ?>

                    <!-- Nút Next -->
                    <?php if ($page < $totalPages): ?>
                      <li class="page-item">
                        <a class="page-link" href="index.php?controller=admin&action=users&page=<?= $page + 1 ?>" aria-label="Trang sau">
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
                Hiển thị <?= count($users) ?> người dùng trên tổng số <?= $totalUsers ?> người dùng
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