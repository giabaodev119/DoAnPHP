<?php
require_once 'config/config.php';

class Category {
    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    /**
     * Lấy tất cả danh mục từ database
     * @return array Mảng các danh mục hoặc mảng rỗng nếu có lỗi
     */
    public function getAll() {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM categories ORDER BY name ASC");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("[".date('Y-m-d H:i:s')."] Database error in getAll(): " . $e->getMessage());
            return [];
        }
    }

    /**
     * Thêm mới một danh mục
     * @param string $name Tên danh mục
     * @return bool True nếu thành công, False nếu thất bại
     */
    public function create($name) {
        try {
            $stmt = $this->conn->prepare("INSERT INTO categories (name) VALUES (:name)");
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("[".date('Y-m-d H:i:s')."] Database error in create(): " . $e->getMessage());
            return false;
        }
    }

    /**
     * Lấy tất cả danh mục (alias của getAll để tương thích ngược)
     * @deprecated Nên sử dụng getAll() thay thế
     */
    public function getAllCategories() {
        return $this->getAll();
    }
}
?>