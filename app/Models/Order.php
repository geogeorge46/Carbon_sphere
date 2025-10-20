<?php
class Order extends Model {
    public function __construct() {
        parent::__construct();
    }

    public function createOrder($userId, $total, $carbonTotal, $deliveryInfo = null) {
        if ($deliveryInfo) {
            $this->db->query('INSERT INTO orders (user_id, total_amount, total_carbon, delivery_address, delivery_city, delivery_state, delivery_postal_code, delivery_phone) VALUES (:user_id, :total_amount, :total_carbon, :address, :city, :state, :postal_code, :phone)');
            $this->db->bind(':user_id', $userId);
            $this->db->bind(':total_amount', $total);
            $this->db->bind(':total_carbon', $carbonTotal);
            $this->db->bind(':address', $deliveryInfo['address']);
            $this->db->bind(':city', $deliveryInfo['city']);
            $this->db->bind(':state', $deliveryInfo['state']);
            $this->db->bind(':postal_code', $deliveryInfo['postal_code']);
            $this->db->bind(':phone', $deliveryInfo['phone']);
        } else {
            $this->db->query('INSERT INTO orders (user_id, total_amount, total_carbon) VALUES (:user_id, :total_amount, :total_carbon)');
            $this->db->bind(':user_id', $userId);
            $this->db->bind(':total_amount', $total);
            $this->db->bind(':total_carbon', $carbonTotal);
        }
        $this->db->execute();
        return $this->db->lastInsertId();
    }

    public function addOrderItem($orderId, $productId, $quantity, $carbonFootprint) {
        $this->db->query('INSERT INTO order_items (order_id, product_id, quantity, carbon_footprint) VALUES (:order_id, :product_id, :quantity, :carbon_footprint)');
        $this->db->bind(':order_id', $orderId);
        $this->db->bind(':product_id', $productId);
        $this->db->bind(':quantity', $quantity);
        $this->db->bind(':carbon_footprint', $carbonFootprint);
        return $this->db->execute();
    }

    // Get seller's orders (orders containing their products)
    public function getSellerOrders($sellerId) {
        $this->db->query("
            SELECT DISTINCT o.order_id, o.user_id, o.total_amount, o.total_carbon, 
                   o.created_at, o.payment_status, u.first_name, u.last_name, u.email
            FROM orders o
            INNER JOIN order_items oi ON o.order_id = oi.order_id
            INNER JOIN products p ON oi.product_id = p.product_id
            INNER JOIN users u ON o.user_id = u.user_id
            WHERE p.seller_id = :seller_id
            ORDER BY o.created_at DESC
        ");
        $this->db->bind(':seller_id', $sellerId);
        return $this->db->resultSet();
    }

    // Get order items for a specific seller's order
    public function getSellerOrderItems($orderId, $sellerId) {
        $this->db->query("
            SELECT oi.order_item_id, oi.product_id, oi.quantity, oi.carbon_footprint,
                   p.product_name, p.price, (p.price * oi.quantity) as item_total
            FROM order_items oi
            INNER JOIN products p ON oi.product_id = p.product_id
            WHERE oi.order_id = :order_id AND p.seller_id = :seller_id
        ");
        $this->db->bind(':order_id', $orderId);
        $this->db->bind(':seller_id', $sellerId);
        return $this->db->resultSet();
    }

    // Get seller's total sales
    public function getSellerTotalSales($sellerId) {
        $this->db->query("
            SELECT SUM(p.price * oi.quantity) as total_revenue,
                   COUNT(DISTINCT o.order_id) as total_orders,
                   SUM(oi.carbon_footprint) as total_carbon_emitted,
                   SUM(oi.quantity) as total_items_sold
            FROM orders o
            INNER JOIN order_items oi ON o.order_id = oi.order_id
            INNER JOIN products p ON oi.product_id = p.product_id
            WHERE p.seller_id = :seller_id
        ");
        $this->db->bind(':seller_id', $sellerId);
        return $this->db->single();
    }

    // Get monthly sales data for chart
    public function getSellerMonthlySales($sellerId) {
        $this->db->query("
            SELECT DATE_FORMAT(o.created_at, '%Y-%m') as month,
                   SUM(p.price * oi.quantity) as revenue,
                   SUM(oi.carbon_footprint) as carbon
            FROM orders o
            INNER JOIN order_items oi ON o.order_id = oi.order_id
            INNER JOIN products p ON oi.product_id = p.product_id
            WHERE p.seller_id = :seller_id
            GROUP BY DATE_FORMAT(o.created_at, '%Y-%m')
            ORDER BY month DESC
            LIMIT 12
        ");
        $this->db->bind(':seller_id', $sellerId);
        return $this->db->resultSet();
    }
    
    // Get user orders
    public function getUserOrders($userId) {
        $this->db->query('
            SELECT * FROM orders 
            WHERE user_id = :user_id 
            ORDER BY created_at DESC
        ');
        $this->db->bind(':user_id', $userId);
        return $this->db->resultSet();
    }
}
