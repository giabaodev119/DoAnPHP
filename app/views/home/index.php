<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Trang chủ - Shop Công nghệ</title>
    <link rel="stylesheet" href="public/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
</head>

<body>
<?php
    require_once 'app/views/partials/header.php';
?>

    <section class="banner">
        Chào mừng đến với cửa hàng công nghệ!
    </section>

    <!-- Form tìm kiếm -->
    <form action="index.php" method="GET" style="text-align: center; margin-bottom: 20px;">
        <input type="hidden" name="controller" value="product">
        <input type="hidden" name="action" value="search">
        
        <input type="text" name="keyword" placeholder="Nhập từ khóa tìm kiếm..." 
            value="<?= isset($_GET['keyword']) ? $_GET['keyword'] : '' ?>" 
            style="padding: 10px; width: 40%; border: 1px solid #ddd; border-radius: 5px;">
        
        <select name="category" style="padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
            <option value="">Tất cả danh mục</option>
            <?php
            require_once 'app/models/Category.php';
            $categoryModel = new Category();
            $categories = $categoryModel->getAll();
            foreach ($categories as $cat) :
            ?>
                <option value="<?= $cat['id'] ?>" <?= (isset($_GET['category']) && $_GET['category'] == $cat['id']) ? 'selected' : '' ?>>
                    <?= $cat['name'] ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit" style="padding: 10px 15px; background-color: #ff6600; color: white; border: none; cursor: pointer; border-radius: 5px;">
            🔍 Tìm kiếm
        </button>
    </form>

    <!-- Sử dụng partial gridview để hiển thị sản phẩm nổi bật -->
    <?php
        // Thiết lập các tham số cho gridview
        $products = $featuredProducts; 
        $title = 'Sản phẩm nổi bật';
        $showDiscount = true; // Hiển thị nhãn giảm giá
        $showAddToCart = true; // Hiển thị nút thêm vào giỏ hàng

        // Load hình ảnh cho các sản phẩm từ bảng product_images
        if (!empty($products)) {
            $productModel = new Product();
            
            foreach ($products as &$product) {
                // Lấy ảnh đầu tiên của sản phẩm
                $images = $productModel->getProductImages($product['id']);
                if (!empty($images)) {
                    $product['image_path'] = $images[0]['image_path'];
                }
            }
            unset($product); // Xóa tham chiếu
        }

        // Include partial view
        include 'app/views/partials/gridview.php';
    ?>

    <section class="features">
        <div class="feature">
            <h3>Giao hàng nhanh</h3>
            <p>Ship toàn quốc trong 24h</p>
        </div>
        <div class="feature">
            <h3>Sản phẩm chính hãng</h3>
            <p>100% từ các thương hiệu lớn</p>
        </div>
        <div class="feature">
            <h3>Hỗ trợ 24/7</h3>
            <p>Tư vấn nhiệt tình, chuyên nghiệp</p>
        </div>
    </section>

    <section class="cta">
        <a href="index.php?controller=product&action=index">Xem sản phẩm ngay</a>
    </section>
    
    <?php require_once 'app/views/partials/footer.php'; ?>
</body>
</html>

