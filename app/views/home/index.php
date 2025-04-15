<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Trang chủ - Shop Công nghệ</title>
    <link rel="stylesheet" href="public/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <style>
    .banner-track {
        display: flex;
        transition: transform 0.5s ease-in-out;
        width: 100%;
    }
</style>
</head>

<body>
<?php
    require_once 'app/views/partials/header.php';
    require_once 'app/models/Banner.php';
$bannerModel = new Banner();
$activeBanners = $bannerModel->getActiveBanners();
?>

<section class="banner" style="height: auto;">
    <?php if (!empty($activeBanners)): ?>
        <div class="banner-slider" style="
            position: relative;
            width: 1000px;
            max-width: none;
            margin: 10px auto 30px;
            overflow: hidden;
            height: 600px;
            border-radius: 8px;">
            <div class="banner-track" style="
                display: flex;
                width: <?= count($activeBanners) * 1000 ?>px;
                transform: translateX(0);
                transition: transform 0.5s ease-in-out;">
                <?php foreach ($activeBanners as $banner): ?>
                    <div class="banner-slide" style="
                        flex: 0 0 1000px;
                        width: 1000px;
                        position: relative;">
                        <img src="public/images/banners/<?php echo $banner['image_path']; ?>" 
                            alt="<?php echo htmlspecialchars($banner['title']); ?>" 
                            style="width: 1000px; height: 600px; object-fit: cover; filter: brightness(60%);">
                        <div class="banner-title"
                            style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
                                color: white; font-size: 24px; font-weight: bold; text-shadow: 2px 2px 4px rgba(0,0,0,0.5);">
                            <?= htmlspecialchars($banner['title']) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

    <!-- Pagination -->
    <div class="banner-pagination" style="
        position: absolute;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        text-align: center;
        z-index: 10;
    ">
        <?php foreach ($activeBanners as $i => $_): ?>
            <span class="dot" style="
                height: 12px;
                width: 12px;
                margin: 0 5px;
                background-color: <?= $i === 0 ? '#fff' : 'rgba(255, 255, 255, 0.5)' ?>;
                border-radius: 50%;
                display: inline-block;
                cursor: pointer;
                transition: background-color 0.3s;
            " data-index="<?= $i ?>"></span>
        <?php endforeach; ?>
    </div>
</div>

        
<script>
    const track = document.querySelector('.banner-track');
    const dots = document.querySelectorAll('.dot');
    const totalSlides = dots.length;
    let currentIndex = 0;

    function showSlide(index) {
        // Thay đổi từ phần trăm sang pixel
        track.style.transform = `translateX(-${index * 1000}px)`;
        dots.forEach((dot, i) => {
            dot.style.backgroundColor = i === index ? '#fff' : 'rgba(255, 255, 255, 0.5)';
        });
        currentIndex = index;
    }

    setInterval(() => {
        const nextIndex = (currentIndex + 1) % totalSlides;
        showSlide(nextIndex);
    }, 3000);

    dots.forEach(dot => {
        dot.addEventListener('click', () => {
            const index = parseInt(dot.getAttribute('data-index'));
            showSlide(index);
        });
    });
</script>
    <?php else: ?>
        <p style="text-align: center;">Chào mừng đến với cửa hàng công nghệ!</p>
    <?php endif; ?>
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

