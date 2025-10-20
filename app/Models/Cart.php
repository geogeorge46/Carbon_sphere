<?php
class Cart extends Model {
    public function __construct() {
        parent::__construct();
    }

    public function getCartByUserId($userId) {
        $this->db->query('SELECT * FROM carts WHERE user_id = :user_id');
        $this->db->bind(':user_id', $userId);
        return $this->db->single();
    }

    public function createCart($userId) {
        $this->db->query('INSERT INTO carts (user_id) VALUES (:user_id)');
        $this->db->bind(':user_id', $userId);
        $this->db->execute();
        return $this->getCartByUserId($userId);
    }

    public function getCartItems($cartId) {
        $this->db->query('SELECT *, products.product_name as productName, products.price as productPrice FROM cart_items INNER JOIN products ON cart_items.product_id = products.product_id WHERE cart_id = :cart_id');
        $this->db->bind(':cart_id', $cartId);
        return $this->db->resultSet();
    }

    public function addProductToCart($cartId, $productId, $quantity) {
        $this->db->query('INSERT INTO cart_items (cart_id, product_id, quantity) VALUES (:cart_id, :product_id, :quantity)');
        $this->db->bind(':cart_id', $cartId);
        $this->db->bind(':product_id', $productId);
        $this->db->bind(':quantity', $quantity);
        return $this->db->execute();
    }

    public function updateProductInCart($cartItemId, $quantity) {
        $this->db->query('UPDATE cart_items SET quantity = :quantity WHERE cart_item_id = :cart_item_id');
        $this->db->bind(':quantity', $quantity);
        $this->db->bind(':cart_item_id', $cartItemId);
        return $this->db->execute();
    }

    public function removeProductFromCart($cartItemId) {
        $this->db->query('DELETE FROM cart_items WHERE cart_item_id = :cart_item_id');
        $this->db->bind(':cart_item_id', $cartItemId);
        return $this->db->execute();
    }

    public function getCartItemByProduct($cartId, $productId) {
        $this->db->query('SELECT * FROM cart_items WHERE cart_id = :cart_id AND product_id = :product_id');
        $this->db->bind(':cart_id', $cartId);
        $this->db->bind(':product_id', $productId);
        return $this->db->single();
    }

    public function clearCart($cartId) {
        $this->db->query('DELETE FROM cart_items WHERE cart_id = :cart_id');
        $this->db->bind(':cart_id', $cartId);
        return $this->db->execute();
    }
}
