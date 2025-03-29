<!-- filepath: c:\xampp\htdocs\DoAnPHP\app\views\products\detail.php -->
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chi tiết sản phẩm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Chi tiết sản phẩm</h2>
        <div class="card">
            <div class="card-body">
                <h3><?= htmlspecialchars($product['name']) ?></h3>
                <p><strong>Giá:</strong> <?= number_format($product['price'], 0, ',', '.') ?> đ</p>
                <p><strong>Mô tả:</strong> <?= htmlspecialchars($product['description']) ?></p>
                <p><strong>Danh mục:</strong> <?= htmlspecialchars($product['category_id']) ?></p>
                <p><strong>Nổi bật:</strong> <?= $product['featured'] ? 'Có' : 'Không' ?></p>
                <h4>Hình ảnh:</h4>
                <div class="d-flex flex-wrap">
                    <?php foreach ($images as $image): ?>
                        <img src="public/images/<?= htmlspecialchars($image['image_path']) ?>" alt="Product Image" class="img-thumbnail" style="width: 150px; margin-right: 10px;">
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <a href="index.php?controller=product&action=index" class="btn btn-secondary mt-3">Quay lại</a>
    </div>
</body>
</html>