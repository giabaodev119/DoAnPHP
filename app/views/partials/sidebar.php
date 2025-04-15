<!-- Sidebar -->
<div class="sidebar" data-background-color="dark">
  <div class="sidebar-logo">
    <!-- Logo Header -->
    <div class="logo-header" data-background-color="dark">
      <a href="index.php?controller=admin&action=dashboard" class="logo">
        <img src="public/img/kaiadmin/logo_light.svg" alt="navbar brand" class="navbar-brand" height="20" />
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
          <a data-bs-toggle="collapse" href="#baseMenu" aria-expanded="true">
            <i class="fas fa-layer-group"></i>
            <p>Danh Mục</p>
            <span class="caret"></span>
          </a>
          <div class="collapse show" id="baseMenu">
            <ul class="nav nav-collapse">
              <li class="nav-item">
                <a class="nav-link <?= isset($_GET['action']) && $_GET['action'] == 'categories' ? 'active' : '' ?>" href="index.php?controller=admin&action=categories">
                  <i class="fas fa-tags me-2"></i>
                  Phân Loại
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?= isset($_GET['action']) && $_GET['action'] == 'products' ? 'active' : '' ?>" href="index.php?controller=admin&action=products">
                  <i class="fas fa-box me-2"></i>
                  Sản phẩm
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?= isset($_GET['action']) && $_GET['action'] == 'users' ? 'active' : '' ?>" href="index.php?controller=admin&action=users">
                  <i class="fas fa-users me-2"></i>
                  Người dùng
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?= isset($_GET['action']) && $_GET['action'] == 'orders' ? 'active' : '' ?>" href="index.php?controller=admin&action=orders">
                  <i class="fas fa-shopping-bag me-2"></i>
                  Đơn hàng
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?= (isset($_GET['action']) && $_GET['action'] == 'vouchers') || (isset($_GET['controller']) && $_GET['controller'] == 'voucher') ? 'active' : '' ?>" href="index.php?controller=admin&action=vouchers">
                  <i class="fas fa-ticket-alt me-2"></i>
                  Voucher
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?= (isset($_GET['controller']) && $_GET['controller'] == 'promotion') ? 'active' : '' ?>" href="index.php?controller=promotion&action=index">
                  <i class="fas fa-percentage me-2"></i>
                  Chương trình khuyến mãi
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?= isset($_GET['action']) && $_GET['action'] == 'banners' ? 'active' : '' ?>" href="index.php?controller=admin&action=banners">
                  <i class="fas fa-ad me-2"></i>
                  Banner
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link text-danger" href="index.php?controller=user&action=logout" onclick="return confirm('Bạn có chắc chắn muốn đăng xuất?')">
                  <i class="fas fa-sign-out-alt me-2"></i>
                  Đăng xuất
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