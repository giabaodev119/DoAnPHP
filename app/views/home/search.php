<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kết quả tìm kiếm - Shop Công nghệ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="public/css/style.css">
    <style>
        .search-results-header {
            background-color: #f8f9fa;
            padding: 20px 0;
            margin-bottom: 30px;
            border-bottom: 1px solid #e9ecef;
        }
        .search-result-count {
            color: #6c757d;
            margin-bottom: 20px;
        }
        .product-item {
            transition: transform 0.3s, box-shadow 0.3s;
            margin-bottom: 30px;
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid rgba(0,0,0,0.1);
        }
        .product-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .product-image-container {
            height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background-color: #f8f9fa;
            padding: 10px;
        }
        .product-image {
            max-height: 180px;
            max-width: 100%;
            object-fit: contain;
        }
        .product-details {
            padding: 15px;
        }
        .product-name {
            font-weight: 600;
            font-size: 16px;
            height: 48px;
            overflow: hidden;
            margin-bottom: 10px;
        }
        .badge-featured {
            background-color: #ff6600;
            color: white;
            font-size: 12px;
            padding: 3px 8px;
            border-radius: 3px;
            display: inline-block;
            margin-bottom: 8px;
        }
        .badge-discount {
            background-color: #e74c3c;
            color: white;
            font-size: 12px;
            padding: 3px 8px;
            border-radius: 3px;
            display: inline-block;
            margin-bottom: 8px;
            margin-left: 5px;
        }
        .product-price {
            color: #e74c3c;
            font-weight: bold;
            font-size: 18px;
            margin-bottom: 5px;
        }
        .product-sold {
            color: #6c757d;
            font-size: 13px;
        }
        .product-actions {
            margin-top: 15px;
        }
        .btn-add-cart {
            background-color: #2ecc71;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            width: 100%;
            transition: background-color 0.3s;
        }
        .btn-add-cart:hover {
            background-color: #27ae60;
        }
        .search-form {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            margin-bottom: 30px;
        }
        .search-title {
            position: relative;
            padding-bottom: 15px;
            margin-bottom: 25px;
            text-align: center;
            color: #343a40;
        }
        .search-title::after {
            content: "";
            position: absolute;
            left: 50%;
            bottom: 0;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background-color: #ff6600;
        }
        .features {
            background-color: #f8f9fa;
            padding: 40px 0;
            margin-top: 40px;
        }
        .feature {
            text-align: center;
            padding: 20px;
        }
        .feature i {
            font-size: 40px;
            color: #ff6600;
            margin-bottom: 15px;
        }
        .no-results {
            text-align: center;
            padding: 40px 20px;
            background-color: #f8f9fa;
            border-radius: 10px;
        }
        .no-results i {
            font-size: 60px;
            color: #6c757d;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <?php include 'app/views/partials/header.php'; ?>

    <div class="container">
        <!-- Form tìm kiếm nâng cao -->
        <div class="search-form">
            <h4 class="search-title">Tìm Kiếm Sản Phẩm</h4>
            <form action="index.php" method="GET" class="row g-3 align-items-end">
                <input type="hidden" name="controller" value="product">
                <input type="hidden" name="action" value="search">
                
                <div class="col-md-5">
                    <label for="keyword" class="form-label">Từ khóa</label>
                    <input type="text" class="form-control" id="keyword" name="keyword" 
                        placeholder="Nhập từ khóa tìm kiếm..." 
                        value="<?= isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : '' ?>">
                </div>
                
                <div class="col-md-5">
                    <label for="category" class="form-label">Danh mục</label>
                    <select class="form-select" id="category" name="category">
                        <option value="">Tất cả danh mục</option>
                        <?php
                        require_once 'app/models/Category.php';
                        $categoryModel = new Category();
                        $categories = $categoryModel->getAll();
                        foreach ($categories as $cat) :
                        ?>
                            <option value="<?= $cat['id'] ?>" <?= (isset($_GET['category']) && $_GET['category'] == $cat['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-2"></i>Tìm kiếm
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Kết quả tìm kiếm -->
        <?php if (!empty($products)): ?>
            <div class="search-result-count">
                <p><?= count($products) ?> sản phẩm được tìm thấy</p>
            </div>
            
            <div class="row">
                <?php 
                $productModel = new Product();
                foreach ($products as $product): 
                    // Lấy hình ảnh từ bảng product_images
                    $images = $productModel->getProductImages($product['id']);
                    $imagePath = !empty($images) ? 'public/images/' . $images[0]['image_path'] : 'public/images/default.jpg';
                    
                    // Kiểm tra file tồn tại
                    if (!file_exists($imagePath)) {
                        $imagePath = 'public/images/default.jpg';
                    }
                ?>
                <div class="col-md-3 col-sm-6">
                    <div class="product-item">
                        <a href="index.php?controller=product&action=detail&id=<?= $product['id'] ?>" class="text-decoration-none text-dark">
                            <div class="product-image-container">
                                <img src="<?= htmlspecialchars($imagePath) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="product-image">
                            </div>
                            
                            <div class="product-details">
                                <h5 class="product-name"><?= htmlspecialchars($product['name']) ?></h5>
                                
                                <div>
                                    <?php if ($product['featured'] == 1): ?>
                                        <span class="badge-featured">Yêu thích</span>
                                    <?php endif; ?>
                                    <span class="badge-discount">Giảm giá</span>
                                </div>
                                
                                <div class="product-price"><?= number_format($product['price'], 0, ',', '.') ?> đ</div>
                                <div class="product-sold">Đã bán <?= isset($product['sold']) ? $product['sold'] : 'N/A' ?></div>
                                
                                <div class="product-actions">
                                <a href="index.php?controller=product&action=addToCart&id=<?= $product['id'] ?>" class="btn btn-add-cart">
                                    <i class="fas fa-shopping-cart me-2"></i>Thêm vào giỏ
                                </a>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Phân trang có thể thêm ở đây -->
            
        <?php else: ?>
            <div class="no-results">
                <i class="fas fa-search"></i>
                <h3>Không tìm thấy sản phẩm nào phù hợp</h3>
                <p class="text-muted">Vui lòng thử lại với từ khóa khác hoặc duyệt qua danh mục sản phẩm của chúng tôi</p>
                <a href="index.php?controller=product&action=index" class="btn btn-primary mt-3">
                    <i class="fas fa-list me-2"></i>Xem tất cả sản phẩm
                </a>
            </div>
        <?php endif; ?>
    </div>
    
    <?php include 'app/views/partials/footer.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
