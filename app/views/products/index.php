<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh sách sản phẩm</title>
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>

<h1 style="text-align: center;">Danh sách sản phẩm</h1>

<!-- Form tìm kiếm -->
<form action="index.php" method="GET" style="text-align: center; margin-bottom: 20px;">
    <input type="hidden" name="controller" value="product">
    <input type="hidden" name="action" value="index">
    
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

<!-- Danh sách sản phẩm -->
<div class="product-grid">
    <?php if (!empty($products)) : ?>
        <?php foreach ($products as $product) : ?>
            <div class="product-card">
                <a href="index.php?controller=product&action=detail&id=<?= $product['id'] ?>">
                <?php
                    $imagePath = !empty($product['image']) && file_exists("public/images/" . $product['image']) ? 
                                 "public/images/" . $product['image'] : "public/images/default.jpg";
                ?>
                    <img src="<?= $imagePath ?>" alt="<?= $product['name'] ?>">

                    <div class="product-name"><?= $product['name'] ?></div>
                    <div class="price"><?= number_format($product['price'], 0, ',', '.') ?> đ</div>
                </a>
            </div>
        <?php endforeach; ?>
    <?php else : ?>
        <p style="text-align: center;">Không tìm thấy sản phẩm nào.</p>
    <?php endif; ?>
</div>

</body>
</html>
