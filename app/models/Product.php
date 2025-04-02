<?php
require_once 'config/config.php';

class Product {
    private $conn;
    
    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    // Lấy tất cả sản phẩm kèm tên danh mục
    public function getAllProducts() {
        $stmt = $this->conn->prepare("
            SELECT 
                p.*, 
                c.name AS category_name 
            FROM 
                products p
            LEFT JOIN 
                categories c 
            ON 
                p.category_id = c.id
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProductById($id) {
        $stmt = $this->conn->prepare("
            SELECT 
                p.*, 
                c.name AS category_name 
            FROM 
                products p
            LEFT JOIN 
                categories c 
            ON 
                p.category_id = c.id
            WHERE 
                p.id = :id
        ");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getFeaturedProducts() {
        $stmt = $this->conn->prepare("
            SELECT 
                p.*, 
                c.name AS category_name 
            FROM 
                products p
            LEFT JOIN 
                categories c 
            ON 
                p.category_id = c.id
            WHERE 
                p.featured = 1
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($name, $price, $description, $category_id, $featured) {
        $stmt = $this->conn->prepare("INSERT INTO products (name, price, description, category_id, featured) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $price, $description, $category_id, $featured]);
        return $this->conn->lastInsertId();
    }
    
    public function addImage($product_id, $image_path) {
        $stmt = $this->conn->prepare("INSERT INTO product_images (product_id, image_path) VALUES (?, ?)");
        $stmt->execute([$product_id, $image_path]);
    }
    
    public function searchProducts($keyword, $category)
    {
        $sql = "
            SELECT 
                p.*, 
                c.name AS category_name 
            FROM 
                products p
            LEFT JOIN 
                categories c 
            ON 
                p.category_id = c.id
            WHERE 
                p.name LIKE :keyword
        ";
        if (!empty($category)) {
            $sql .= " AND p.category_id = :category";
        }
    
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':keyword', "%$keyword%", PDO::PARAM_STR);
        if (!empty($category)) {
            $stmt->bindValue(':category', $category, PDO::PARAM_INT);
        }
        $stmt->execute();
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update($id, $name, $price, $description, $category_id, $featured) {
        $stmt = $this->conn->prepare("UPDATE products SET name = ?, price = ?, description = ?, category_id = ?, featured = ? WHERE id = ?");
        return $stmt->execute([$name, $price, $description, $category_id, $featured, $id]);
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM products WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getProductImages($product_id) {
        $stmt = $this->conn->prepare("SELECT image_path FROM product_images WHERE product_id = ?");
        $stmt->execute([$product_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRelatedProducts($category_id, $current_product_id, $limit = 4) {
        // Sử dụng tham số có tên để tránh lỗi với mệnh đề LIMIT
        $stmt = $this->conn->prepare("
            SELECT 
                p.*, 
                c.name AS category_name,
                (SELECT image_path FROM product_images WHERE product_id = p.id LIMIT 1) AS image_path
            FROM 
                products p
            LEFT JOIN 
                categories c 
            ON 
                p.category_id = c.id
            WHERE 
                p.category_id = :category_id AND p.id != :product_id
            LIMIT :limit
        ");
        
        $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
        $stmt->bindParam(':product_id', $current_product_id, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT); // Chỉ định rõ là tham số số nguyên
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
 public function getTotalProducts()
{
    try {
        $stmt = $this->conn->query("SELECT COUNT(*) FROM products");
        return $stmt->fetchColumn();
    } catch (PDOException $e) {
        return 0;
    }
}

public function getProductsByPage($limit, $offset)
{
    try {
        $stmt = $this->conn->prepare("
            SELECT p.*, c.name AS category_name 
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            ORDER BY p.id DESC
            LIMIT ? OFFSET ?
        ");
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->bindValue(2, $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return [];
    }
}
}
?>