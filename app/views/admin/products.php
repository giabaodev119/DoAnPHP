<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Admin - Quản lý sản phẩm</title>
    <link rel="stylesheet" href="public/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
</head>
<body>

<div class="admin-container">

    <h2>Quản lý sản phẩm</h2>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert-success">✅ Thêm sản phẩm thành công!</div>
    <?php endif; ?>

    <!-- Form thêm sản phẩm -->
    <h3>➕ Thêm sản phẩm mới</h3>
    <form class="admin-form" action="" method="post" enctype="multipart/form-data">
        <label for="name">Tên sản phẩm:</label>
        <input type="text" name="name" id="name" required>

        <label for="price">Giá:</label>
        <input type="number" name="price" id="price" required>

        <label for="description">Mô tả:</label>
        <textarea name="description" id="description" rows="3"></textarea>

        <label for="category">Danh mục:</label>
        <select name="category_id" id="category" required>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>"><?= $cat['name'] ?></option>
            <?php endforeach; ?>
        </select>

        <label for="images">Hình ảnh (nhiều):</label>
        <input type="file" name="images[]" id="images" multiple>

        <button type="submit">Thêm sản phẩm</button>
    </form>

    <hr>

    <!-- Danh sách sản phẩm -->
    <h3>📦 Danh sách sản phẩm</h3>
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên</th>
                <th>Giá</th>
                <th>Danh mục</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($products)) : ?>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?= $product['id'] ?></td>
                        <td><?= htmlspecialchars($product['name']) ?></td>
                        <td><?= number_format($product['price'], 0, ',', '.') ?> đ</td>
                        <td><?= $product['category_id'] ?></td>
                        <td>
                            <a href="#">✏️ Sửa</a> | 
                            <a href="#" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')">🗑️ Xóa</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="5" style="text-align: center;">Chưa có sản phẩm nào</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</div>
</body>
</html>
