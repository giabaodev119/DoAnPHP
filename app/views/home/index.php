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
            <h2>Sản phẩm nổi bật</h2>
            <div class="product-grid">
                <?php foreach ($featuredProducts as $product) : ?>
                    <div class="product-card">
                        <a href="index.php?controller=product&action=detail&id=<?= $product['id'] ?>">
                        <?php
                            $imagePath = !empty($product['image']) && file_exists("public/images/" . $product['image'])? "public/images/" . $product['image'] : "public/images/default.jpg";
                        ?>
                            <img src="<?= $imagePath ?>" alt="<?= $product['name'] ?>">

                            <div class="label-hot">Yêu thích</div>
                            <div class="product-name"><?= $product['name'] ?></div>
                            <div class="discount">7% Giảm</div>
                            <div class="price"><?= number_format($product['price'], 0, ',', '.') ?> đ</div>
                            <div class="sold">Đã bán 1,7k</div>
                        </a>
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




</body>
</html>
