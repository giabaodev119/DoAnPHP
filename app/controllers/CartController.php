<?php
require_once 'app/models/Cart.php';
require_once 'app/models/Product.php';

class CartController {
    private $cartModel;
    private $productModel;

    public function __construct() {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $this->cartModel = new Cart();
        $this->productModel = new Product();
    }

    /**
     * Display cart contents
     */
    public function index() {
        $data = [];
        
        if ($this->isUserLoggedIn()) {
            $userId = $_SESSION['user_id'];
            $data['cart_items'] = $this->cartModel->getCartItems($userId);
            $data['cart_total'] = $this->cartModel->getCartTotal($userId);
        } else {
            $data['cart_items'] = $this->getSessionCartItems();
            $data['cart_total'] = $this->calculateSessionCartTotal();
        }
        
        // Set view title and page
        $data['title'] = 'Your Shopping Cart';
        
        // Load view
        require_once 'app/views/cart/index.php';
    }

    //update cart item quantity
    public function update() {
        // Get cart item ID and new quantity
        $cartId = isset($_POST['cart_id']) ? (int)$_POST['cart_id'] : 0;
        $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
        
        if (!$cartId || $quantity < 1) {
            $_SESSION['error'] = 'Invalid cart item or quantity';
            header('Location: index.php?controller=cart');
            exit;
        }
        
        if ($this->isUserLoggedIn()) {
            // Update in database for logged-in users
            $userId = $_SESSION['user_id'];
            
            // Verify the cart item belongs to this user before updating
            $cartItem = $this->cartModel->getCartItemById($cartId);
            if ($cartItem && $cartItem->user_id == $userId) {
                $success = $this->cartModel->updateCartItemQuantity($cartId, $quantity);
                if ($success) {
                    $_SESSION['message'] = 'Cart updated successfully';
                } else {
                    $_SESSION['error'] = 'Failed to update cart';
                }
            } else {
                $_SESSION['error'] = 'You do not have permission to update this item';
            }
        } else {
            // Update session cart for guests
            if (isset($_SESSION['cart'])) {
                foreach ($_SESSION['cart'] as &$item) {
                    if ($item['id'] == $cartId) {
                        $item['quantity'] = $quantity;
                        $_SESSION['message'] = 'Cart updated successfully';
                        break;
                    }
                }
            }
        }
        
        // Redirect back to cart
        header('Location: index.php?controller=cart');
        exit;
    }

    /**
     * Remove item from cart
     */
    public function remove() {
        // Get cart item ID
        $cartId = isset($_POST['cart_id']) ? (int)$_POST['cart_id'] : 0;
        
        if (!$cartId) {
            $_SESSION['error'] = 'Invalid cart item';
            header('Location: index.php?controller=cart');
            exit;
        }
        
        if ($this->isUserLoggedIn()) {
            // Remove from database for logged-in users
            $userId = $_SESSION['user_id'];
            
            // Verify the cart item belongs to this user before removing
            $cartItem = $this->cartModel->getCartItemById($cartId);
            if ($cartItem && $cartItem->user_id == $userId) {
                $success = $this->cartModel->removeFromCart($cartId);
                if ($success) {
                    $_SESSION['message'] = 'Item removed from cart';
                } else {
                    $_SESSION['error'] = 'Failed to remove item from cart';
                }
            } else {
                $_SESSION['error'] = 'You do not have permission to remove this item';
            }
        } else {
            // Remove from session cart for guests
            if (isset($_SESSION['cart'])) {
                foreach ($_SESSION['cart'] as $key => $item) {
                    if ($item['id'] == $cartId) {
                        unset($_SESSION['cart'][$key]);
                        // Re-index array
                        $_SESSION['cart'] = array_values($_SESSION['cart']);
                        $_SESSION['message'] = 'Item removed from cart';
                        break;
                    }
                }
            }
        }
        
        // Redirect back to cart
        header('Location: index.php?controller=cart');
        exit;
    }

    /**
     * Clear all items from cart
     */
    public function clear() {
        if ($this->isUserLoggedIn()) {
            // Clear database cart for logged-in users
            $userId = $_SESSION['user_id'];
            $success = $this->cartModel->clearCart($userId);
            
            if ($success) {
                $_SESSION['message'] = 'Cart cleared successfully';
            } else {
                $_SESSION['error'] = 'Failed to clear cart';
            }
        } else {
            // Clear session cart for guests
            $_SESSION['cart'] = [];
            $_SESSION['message'] = 'Cart cleared successfully';
        }
        
        // Redirect back to cart
        header('Location: index.php?controller=cart');
        exit;
    }

    /**
     * Helper method to check if user is logged in
     */
    private function isUserLoggedIn() {
        return isset($_SESSION['user_id']);
    }
    
    /**
     * Helper methods for session-based cart for guest users
     */
    private function getSessionCartItems() {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        
        $cartItems = [];
        foreach ($_SESSION['cart'] as $item) {
            // Convert array to object to match database format
            $cartItem = new stdClass();
            $cartItem->cart_id = $item['id']; // Use product ID as cart ID for session cart
            $cartItem->product_id = $item['id'];
            $cartItem->product_name = $item['name'];
            $cartItem->price = $item['price'];
            $cartItem->quantity = $item['quantity'];
            $cartItem->image = $item['image'] ?? '';
            $cartItem->image_path = $item['image'] ?? '';
            
            $cartItems[] = $cartItem;
        }
        
        return $cartItems;
    }
    
    private function calculateSessionCartTotal() {
        $total = 0;
        if (isset($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $item) {
                $total += $item['price'] * $item['quantity'];
            }
        }
        return $total;
    }
}
?>