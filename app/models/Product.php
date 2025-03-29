<?php
require_once 'config/config.php';

class Product {
    private $conn;
    
    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    public function getAllProducts() {
        $stmt = $this->conn->prepare("SELECT * FROM products");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProductById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM products WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getFeaturedProducts() {
        $stmt = $this->conn->prepare("SELECT * FROM products WHERE featured = 1");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($name, $price, $description, $category_id) {
        $stmt = $this->conn->prepare("INSERT INTO products (name, price, description, category_id) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $price, $description, $category_id]);
        return $this->conn->lastInsertId();
    }
    
    public function addImage($product_id, $image_path) {
        $stmt = $this->conn->prepare("INSERT INTO product_images (product_id, image_path) VALUES (?, ?)");
        $stmt->execute([$product_id, $image_path]);
    }
    public function searchProducts($keyword, $category)
    {
        $sql = "SELECT * FROM products WHERE name LIKE :keyword";
        if (!empty($category)) {
            $sql .= " AND category_id = :category";
        }
    
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':keyword', "%$keyword%", PDO::PARAM_STR);
        if (!empty($category)) {
            $stmt->bindValue(':category', $category, PDO::PARAM_INT);
        }
        $stmt->execute();
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
    
    
    
    
}
?>
