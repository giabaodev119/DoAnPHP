<?php
require_once 'app/models/Category.php';

class CategoryController {
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $category = new Category();
            if ($category->create($name)) {
                header("Location: index.php?controller=category&action=create&success=1");
                exit;
            }
        }
        require_once 'app/views/categories/create.php';
    }
}
