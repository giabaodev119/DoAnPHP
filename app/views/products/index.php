<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh sách sản phẩm</title>
</head>
<body>
    <h1>Danh sách sản phẩm</h1>
    <ul>
        <?php foreach ($products as $product) : ?>
            <li>
                <a href="index.php?controller=product&action=detail&id=<?= $product['id'] ?>">
                    <?= $product['name'] ?> - <?= $product['price'] ?> VNĐ
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
