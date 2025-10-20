<?php
class Product extends Model {
    public function __construct() {
        parent::__construct();
    }

    public function getProducts() {
        $this->db->query("SELECT 
                        products.product_id,
                        products.seller_id,
                        products.category_id,
                        products.product_name,
                        products.description,
                        products.price,
                        products.carbon_footprint,
                        products.created_at,
                        COALESCE(products.image_url, '') as image_url,
                        users.user_id,
                        users.first_name,
                        users.last_name,
                        users.email
                        FROM products
                        INNER JOIN users
                        ON products.seller_id = users.user_id
                        ORDER BY products.created_at DESC
                        ");

        $results = $this->db->resultSet();

        return $results;
    }

    public function addProduct($data){
        $this->db->query('INSERT INTO products (product_name, image_url, seller_id, category_id, description, price, carbon_footprint) VALUES(:product_name, :image_url, :seller_id, :category_id, :description, :price, :carbon_footprint)');
        // Bind values
        $this->db->bind(':product_name', $data['product_name']);
        $this->db->bind(':image_url', $data['image_url'] ?? '');
        $this->db->bind(':seller_id', $data['seller_id']);
        $this->db->bind(':category_id', $data['category_id']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':carbon_footprint', $data['carbon_footprint']);

        // Execute
        if($this->db->execute()){
            return true;
        } else {
            return false;
        }
    }

    public function getProductById($id){
        $this->db->query('SELECT * FROM products WHERE product_id = :id');
        $this->db->bind(':id', $id);

        $row = $this->db->single();

        return $row;
    }

    public function updateProduct($data){
        $this->db->query('UPDATE products SET product_name = :product_name, image_url = :image_url, description = :description, price = :price, carbon_footprint = :carbon_footprint, category_id = :category_id WHERE product_id = :id');
        // Bind values
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':product_name', $data['product_name']);
        $this->db->bind(':image_url', $data['image_url'] ?? '');
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':carbon_footprint', $data['carbon_footprint']);
        $this->db->bind(':category_id', $data['category_id']);

        // Execute
        if($this->db->execute()){
            return true;
        } else {
            return false;
        }
    }

    public function deleteProduct($id){
        $this->db->query('DELETE FROM products WHERE product_id = :id');
        // Bind values
        $this->db->bind(':id', $id);

        // Execute
        if($this->db->execute()){
            return true;
        } else {
            return false;
        }
    }

    // Get all products for a specific seller
    public function getSellerProducts($sellerId) {
        $this->db->query("SELECT * FROM products WHERE seller_id = :seller_id ORDER BY created_at DESC");
        $this->db->bind(':seller_id', $sellerId);
        return $this->db->resultSet();
    }

    // Get seller's product count
    public function getSellerProductCount($sellerId) {
        $this->db->query("SELECT COUNT(*) as count FROM products WHERE seller_id = :seller_id");
        $this->db->bind(':seller_id', $sellerId);
        $result = $this->db->single();
        return $result->count;
    }

    // Get seller's average carbon footprint
    public function getSellerAvgCarbonFootprint($sellerId) {
        $this->db->query("SELECT AVG(carbon_footprint) as avg_carbon FROM products WHERE seller_id = :seller_id");
        $this->db->bind(':seller_id', $sellerId);
        $result = $this->db->single();
        return $result->avg_carbon ?? 0;
    }

    // Get total carbon footprint of seller's sold products
    public function getSellerTotalCarbonSold($sellerId) {
        $this->db->query("
            SELECT SUM(oi.carbon_footprint * oi.quantity) as total_carbon
            FROM order_items oi
            INNER JOIN products p ON oi.product_id = p.product_id
            WHERE p.seller_id = :seller_id
        ");
        $this->db->bind(':seller_id', $sellerId);
        $result = $this->db->single();
        return $result->total_carbon ?? 0;
    }

    // Check if product belongs to seller
    public function isProductBySeller($productId, $sellerId) {
        $this->db->query("SELECT * FROM products WHERE product_id = :product_id AND seller_id = :seller_id");
        $this->db->bind(':product_id', $productId);
        $this->db->bind(':seller_id', $sellerId);
        $result = $this->db->single();
        return $result ? true : false;
    }
}
