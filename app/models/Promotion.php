<?php
require_once 'config/config.php';

class Promotion
{
    private $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }

    // Lấy tất cả chương trình khuyến mãi
    public function getAllPromotions()
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM promotions ORDER BY id DESC");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi lấy danh sách khuyến mãi: " . $e->getMessage());
            return [];
        }
    }

    // Phân trang chương trình khuyến mãi
    public function getPromotionsByPage($limit, $offset)
    {
        try {
            $stmt = $this->conn->prepare("
                SELECT * FROM promotions 
                ORDER BY id DESC 
                LIMIT :limit OFFSET :offset
            ");
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi phân trang khuyến mãi: " . $e->getMessage());
            return [];
        }
    }

    // Lấy tổng số chương trình khuyến mãi
    public function getTotalPromotions()
    {
        try {
            $stmt = $this->conn->prepare("SELECT COUNT(*) FROM promotions");
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Lỗi đếm khuyến mãi: " . $e->getMessage());
            return 0;
        }
    }

    // Lấy thông tin chi tiết một chương trình khuyến mãi
    public function getPromotionById($id)
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM promotions WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi lấy thông tin khuyến mãi: " . $e->getMessage());
            return false;
        }
    }

    // Thêm chương trình khuyến mãi mới
    public function create($data)
    {
        try {
            $this->conn->beginTransaction();

            $stmt = $this->conn->prepare("
                INSERT INTO promotions (
                    name, description, discount_type, discount_value, 
                    start_date, end_date, status, target_type, created_at
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())
            ");
            $stmt->execute([
                $data['name'],
                $data['description'],
                $data['discount_type'],
                $data['discount_value'],
                $data['start_date'],
                $data['end_date'],
                $data['status'],
                $data['target_type']
            ]);

            $promotionId = $this->conn->lastInsertId();

            // Xử lý các sản phẩm/danh mục được áp dụng
            if ($data['target_type'] == 'product' && !empty($data['product_ids'])) {
                $stmtItems = $this->conn->prepare("
                    INSERT INTO promotion_products (promotion_id, product_id) 
                    VALUES (?, ?)
                ");
                foreach ($data['product_ids'] as $productId) {
                    $stmtItems->execute([$promotionId, $productId]);
                }
            } elseif ($data['target_type'] == 'category' && !empty($data['category_ids'])) {
                $stmtItems = $this->conn->prepare("
                    INSERT INTO promotion_categories (promotion_id, category_id) 
                    VALUES (?, ?)
                ");
                foreach ($data['category_ids'] as $categoryId) {
                    $stmtItems->execute([$promotionId, $categoryId]);
                }
            }

            $this->conn->commit();
            return $promotionId;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("Lỗi thêm khuyến mãi: " . $e->getMessage());
            return false;
        }
    }

    // Cập nhật chương trình khuyến mãi
    public function update($id, $data)
    {
        try {
            $this->conn->beginTransaction();

            $stmt = $this->conn->prepare("
                UPDATE promotions SET 
                    name = ?, description = ?, discount_type = ?, 
                    discount_value = ?, start_date = ?, end_date = ?, 
                    status = ?, target_type = ?, updated_at = NOW()
                WHERE id = ?
            ");
            $stmt->execute([
                $data['name'],
                $data['description'],
                $data['discount_type'],
                $data['discount_value'],
                $data['start_date'],
                $data['end_date'],
                $data['status'],
                $data['target_type'],
                $id
            ]);

            // Xóa các liên kết cũ
            $this->deletePromotionItems($id);

            // Thêm lại liên kết mới
            if ($data['target_type'] == 'product' && !empty($data['product_ids'])) {
                $stmtItems = $this->conn->prepare("
                    INSERT INTO promotion_products (promotion_id, product_id) 
                    VALUES (?, ?)
                ");
                foreach ($data['product_ids'] as $productId) {
                    $stmtItems->execute([$id, $productId]);
                }
            } elseif ($data['target_type'] == 'category' && !empty($data['category_ids'])) {
                $stmtItems = $this->conn->prepare("
                    INSERT INTO promotion_categories (promotion_id, category_id) 
                    VALUES (?, ?)
                ");
                foreach ($data['category_ids'] as $categoryId) {
                    $stmtItems->execute([$id, $categoryId]);
                }
            }

            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("Lỗi cập nhật khuyến mãi: " . $e->getMessage());
            return false;
        }
    }

    // Xóa các liên kết của chương trình khuyến mãi
    private function deletePromotionItems($promotionId)
    {
        try {
            $stmt = $this->conn->prepare("DELETE FROM promotion_products WHERE promotion_id = ?");
            $stmt->execute([$promotionId]);

            $stmt = $this->conn->prepare("DELETE FROM promotion_categories WHERE promotion_id = ?");
            $stmt->execute([$promotionId]);

            return true;
        } catch (PDOException $e) {
            error_log("Lỗi xóa liên kết khuyến mãi: " . $e->getMessage());
            return false;
        }
    }

    // Xóa chương trình khuyến mãi
    public function delete($id)
    {
        try {
            $this->conn->beginTransaction();

            // Xóa các liên kết
            $this->deletePromotionItems($id);

            // Xóa chương trình khuyến mãi
            $stmt = $this->conn->prepare("DELETE FROM promotions WHERE id = ?");
            $stmt->execute([$id]);

            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("Lỗi xóa khuyến mãi: " . $e->getMessage());
            return false;
        }
    }

    // Lấy sản phẩm áp dụng khuyến mãi
    public function getPromotionProducts($promotionId)
    {
        try {
            $stmt = $this->conn->prepare("
                SELECT p.* FROM products p
                JOIN promotion_products pp ON p.id = pp.product_id
                WHERE pp.promotion_id = ?
            ");
            $stmt->execute([$promotionId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi lấy sản phẩm khuyến mãi: " . $e->getMessage());
            return [];
        }
    }

    // Lấy danh mục áp dụng khuyến mãi
    public function getPromotionCategories($promotionId)
    {
        try {
            $stmt = $this->conn->prepare("
                SELECT c.* FROM categories c
                JOIN promotion_categories pc ON c.id = pc.category_id
                WHERE pc.promotion_id = ?
            ");
            $stmt->execute([$promotionId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi lấy danh mục khuyến mãi: " . $e->getMessage());
            return [];
        }
    }

    // Áp dụng khuyến mãi vào sản phẩm
    public function applyPromotionsToProducts()
    {
        try {
            // Cập nhật giá khuyến mãi về null cho tất cả sản phẩm
            $resetStmt = $this->conn->prepare("UPDATE products SET discount_price = NULL");
            $resetStmt->execute();

            // Lấy các chương trình khuyến mãi đang hoạt động
            $now = date('Y-m-d H:i:s');
            $stmt = $this->conn->prepare("
                SELECT * FROM promotions 
                WHERE status = 'active'
                AND (start_date IS NULL OR start_date <= ?)
                AND (end_date IS NULL OR end_date >= ?)
            ");
            $stmt->execute([$now, $now]);
            $promotions = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($promotions as $promotion) {
                if ($promotion['target_type'] == 'product') {
                    // Áp dụng cho từng sản phẩm
                    $productStmt = $this->conn->prepare("
                        SELECT p.id, p.price FROM products p
                        JOIN promotion_products pp ON p.id = pp.product_id
                        WHERE pp.promotion_id = ?
                    ");
                    $productStmt->execute([$promotion['id']]);
                    $products = $productStmt->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($products as $product) {
                        $discountPrice = $this->calculateDiscountPrice(
                            $product['price'],
                            $promotion['discount_type'],
                            $promotion['discount_value']
                        );

                        $updateStmt = $this->conn->prepare("
                            UPDATE products SET discount_price = ? WHERE id = ?
                        ");
                        $updateStmt->execute([$discountPrice, $product['id']]);
                    }
                } elseif ($promotion['target_type'] == 'category') {
                    // Áp dụng cho sản phẩm thuộc danh mục
                    $categoryStmt = $this->conn->prepare("
                        SELECT p.id, p.price FROM products p
                        JOIN promotion_categories pc ON p.category_id = pc.category_id
                        WHERE pc.promotion_id = ?
                    ");
                    $categoryStmt->execute([$promotion['id']]);
                    $products = $categoryStmt->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($products as $product) {
                        $discountPrice = $this->calculateDiscountPrice(
                            $product['price'],
                            $promotion['discount_type'],
                            $promotion['discount_value']
                        );

                        $updateStmt = $this->conn->prepare("
                            UPDATE products SET discount_price = ? WHERE id = ?
                        ");
                        $updateStmt->execute([$discountPrice, $product['id']]);
                    }
                }
            }

            return true;
        } catch (PDOException $e) {
            error_log("Lỗi áp dụng khuyến mãi: " . $e->getMessage());
            return false;
        }
    }

    // Tính giá sau khuyến mãi
    private function calculateDiscountPrice($originalPrice, $discountType, $discountValue)
    {
        if ($discountType == 'percentage') {
            $discountAmount = $originalPrice * ($discountValue / 100);
            return round($originalPrice - $discountAmount);
        } else { // fixed
            return max(0, $originalPrice - $discountValue);
        }
    }
}
