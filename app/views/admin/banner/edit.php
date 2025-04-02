<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Admin Dashboard - Chỉnh sửa Banner</title>
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
      .form-container {
          max-width: 800px;
          margin: 0 auto;
          padding: 30px;
          border-radius: 10px;
          box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
          background-color: white;
      }
      .form-header {
          border-bottom: 1px solid #eee;
          padding-bottom: 15px;
          margin-bottom: 30px;
      }
      .form-label {
          font-weight: 600;
          color: #555;
      }
      .current-image {
          max-width: 100%;
          height: auto;
          border-radius: 5px;
          box-shadow: 0 2px 10px rgba(0,0,0,0.1);
          margin-bottom: 15px;
          border: 1px solid #eee;
      }
      .preview-image {
          max-width: 100%;
          height: auto;
          border-radius: 5px;
          margin-top: 10px;
          display: none;
          box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      }
      .btn-submit {
          padding: 10px 25px;
          font-weight: 600;
          letter-spacing: 0.5px;
      }
      .custom-file-upload {
          border: 1px dashed #ccc;
          display: inline-block;
          padding: 20px;
          cursor: pointer;
          width: 100%;
          text-align: center;
          border-radius: 5px;
          transition: all 0.3s;
          margin-top: 10px;
      }
      .custom-file-upload:hover {
          border-color: #2575fc;
          background-color: #f8f9fa;
      }
      .file-input {
          display: none;
      }
      .image-section {
          background-color: #f9f9f9;
          padding: 20px;
          border-radius: 8px;
          margin-bottom: 20px;
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
            <div class="row">
              <div class="col-12">
                <div class="form-container">
                  <div class="form-header">
                    <h2><i class="fas fa-edit me-2"></i>Chỉnh sửa Banner</h2>
                    <p class="text-muted">Cập nhật thông tin banner hiện có</p>
                  </div>
                  
                  <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                      <i class="fas fa-exclamation-circle me-2"></i><?= $_SESSION['error'] ?>
                      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                  <?php endif; ?>
                  
                  <form method="POST" enctype="multipart/form-data" id="bannerForm">
                    <div class="mb-4">
                      <label for="title" class="form-label"><i class="fas fa-heading me-1"></i> Tiêu đề banner</label>
                      <input type="text" class="form-control form-control-lg" id="title" name="title" 
                             value="<?= htmlspecialchars($banner['title']) ?>" required
                             placeholder="Nhập tiêu đề banner">
                    </div>
                    
                    <div class="mb-4">
                      <div class="image-section">
                        <label class="form-label"><i class="fas fa-image me-1"></i> Hình ảnh hiện tại</label>
                        <div class="text-center">
                          <img src="public/images/banners/<?= htmlspecialchars($banner['image_path']) ?>" 
                               class="current-image" alt="Banner hiện tại">
                          <p class="text-muted mt-2">Hình ảnh đang sử dụng</p>
                        </div>
                        
                        <label class="form-label mt-3"><i class="fas fa-sync-alt me-1"></i> Thay đổi hình ảnh</label>
                        <label for="image" class="custom-file-upload">
                          <i class="fas fa-cloud-upload-alt fa-2x mb-2" style="color: #2575fc;"></i>
                          <p class="mb-1">Kéo thả hình ảnh mới vào đây hoặc click để chọn</p>
                          <small class="text-muted">(Để trống nếu không muốn thay đổi ảnh)</small>
                        </label>
                        <input type="file" class="file-input" id="image" name="image" accept="image/*">
                        <img id="imagePreview" class="preview-image" src="#" alt="Preview">
                      </div>
                    </div>
                    
                    <div class="mb-4">
                      <label for="status" class="form-label"><i class="fas fa-eye me-1"></i> Trạng thái</label>
                      <select class="form-select form-select-lg" id="status" name="status">
                        <option value="active" <?= $banner['status'] === 'active' ? 'selected' : '' ?>>Hiển thị</option>
                        <option value="inactive" <?= $banner['status'] === 'inactive' ? 'selected' : '' ?>>Ẩn</option>
                      </select>
                    </div>
                    
                    <div class="d-flex justify-content-between mt-5">
                      <a href="index.php?controller=admin&action=banners" class="btn btn-outline-secondary btn-lg">
                        <i class="fas fa-arrow-left me-1"></i> Quay lại
                      </a>
                      <button type="submit" class="btn btn-primary btn-lg btn-submit">
                        <i class="fas fa-save me-1"></i> Cập nhật
                      </button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- End Main Content -->
    </div>

    <!-- JavaScript -->
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        // Image preview functionality
        const imageInput = document.getElementById('image');
        const imagePreview = document.getElementById('imagePreview');
        
        imageInput.addEventListener('change', function() {
          const file = this.files[0];
          if (file) {
            const reader = new FileReader();
            
            reader.addEventListener('load', function() {
              imagePreview.style.display = 'block';
              imagePreview.src = this.result;
            });
            
            reader.readAsDataURL(file);
          }
        });
        
        // Custom file upload click
        document.querySelector('.custom-file-upload').addEventListener('click', function() {
          imageInput.click();
        });
        
        // Drag and drop functionality
        const uploadArea = document.querySelector('.custom-file-upload');
        
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
          uploadArea.addEventListener(eventName, preventDefaults, false);
        });
        
        function preventDefaults(e) {
          e.preventDefault();
          e.stopPropagation();
        }
        
        ['dragenter', 'dragover'].forEach(eventName => {
          uploadArea.addEventListener(eventName, highlight, false);
        });
        
        ['dragleave', 'drop'].forEach(eventName => {
          uploadArea.addEventListener(eventName, unhighlight, false);
        });
        
        function highlight() {
          uploadArea.classList.add('bg-primary', 'bg-opacity-10');
          uploadArea.style.borderColor = '#2575fc';
        }
        
        function unhighlight() {
          uploadArea.classList.remove('bg-primary', 'bg-opacity-10');
          uploadArea.style.borderColor = '#ccc';
        }
        
        uploadArea.addEventListener('drop', handleDrop, false);
        
        function handleDrop(e) {
          const dt = e.dataTransfer;
          const files = dt.files;
          imageInput.files = files;
          
          // Trigger change event
          const event = new Event('change');
          imageInput.dispatchEvent(event);
        }
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