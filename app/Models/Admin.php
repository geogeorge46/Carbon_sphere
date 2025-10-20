<?php
class Admin extends Model {
    public function __construct(){
        parent::__construct();
    }

    public function getDashboardMetrics(){
        // total revenue, total carbon, total users, total products
        $this->db->query('SELECT (SELECT COUNT(*) FROM users) as total_users, (SELECT COUNT(*) FROM products) as total_products, (SELECT IFNULL(SUM(total_amount),0) FROM orders) as total_revenue, (SELECT IFNULL(SUM(total_carbon),0) FROM orders) as total_carbon FROM dual');
        return $this->db->single();
    }

    // Carbon emitted per month (last 12 months)
    public function getCarbonByMonth($months = 12){
        $this->db->query("SELECT DATE_FORMAT(created_at, '%Y-%m') as month, SUM(total_carbon) as total_carbon FROM orders GROUP BY month ORDER BY month DESC LIMIT :limit");
        $this->db->bind(':limit', $months);
        return $this->db->resultSet();
    }

    // Revenue per month (last 12 months)
    public function getRevenueByMonth($months = 12){
        $this->db->query("SELECT DATE_FORMAT(created_at, '%Y-%m') as month, SUM(total_amount) as total_revenue FROM orders GROUP BY month ORDER BY month DESC LIMIT :limit");
        $this->db->bind(':limit', $months);
        return $this->db->resultSet();
    }

    // Top products by total carbon footprint (sum of order_items.carbon_footprint)
    public function getTopProductsByCarbon($limit = 10){
        $this->db->query('SELECT p.product_id, p.product_name, IFNULL(SUM(oi.carbon_footprint),0) as total_carbon FROM products p LEFT JOIN order_items oi ON p.product_id = oi.product_id GROUP BY p.product_id ORDER BY total_carbon DESC LIMIT :limit');
        $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }

    // Carbon by category
    public function getCarbonByCategory(){
        $this->db->query('SELECT c.category_name, IFNULL(SUM(oi.carbon_footprint),0) as total_carbon FROM categories c LEFT JOIN products p ON c.category_id = p.category_id LEFT JOIN order_items oi ON p.product_id = oi.product_id GROUP BY c.category_id ORDER BY total_carbon DESC');
        return $this->db->resultSet();
    }

    public function getAllUsers(){
        $this->db->query('SELECT user_id, first_name, last_name, email, role, created_at FROM users ORDER BY created_at DESC');
        return $this->db->resultSet();
    }

    public function getUserById($id){
        $this->db->query('SELECT * FROM users WHERE user_id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function updateUserRole($id, $role){
        $this->db->query('UPDATE users SET role = :role WHERE user_id = :id');
        $this->db->bind(':role', $role);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function deleteUser($id){
        $this->db->query('DELETE FROM users WHERE user_id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function getAllProducts(){
        $this->db->query('SELECT p.*, u.first_name, u.last_name FROM products p LEFT JOIN users u ON p.seller_id = u.user_id ORDER BY p.created_at DESC');
        return $this->db->resultSet();
    }

    public function setProductStatus($id, $status){
        $this->db->query('UPDATE products SET status = :status WHERE product_id = :id');
        $this->db->bind(':status', $status);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function deleteProduct($id){
        $this->db->query('DELETE FROM products WHERE product_id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function getCategories(){
        $this->db->query('SELECT * FROM categories ORDER BY category_name');
        return $this->db->resultSet();
    }

    public function addCategory($name){
        $this->db->query('INSERT INTO categories (category_name) VALUES (:name)');
        $this->db->bind(':name', $name);
        return $this->db->execute();
    }

    public function getCategoryById($id){
        $this->db->query('SELECT * FROM categories WHERE category_id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function updateCategory($id, $name){
        $this->db->query('UPDATE categories SET category_name = :name WHERE category_id = :id');
        $this->db->bind(':name', $name);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function deleteCategory($id){
        $this->db->query('DELETE FROM categories WHERE category_id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function getAllOrders(){
        $this->db->query('SELECT o.*, u.first_name, u.last_name FROM orders o LEFT JOIN users u ON o.user_id = u.user_id ORDER BY o.created_at DESC');
        return $this->db->resultSet();
    }
}
