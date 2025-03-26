<h2>Quản lý danh mục</h2>
<a href="index.php?controller=category&action=create">➕ Thêm danh mục</a>
<table border="1" cellpadding="10">
    <tr>
        <th>ID</th><th>Tên danh mục</th>
    </tr>
    <?php foreach ($categories as $cat): ?>
    <tr>
        <td><?= $cat['id'] ?></td>
        <td><?= $cat['name'] ?></td>
    </tr>
    <?php endforeach; ?>
</table>
