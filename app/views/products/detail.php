<!-- filepath: c:\xampp\htdocs\DoAnPHP\app\views\products\detail.php -->
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($product['name']) ?> - Chi tiết sản phẩm</title>
    <link rel="stylesheet" href="public/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .product-thumbnail {
            cursor: pointer;
            border: 2px solid transparent;
            width: 80px;
            height: 80px;
            object-fit: cover;
            margin: 5px;
            transition: border-color 0.2s;
        }

        .product-thumbnail.active {
            border-color: #0d6efd;
        }

        #mainImage {
            width: 100%;
            height: 400px;
            object-fit: contain;
        }

        .thumbnail-container {
            display: flex;
            flex-wrap: wrap;
            margin-top: 15px;
        }

        .card {
            transition: transform 0.3s;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .card-img-top {
            padding: 10px;
        }
    </style>
</head>

<body>
    <?php include 'app/views/partials/header.php'; ?>

    <div class="container mt-5">
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?= $_SESSION['error']; ?>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Trang chủ</a></li>
                <li class="breadcrumb-item">
                    <a href="index.php?controller=product&action=category&id=<?= $product['category_id'] ?>">
                        <?= htmlspecialchars($product['category_name']) ?>
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($product['name']) ?></li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-md-6">
                <!-- Ảnh chính -->
                <div class="main-image-container">
                    <?php
                    // Lấy ảnh đầu tiên làm ảnh chính nếu có
                    $mainImage = !empty($images) ? 'public/images/' . $images[0]['image_path'] : 'public/images/no-image.jpg';
                    ?>
                    <img id="mainImage" src="<?= htmlspecialchars($mainImage) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="img-fluid">
                </div>

                <!-- Các ảnh nhỏ -->
                <div class="thumbnail-container">
                    <?php foreach ($images as $index => $image): ?>
                        <img
                            src="<?= htmlspecialchars('public/images/' . $image['image_path']) ?>"
                            class="product-thumbnail <?= $index === 0 ? 'active' : '' ?>"
                            alt="Thumbnail <?= $index + 1 ?>"
                            onclick="changeMainImage('<?= htmlspecialchars('public/images/' . $image['image_path']) ?>', this)">
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="col-md-6">
                <h1><?= htmlspecialchars($product['name']) ?></h1>

                <!-- Hiển thị giảm giá nếu có -->
                <?php if (!empty($product['discount_price']) && $product['discount_price'] < $product['price']): ?>
                    <div class="mb-2">
                        <span class="text-decoration-line-through text-muted fs-5"><?= number_format($product['price'], 0, ',', '.') ?> đ</span>
                        <span class="text-danger fw-bold fs-4 ms-2"><?= number_format($product['discount_price'], 0, ',', '.') ?> đ</span>
                        <?php
                        $discountPercent = round((($product['price'] - $product['discount_price']) / $product['price']) * 100);
                        ?>
                        <span class="badge bg-danger ms-2">-<?= $discountPercent ?>%</span>
                    </div>
                <?php else: ?>
                    <p class="text-danger fw-bold fs-4"><?= number_format($product['price'], 0, ',', '.') ?> đ</p>
                <?php endif; ?>

                <div class="mb-3">
                    <h4>Thông tin sản phẩm</h4>
                    <?php
                    // Get the product description
                    $description = $product['description'];
                    $shortDesc = mb_substr($description, 0, 150); // Get first 150 characters
                    $isLongDesc = mb_strlen($description) > 150;
                    ?>

                    <p id="shortDescription">
                        <?= nl2br(htmlspecialchars($shortDesc)) ?>
                        <?php if ($isLongDesc): ?>
                            <span>...</span>
                            <a href="javascript:void(0)" id="readMoreBtn" class="text-primary" onclick="toggleDescription()">Xem thêm</a>
                        <?php endif; ?>
                    </p>

                    <?php if ($isLongDesc): ?>
                        <p id="fullDescription" style="display: none;">
                            <?= nl2br(htmlspecialchars($description)) ?>
                            <a href="javascript:void(0)" class="text-primary" onclick="toggleDescription()">Thu gọn</a>
                        </p>
                    <?php endif; ?>
                </div>

                <!-- Hiển thị thông tin về size và số lượng -->
                <div class="mb-3">
                    <h4>Kích cỡ:</h4>
                    <?php
                    // Lấy thông tin về các size của sản phẩm
                    $productModel = new Product();
                    $sizes = $productModel->getProductSizes($product['id']);

                    if (!empty($sizes)):
                    ?>
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <?php foreach ($sizes as $size): ?>
                                <div class="size-box border p-2 text-center" style="min-width: 80px;">
                                    <div class="fw-bold"><?= htmlspecialchars($size['size']) ?></div>
                                    <small class="text-muted">Còn <?= htmlspecialchars($size['stock']) ?> sản phẩm</small>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">Không có thông tin về kích cỡ.</p>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <h4>Danh mục:</h4>
                    <p><?= htmlspecialchars($product['category_name']) ?></p>
                </div>

                <form action="index.php?controller=product&action=addToCart&id=<?= $product['id'] ?>" method="post" class="mb-3">
                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">

                    <!-- Thêm chọn size nếu có thông tin size -->
                    <?php if (!empty($sizes)): ?>
                        <div class="mb-3">
                            <label for="size" class="form-label">Chọn kích cỡ:</label>
                            <select class="form-select" id="size" name="size" required style="width: 150px;">
                                <option value="">-- Chọn size --</option>
                                <?php foreach ($sizes as $size): ?>
                                    <?php if ($size['stock'] > 0): ?>
                                        <option value="<?= htmlspecialchars($size['size']) ?>"><?= htmlspecialchars($size['size']) ?> (<?= $size['stock'] ?>)</option>
                                    <?php else: ?>
                                        <option value="<?= htmlspecialchars($size['size']) ?>" disabled><?= htmlspecialchars($size['size']) ?> (Hết hàng)</option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>

                    <div class="mb-3">
                        <label for="quantity" class="form-label">Số lượng:</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" value="1" min="1" style="width: 100px;">
                    </div>
                    <button type="submit" class="btn btn-primary">Thêm vào giỏ hàng</button>
                </form>
            </div>
        </div>

        <!-- Sản phẩm liên quan -->
        <div class="related-products mt-5">
            <h3>Sản phẩm liên quan</h3>

            <?php if (!empty($relatedProducts)): ?>
                <div class="row row-cols-1 row-cols-md-4 g-4 mt-2">
                    <?php foreach ($relatedProducts as $relatedProduct): ?>
                        <div class="col">
                            <div class="card h-100">
                                <!-- Ảnh sản phẩm -->
                                <?php if (!empty($relatedProduct['image_path'])): ?>
                                    <img src="<?= htmlspecialchars('public/images/' . $relatedProduct['image_path']) ?>"
                                        class="card-img-top" alt="<?= htmlspecialchars($relatedProduct['name']) ?>"
                                        style="height: 200px; object-fit: contain;">
                                <?php else: ?>
                                    <img src="public/images/no-image.jpg" class="card-img-top"
                                        alt="No image" style="height: 200px; object-fit: contain;">
                                <?php endif; ?>

                                <div class="card-body">
                                    <h5 class="card-title"><?= htmlspecialchars($relatedProduct['name']) ?></h5>
                                    <p class="card-text text-danger fw-bold">
                                        <?= number_format($relatedProduct['price'], 0, ',', '.') ?> đ
                                    </p>
                                    <a href="index.php?controller=product&action=detail&id=<?= $relatedProduct['id'] ?>"
                                        class="btn btn-primary">Xem chi tiết</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-muted">Không có sản phẩm liên quan.</p>
            <?php endif; ?>
        </div>
    </div>

    <?php require_once 'app/views/partials/footer.php'; ?>

    <script>
        function changeMainImage(imageSrc, thumbnail) {
            // Thay đổi ảnh chính
            document.getElementById('mainImage').src = imageSrc;

            // Cập nhật active state cho thumbnails
            const thumbnails = document.querySelectorAll('.product-thumbnail');
            thumbnails.forEach(item => {
                item.classList.remove('active');
            });
            thumbnail.classList.add('active');
        }

        function toggleDescription() {
            const shortDesc = document.getElementById('shortDescription');
            const fullDesc = document.getElementById('fullDescription');

            if (shortDesc.style.display === 'none') {
                shortDesc.style.display = 'block';
                fullDesc.style.display = 'none';
            } else {
                shortDesc.style.display = 'none';
                fullDesc.style.display = 'block';
            }
        }
    </script>
</body>

</html>