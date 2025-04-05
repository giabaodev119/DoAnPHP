<?php
require_once 'config/config.php';

class Category
{
    private $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }

    /**
     * Lấy tất cả danh mục từ database
     * @return array Mảng các danh mục hoặc mảng rỗng nếu có lỗi
     */
    public function getAll()
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM categories ORDER BY name ASC");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("[" . date('Y-m-d H:i:s') . "] Database error in getAll(): " . $e->getMessage());
            return [];
        }
    }

    /**
     * Thêm mới một danh mục
     * @param string $name Tên danh mục
     * @return bool True nếu thành công, False nếu thất bại
     */
    public function create($name)
    {
        try {
            $stmt = $this->conn->prepare("INSERT INTO categories (name) VALUES (:name)");
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("[" . date('Y-m-d H:i:s') . "] Database error in create(): " . $e->getMessage());
            return false;
        }
    }

    /**
     * Xóa một danh mục theo ID
     * @param int $id ID của danh mục
     * @return bool True nếu thành công, False nếu thất bại
     */
    public function delete($id)
    {
        try {
            $stmt = $this->conn->prepare("DELETE FROM categories WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("[" . date('Y-m-d H:i:s') . "] Database error in delete(): " . $e->getMessage());
            return false;
        }
    }

    /**
     * Lấy danh mục theo ID
     * @param int $id ID của danh mục
     * @return array|null Mảng thông tin danh mục hoặc null nếu có lỗi
     */
    public function getById($id)
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM categories WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("[" . date('Y-m-d H:i:s') . "] Database error in getById(): " . $e->getMessage());
            return null;
        }
    }

    /**
     * Cập nhật danh mục theo ID
     * @param int $id ID của danh mục
     * @param string $name Tên mới của danh mục
     * @return bool True nếu thành công, False nếu thất bại
     */
    public function update($id, $name)
    {
        try {
            $stmt = $this->conn->prepare("UPDATE categories SET name = :name WHERE id = :id");
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("[" . date('Y-m-d H:i:s') . "] Database error in update(): " . $e->getMessage());
            return false;
        }
    }

    /**
     * Lấy tất cả danh mục (alias của getAll để tương thích ngược)
     * @deprecated Nên sử dụng getAll() thay thế
     */
    public function getAllCategories()
    {
        return $this->getAll();
    }
    public function getTotalCategories()
    {
        try {
            $stmt = $this->conn->prepare("SELECT COUNT(*) FROM categories");
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("[" . date('Y-m-d H:i:s') . "] Database error in getTotalCategories(): " . $e->getMessage());
            return 0;
        }
    }

    public function getCategoriesByPage($limit, $offset)
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM categories ORDER BY name ASC LIMIT ? OFFSET ?");
            $stmt->bindParam(1, $limit, PDO::PARAM_INT);
            $stmt->bindParam(2, $offset, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("[" . date('Y-m-d H:i:s') . "] Database error in getCategoriesByPage(): " . $e->getMessage());
            return [];
        }
    }
}
