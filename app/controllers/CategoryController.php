<?php
require_once 'app/models/Category.php';

class CategoryController
{
    public function index()
    {
        // Lấy danh sách danh mục
        $categoryModel = new Category();
        $categories = $categoryModel->getAll();

        // Truyền dữ liệu vào view
        require_once 'app/views/admin/categories/index.php';
    }

    public function create()
    {
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');

            if (!empty($name)) {
                $category = new Category();
                if ($category->create($name)) {
                    header("Location: index.php?controller=admin&action=categories&success=1");
                    exit;
                } else {
                    $error = "Lỗi khi thêm danh mục. Vui lòng thử lại.";
                }
            } else {
                $error = "Vui lòng nhập tên danh mục.";
            }
        }

        // Truyền lỗi (nếu có) vào view
        require_once 'app/views/admin/categories/create.php';
    }

    public function delete($id)
    {
        $categoryModel = new Category();

        if ($categoryModel->delete($id)) {
            header("Location: index.php?controller=admin&action=categories&success=1");
            exit;
        } else {
            header("Location: index.php?controller=admin&action=categories&error=1");
            exit;
        }
    }

    public function edit($id)
    {
        $categoryModel = new Category();
        $category = $categoryModel->getById($id);
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');

            if (!empty($name)) {
                if ($categoryModel->update($id, $name)) {
                    header("Location: index.php?controller=admin&action=categories&success=1");
                    exit;
                } else {
                    $error = "Lỗi khi cập nhật danh mục. Vui lòng thử lại.";
                }
            } else {
                $error = "Vui lòng nhập tên danh mục.";
            }
        }

        // Truyền dữ liệu vào view
        require_once 'app/views/admin/categories/edit.php';
    }
}
