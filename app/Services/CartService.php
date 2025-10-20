<?php
/**
 * CartService - Advanced cart management with OOP principles
 * Handles all cart operations including calculations
 */
class CartService {
    private $cartModel;
    private $productModel;
    private $db;

    public function __construct() {
        $this->cartModel = new Cart();
        $this->productModel = new Product();
        $this->db = DB::getInstance();
    }

    /**
     * Add product to user's cart
     * @param int $userId
     * @param int $productId
     * @param int $quantity
     * @return array - Response with status and message
     */
    public function addToCart($userId, $productId, $quantity = 1) {
        try {
            // Get user's cart or create one
            $cart = $this->cartModel->getCartByUserId($userId);
            if (!$cart) {
                $cart = $this->cartModel->createCart($userId);
            }

            // Check if product already in cart
            $existingItem = $this->cartModel->getCartItemByProduct($cart->cart_id, $productId);
            
            if ($existingItem) {
                // Update quantity
                $newQuantity = $existingItem->quantity + $quantity;
                $this->cartModel->updateProductInCart($existingItem->cart_item_id, $newQuantity);
            } else {
                // Add new item
                $this->cartModel->addProductToCart($cart->cart_id, $productId, $quantity);
            }

            return [
                'success' => true,
                'message' => 'Product added to cart successfully',
                'cart_id' => $cart->cart_id
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error adding product to cart: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Remove product from cart
     * @param int $cartItemId
     * @return array
     */
    public function removeFromCart($cartItemId) {
        try {
            $this->cartModel->removeProductFromCart($cartItemId);
            return [
                'success' => true,
                'message' => 'Product removed from cart'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error removing product: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get user's cart with calculated totals
     * @param int $userId
     * @return array
     */
    public function getUserCart($userId) {
        try {
            $cart = $this->cartModel->getCartByUserId($userId);
            if (!$cart) {
                return [
                    'items' => [],
                    'total_amount' => 0,
                    'total_carbon' => 0,
                    'item_count' => 0
                ];
            }

            $items = $this->cartModel->getCartItems($cart->cart_id);
            
            return [
                'cart_id' => $cart->cart_id,
                'items' => $items ?? [],
                'total_amount' => $this->calculateTotalAmount($items),
                'total_carbon' => $this->calculateTotalCarbon($items),
                'item_count' => count($items ?? [])
            ];
        } catch (Exception $e) {
            error_log('Cart Error: ' . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Calculate total amount from cart items
     * @param array $items
     * @return float
     */
    private function calculateTotalAmount($items) {
        $total = 0;
        if ($items) {
            foreach ($items as $item) {
                $total += $item->productPrice * $item->quantity;
            }
        }
        return round($total, 2);
    }

    /**
     * Calculate total carbon footprint from cart items
     * @param array $items
     * @return float
     */
    private function calculateTotalCarbon($items) {
        $total = 0;
        if ($items) {
            foreach ($items as $item) {
                $total += $item->carbon_footprint * $item->quantity;
            }
        }
        return round($total, 2);
    }

    /**
     * Update product quantity in cart
     * @param int $cartItemId
     * @param int $quantity
     * @return array
     */
    public function updateQuantity($cartItemId, $quantity) {
        try {
            if ($quantity <= 0) {
                return $this->removeFromCart($cartItemId);
            }
            
            $this->cartModel->updateProductInCart($cartItemId, $quantity);
            return [
                'success' => true,
                'message' => 'Quantity updated'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error updating quantity: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Clear entire cart
     * @param int $userId
     * @return array
     */
    public function clearCart($userId) {
        try {
            $cart = $this->cartModel->getCartByUserId($userId);
            if ($cart) {
                $this->cartModel->clearCart($cart->cart_id);
            }
            return [
                'success' => true,
                'message' => 'Cart cleared'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error clearing cart: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get mini cart info (for header display)
     * @param int $userId
     * @return array
     */
    public function getMiniCart($userId) {
        $cartData = $this->getUserCart($userId);
        return [
            'count' => $cartData['item_count'] ?? 0,
            'total' => $cartData['total_amount'] ?? 0
        ];
    }
}