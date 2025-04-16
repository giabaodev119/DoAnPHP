<?php
require_once 'config/config.php';

class Product
{
    private $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }

    // Lấy tất cả sản phẩm kèm tên danh mục
    public function getAllProducts()
    {
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

    public function getProductById($id)
    {
        try {
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

            // Thêm log để debug
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$result) {
                error_log("Không tìm thấy sản phẩm với ID: $id");
            }
            return $result;
        } catch (PDOException $e) {
            error_log("Database error in getProductById(): " . $e->getMessage());
            return false;
        }
    }

    public function getFeaturedProducts()
    {
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



    public function addImage($product_id, $image_path)
    {
        $stmt = $this->conn->prepare("INSERT INTO product_images (product_id, image_path) VALUES (?, ?)");
        $stmt->execute([$product_id, $image_path]);
    }

    public function searchProducts($keyword, $category, $limit = null, $offset = null)
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

        // Add ORDER BY for consistent pagination
        $sql .= " ORDER BY p.id DESC";

        // Add LIMIT and OFFSET for pagination
        if ($limit !== null) {
            $sql .= " LIMIT :limit OFFSET :offset";
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':keyword', "%$keyword%", PDO::PARAM_STR);
        if (!empty($category)) {
            $stmt->bindValue(':category', $category, PDO::PARAM_INT);
        }

        if ($limit !== null) {
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Add a method to count total search results for pagination
    public function countSearchResults($keyword, $category)
    {
        $sql = "
        SELECT COUNT(*) as total
        FROM products p
        WHERE p.name LIKE :keyword
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
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$result['total'];
    }

    public function update($id, $name, $price, $discount_price, $description, $category_id, $featured)
    {
        // Nếu không có discount_price hoặc discount_price bằng 0, đặt bằng price
        if (!isset($discount_price) || empty($discount_price)) {
            $discount_price = $price;
        }

        $stmt = $this->conn->prepare("UPDATE products SET name = ?, price = ?, discount_price = ?, description = ?, category_id = ?, featured = ? WHERE id = ?");
        return $stmt->execute([$name, $price, $discount_price, $description, $category_id, $featured, $id]);
    }
    /**
     * Tạo sản phẩm mới
     * @param array $data Dữ liệu sản phẩm (name, price, discount_price, description, category_id, featured)
     * @return int|bool ID của sản phẩm mới hoặc false nếu thất bại
     */
    public function create($data)
    {
        try {
            $stmt = $this->conn->prepare("
            INSERT INTO products 
            (name, price, discount_price, description, category_id, featured) 
            VALUES 
            (?, ?, ?, ?, ?, ?)
        ");

            $result = $stmt->execute([
                $data['name'],
                $data['price'],
                $data['discount_price'],
                $data['description'],
                $data['category_id'],
                $data['featured']
            ]);

            if (!$result) {
                // Lấy thông tin lỗi SQL
                $errorInfo = $stmt->errorInfo();
                error_log("SQL Error in create(): " . implode(", ", $errorInfo));
                throw new Exception("Lỗi SQL: " . $errorInfo[2]);
            }

            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            error_log("Database error in create(): " . $e->getMessage());
            throw new Exception("Lỗi database: " . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $this->conn->beginTransaction();

            // 1. Xóa sizes của sản phẩm
            $stmt = $this->conn->prepare("DELETE FROM product_sizes WHERE product_id = ?");
            $stmt->execute([$id]);

            // 2. Xóa hình ảnh của sản phẩm
            $stmt = $this->conn->prepare("DELETE FROM product_images WHERE product_id = ?");
            $stmt->execute([$id]);

            // 3. Xóa sản phẩm khỏi các giỏ hàng (nếu có)
            $stmt = $this->conn->prepare("DELETE FROM cart WHERE product_id = ?");
            $stmt->execute([$id]);

            // 4. Cuối cùng, xóa sản phẩm
            $stmt = $this->conn->prepare("DELETE FROM products WHERE id = ?");
            $stmt->execute([$id]);

            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("Lỗi xóa sản phẩm: " . $e->getMessage());
            return false;
        }
    }

    public function getProductImages($product_id)
    {
        $stmt = $this->conn->prepare("SELECT image_path FROM product_images WHERE product_id = ?");
        $stmt->execute([$product_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRelatedProducts($category_id, $current_product_id, $limit = 4)
    {
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

    /**
     * Giảm số lượng tồn kho của sản phẩm theo kích cỡ
     * @param int $productId ID của sản phẩm
     * @param int $quantity Số lượng cần giảm
     * @param string|null $size Kích cỡ sản phẩm (nếu có)
     * @return bool True nếu cập nhật thành công, False nếu thất bại
     */
    public function decreaseStock($productId, $quantity, $size = null)
    {
        try {
            $this->conn->beginTransaction();

            if ($size) {
                // Giảm số lượng tồn kho theo kích cỡ
                $stmt = $this->conn->prepare("
                UPDATE product_sizes 
                SET stock = stock - ? 
                WHERE product_id = ? AND size = ? AND stock >= ?
            ");
                $result = $stmt->execute([$quantity, $productId, $size, $quantity]);

                if ($stmt->rowCount() == 0) {
                    // Nếu không có hàng nào được cập nhật, kiểm tra lại xem có đủ tồn kho không
                    $checkStmt = $this->conn->prepare("
                    SELECT stock FROM product_sizes 
                    WHERE product_id = ? AND size = ?
                ");
                    $checkStmt->execute([$productId, $size]);
                    $currentStock = $checkStmt->fetchColumn();

                    if ($currentStock === false) {
                        // Size không tồn tại
                        error_log("Size '$size' không tồn tại cho sản phẩm $productId");
                        $this->conn->rollBack();
                        return false;
                    } elseif ($currentStock < $quantity) {
                        // Không đủ tồn kho
                        error_log("Không đủ tồn kho: Cần $quantity, chỉ có $currentStock cho sản phẩm $productId, size $size");
                        $this->conn->rollBack();
                        return false;
                    }
                }
            } else {
                // Trường hợp không có size
                $stmt = $this->conn->prepare("
                UPDATE products 
                SET stock = stock - ? 
                WHERE id = ? AND stock >= ?
            ");
                $result = $stmt->execute([$quantity, $productId, $quantity]);

                if ($stmt->rowCount() == 0) {
                    $this->conn->rollBack();
                    return false;
                }
            }

            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("Lỗi giảm số lượng tồn kho: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Kiểm tra số lượng tồn kho theo kích cỡ trước khi đặt hàng
     * @param int $productId ID của sản phẩm
     * @param int $quantity Số lượng cần kiểm tra
     * @param string|null $size Kích cỡ sản phẩm (nếu có)
     * @return array|bool Thông tin sản phẩm nếu đủ tồn kho, False nếu không đủ
     */
    public function checkStock($productId, $quantity, $size = null)
    {
        try {
            if ($size) {
                // Kiểm tra tồn kho theo kích cỡ
                $stmt = $this->conn->prepare("
                SELECT ps.*, p.name, p.price, p.discount_price 
                FROM product_sizes ps
                JOIN products p ON ps.product_id = p.id
                WHERE ps.product_id = ? AND ps.size = ?
            ");
                $stmt->execute([$productId, $size]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$result) {
                    error_log("Size '$size' không tồn tại cho sản phẩm $productId");
                    return false;
                }

                if ($result['stock'] < $quantity) {
                    error_log("Không đủ tồn kho: Cần $quantity, chỉ có {$result['stock']} cho sản phẩm $productId, size $size");
                    return false;
                }

                return $result;
            } else {
                // Kiểm tra tổng tồn kho (nếu không có size)
                $stmt = $this->conn->prepare("
                SELECT * FROM products WHERE id = ?
            ");
                $stmt->execute([$productId]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$result) {
                    error_log("Không tìm thấy sản phẩm $productId");
                    return false;
                }

                // Tính tổng tồn kho từ tất cả các size
                $sizeStmt = $this->conn->prepare("
                SELECT SUM(stock) as total_stock 
                FROM product_sizes 
                WHERE product_id = ?
            ");
                $sizeStmt->execute([$productId]);
                $totalStock = $sizeStmt->fetchColumn();

                if ($totalStock < $quantity) {
                    error_log("Không đủ tồn kho: Cần $quantity, chỉ có tổng cộng $totalStock cho sản phẩm $productId");
                    return false;
                }

                return $result;
            }
        } catch (PDOException $e) {
            error_log("Lỗi kiểm tra tồn kho: " . $e->getMessage());
            return false;
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
    /**
     * Đếm tổng số sản phẩm
     * @return int Tổng số sản phẩm
     */
    public function getTotalProducts()
    {
        try {
            $stmt = $this->conn->prepare("SELECT COUNT(*) FROM products");
            $stmt->execute();
            return (int)$stmt->fetchColumn();
        } catch (PDOException $e) {
            return 0;
        }
    }

    /**
     * Thêm size và số lượng tồn kho cho sản phẩm
     * @param int $productId ID của sản phẩm
     * @param string $size Kích cỡ (S, M, L, XL, etc.)
     * @param int $stock Số lượng tồn kho
     * @return bool True nếu thành công, False nếu thất bại
     */
    public function addProductSize($productId, $size, $stock)
    {
        try {
            $stmt = $this->conn->prepare("
            INSERT INTO product_sizes 
            (product_id, size, stock) 
            VALUES 
            (?, ?, ?)
        ");

            return $stmt->execute([$productId, $size, $stock]);
        } catch (PDOException $e) {
            error_log("Database error in addProductSize(): " . $e->getMessage());
            return false;
        }
    }

    public function getProductSizes($productId)
    {
        try {
            $stmt = $this->conn->prepare("SELECT size, stock FROM product_sizes WHERE product_id = ? ORDER BY FIELD(size, 'S', 'M', 'L', 'XL', 'XXL')");
            $stmt->execute([$productId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting product sizes: " . $e->getMessage());
            return [];
        }
    }

    public function updateProductSizes($productId, $sizes, $stocks)
    {
        try {
            // Begin transaction
            $this->conn->beginTransaction();

            // Delete existing sizes
            $stmt = $this->conn->prepare("DELETE FROM product_sizes WHERE product_id = ?");
            $stmt->execute([$productId]);

            // Insert new sizes
            $stmt = $this->conn->prepare("INSERT INTO product_sizes (product_id, size, stock) VALUES (?, ?, ?)");
            foreach ($sizes as $i => $size) {
                if (!empty($size) && isset($stocks[$i])) {
                    $stmt->execute([$productId, $size, $stocks[$i]]);
                }
            }

            // Commit transaction
            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            // Rollback on error
            $this->conn->rollBack();
            error_log("Error updating product sizes: " . $e->getMessage());
            return false;
        }
    }

    public function beginTransaction()
    {
        return $this->conn->beginTransaction();
    }

    public function commit()
    {
        return $this->conn->commit();
    }

    public function rollback()
    {
        if ($this->conn->inTransaction()) {
            $this->conn->rollBack();
        }
    }

    public function deleteProductSizes($productId)
    {
        $stmt = $this->conn->prepare("DELETE FROM product_sizes WHERE product_id = ?");
        return $stmt->execute([$productId]);
    }

    /**
     * Lấy thông tin size cụ thể của sản phẩm
     * @param int $productId ID của sản phẩm
     * @param string $size Size cần kiểm tra
     * @return array|false Thông tin size hoặc false nếu không tìm thấy
     */
    public function getProductSizeInfo($productId, $size)
    {
        try {
            $stmt = $this->conn->prepare("
                SELECT * FROM product_sizes 
                WHERE product_id = ? AND size = ?
            ");
            $stmt->execute([$productId, $size]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi lấy thông tin size: " . $e->getMessage());
            return false;
        }
    }
    /**
     * Lấy tổng số lượng tồn kho từ tất cả các size của một sản phẩm
     * @param int $productId ID của sản phẩm
     * @return int Tổng số lượng tồn kho
     */
    public function getTotalStockForProduct($productId)
    {
        try {
            $stmt = $this->conn->prepare("
            SELECT SUM(stock) as total_stock 
            FROM product_sizes 
            WHERE product_id = ?
        ");
            $stmt->execute([$productId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total_stock'] ?? 0;
        } catch (PDOException $e) {
            error_log("Lỗi lấy tổng tồn kho: " . $e->getMessage());
            return 0;
        }
    }
}
