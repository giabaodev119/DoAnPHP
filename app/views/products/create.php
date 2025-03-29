<h2>Thêm sản phẩm mới</h2>
<form action="" method="post" enctype="multipart/form-data">
    <label>Tên sản phẩm:</label><br>
    <input type="text" name="name" required><br>

    <label>Giá:</label><br>
    <input type="number" name="price" required><br>

    <label>Mô tả:</label><br>
    <textarea name="description"></textarea><br>

    <label>Danh mục:</label><br>
    <select name="category_id" required>
        <?php foreach ($categories as $cat): ?>
            <option value="<?= $cat['id'] ?>"><?= $cat['name'] ?></option>
        <?php endforeach; ?>
    </select><br>

    <label>Hình ảnh (chọn nhiều):</label><br>
    <input type="file" name="images[]" multiple><br><br>

    <div class="mb-3 form-check">
    <input type="checkbox" class="form-check-input" id="featured" name="featured" value="1">
    <label class="form-check-label" for="featured">🔥 Đánh dấu là mặt hàng yêu thích</label>
    </div>

    <button type="submit">Thêm sản phẩm</button>
</form>
