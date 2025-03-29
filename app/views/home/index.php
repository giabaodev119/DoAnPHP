<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Trang chủ - Shop Công nghệ</title>
    <link rel="stylesheet" href="public/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
</head>

<body>


    <section class="banner">
        Chào mừng đến với cửa hàng công nghệ!
    </section>

<section class="products">
    <ul>
        <section class="products">
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

            <h2>Sản phẩm nổi bật</h2>
            <div class="product-grid">
                <?php foreach ($featuredProducts as $product) : ?>
                    <div class="product-card">
                        <a href="index.php?controller=product&action=detail&id=<?= $product['id'] ?>">
                        <?php
                            $imagePath = !empty($product['image']) && file_exists("public/images/" . $product['image'])? "public/images/" . $product['image'] : "public/images/default.jpg";
                        ?>
                            <img src="<?= $imagePath ?>" alt="<?= $product['name'] ?>">



    <section class="products">
        <ul>
            <section class="products">
                <h2>Sản phẩm nổi bật</h2>
                <div class="product-grid">
                    <?php foreach ($featuredProducts as $product): ?>
                        <div class="product-card">
                            <a href="index.php?controller=product&action=detail&id=<?= $product['id'] ?>">
                                <?php
                                $imagePath = !empty($product['image']) && file_exists("public/images/" . $product['image']) ? "public/images/" . $product['image'] : "public/images/default.jpg";
                                ?>
                                <img src="<?= $imagePath ?>" alt="<?= $product['name'] ?>">

                                <div class="label-hot">Yêu thích</div>
                                <div class="product-name"><?= $product['name'] ?></div>
                                <div class="discount">7% Giảm</div>
                                <div class="price"><?= number_format($product['price'], 0, ',', '.') ?> đ</div>
                                <div class="sold">Đã bán 1,7k</div>
                            </a>
                            <div class="product-actions">
                                    <a href="index.php?controller=product&action=addToCart&id=<?= $product['id'] ?>"
                                        class="btn-add-to-cart">Thêm vào giỏ</a>
                                </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        </ul>
    </section>

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

    