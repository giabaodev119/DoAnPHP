<h2>Thêm danh mục mới</h2>
<?php if (isset($_GET['success'])): ?>
    <p style="color: green;">Đã thêm danh mục thành công!</p>
<?php endif; ?>
<form method="post" action="">
    <label for="name">Tên danh mục:</label><br>
    <input type="text" name="name" required><br><br>
    <button type="submit">Thêm</button>
</form>
