<?php
require_once 'config/config.php';

class Cart {
    private $conn;
    
    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    /**
     * Get all cart items for a specific user with product details
     */
    public function getCartItems($user_id) {
        $stmt = $this->conn->prepare("
            SELECT c.id as cart_id, c.quantity, p.id as product_id, p.name as product_name, 
                   p.price, p.image, p.description, cat.name as category_name,
                   (SELECT image_path FROM product_images WHERE product_id = p.id LIMIT 1) as image_path
            FROM cart c
            JOIN products p ON c.product_id = p.id
            LEFT JOIN categories cat ON p.category_id = cat.id
            WHERE c.user_id = :user_id
        ");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Add item to cart, or update quantity if already exists
     */
    public function addToCart($user_id, $product_id, $quantity = 1) {
        // Check if product exists first
        $product = $this->getProductById($product_id);
        if (!$product) {
            return false;
        }

        // Check if product already in cart
        $cartItem = $this->getCartItem($user_id, $product_id);
        
        if ($cartItem) {
            // Update quantity if already in cart
            $newQuantity = $cartItem->quantity + $quantity;
            return $this->updateCartItemQuantity($cartItem->id, $newQuantity);
        } else {
            // Add new cart item
            $stmt = $this->conn->prepare("
                INSERT INTO cart (user_id, product_id, quantity) 
                VALUES (:user_id, :product_id, :quantity)
            ");
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':product_id', $product_id);
            $stmt->bindParam(':quantity', $quantity);
            return $stmt->execute();
        }
    }

    /**
     * Get a specific cart item
     */
    public function getCartItem($user_id, $product_id) {
        $stmt = $this->conn->prepare("
            SELECT * FROM cart 
            WHERE user_id = :user_id AND product_id = :product_id
        ");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Get a specific cart item by ID
     */
    public function getCartItemById($cart_id) {
        $stmt = $this->conn->prepare("SELECT * FROM cart WHERE id = :id");
        $stmt->bindParam(':id', $cart_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Update cart item quantity
     */
    public function updateCartItemQuantity($cart_id, $quantity) {
        $stmt = $this->conn->prepare("
            UPDATE cart SET quantity = :quantity 
            WHERE id = :cart_id
        ");
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':cart_id', $cart_id);
        return $stmt->execute();
    }

    /**
     * Remove item from cart
     */
    public function removeFromCart($cart_id) {
        $stmt = $this->conn->prepare("DELETE FROM cart WHERE id = :cart_id");
        $stmt->bindParam(':cart_id', $cart_id);
        return $stmt->execute();
    }

    /**
     * Clear all items from a user's cart
     */
    public function clearCart($user_id) {
        $stmt = $this->conn->prepare("DELETE FROM cart WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $user_id);
        return $stmt->execute();
    }

    /**
     * Get total number of items in cart
     */
    public function getCartItemCount($user_id) {
        $stmt = $this->conn->prepare("
            SELECT SUM(quantity) as count 
            FROM cart 
            WHERE user_id = :user_id
        ");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result->count ?? 0;
    }

    /**
     * Calculate cart total
     */
    public function getCartTotal($user_id) {
        $stmt = $this->conn->prepare("
            SELECT SUM(c.quantity * p.price) as total
            FROM cart c
            JOIN products p ON c.product_id = p.id
            WHERE c.user_id = :user_id
        ");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result->total ?? 0;
    }

    /**
     * Helper method to get product by ID
     */
    private function getProductById($product_id) {
        $stmt = $this->conn->prepare("SELECT * FROM products WHERE id = :id");
        $stmt->bindParam(':id', $product_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
}
?>