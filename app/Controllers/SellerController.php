<?php
class SellerController extends Controller {
    private $productModel;
    private $orderModel;
    private $userModel;

    public function __construct() {
        // Check if user is logged in and is a seller
        if (!isLoggedIn() || (isset($_SESSION['user_role']) && $_SESSION['user_role'] !== 'seller')) {
            header('location:' . URLROOT . '/auth/login');
            exit;
        }

        $this->productModel = $this->model('Product');
        $this->orderModel = $this->model('Order');
        $this->userModel = $this->model('User');
    }

    /**
     * Dashboard Overview
     */
    public function index() {
        $sellerId = $_SESSION['user_id'];
        
        // Get basic stats
        $productCount = $this->productModel->getSellerProductCount($sellerId);
        $totalSales = $this->orderModel->getSellerTotalSales($sellerId);
        $avgCarbon = $this->productModel->getSellerAvgCarbonFootprint($sellerId);
        $totalCarbonSold = $this->productModel->getSellerTotalCarbonSold($sellerId);
        
        // Get monthly data for charts
        $monthlySales = $this->orderModel->getSellerMonthlySales($sellerId);
        
        // Get recent orders
        $recentOrders = $this->orderModel->getSellerOrders($sellerId);
        $recentOrders = array_slice($recentOrders ?? [], 0, 5);

        $data = [
            'title' => 'Seller Dashboard',
            'productCount' => $productCount,
            'totalSales' => $totalSales,
            'avgCarbon' => $avgCarbon,
            'totalCarbonSold' => $totalCarbonSold,
            'monthlySales' => $monthlySales ?? [],
            'recentOrders' => $recentOrders ?? []
        ];

        $this->view('seller/dashboard', $data);
    }

    /**
     * Add New Product
     */
    public function addProduct() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'product_name' => trim($_POST['product_name'] ?? ''),
                'description' => trim($_POST['description'] ?? ''),
                'price' => trim($_POST['price'] ?? ''),
                'carbon_footprint' => trim($_POST['carbon_footprint'] ?? ''),
                'category_id' => trim($_POST['category_id'] ?? ''),
                'image_url' => trim($_POST['image_url'] ?? ''),
                'seller_id' => $_SESSION['user_id'],
                'product_name_err' => '',
                'description_err' => '',
                'price_err' => '',
                'carbon_footprint_err' => '',
                'category_id_err' => '',
                'image_url_err' => ''
            ];

            // Validate
            if (empty($data['product_name'])) {
                $data['product_name_err'] = 'Product name is required';
            }
            if (empty($data['description'])) {
                $data['description_err'] = 'Description is required';
            }
            if (empty($data['price']) || !is_numeric($data['price']) || $data['price'] <= 0) {
                $data['price_err'] = 'Valid price is required';
            }
            if (empty($data['carbon_footprint']) || !is_numeric($data['carbon_footprint']) || $data['carbon_footprint'] < 0) {
                $data['carbon_footprint_err'] = 'Valid carbon footprint value is required';
            }
            if (empty($data['category_id'])) {
                $data['category_id_err'] = 'Please select a category';
            }
            // Validate image URL format (optional field, but if provided must be valid URL)
            if (!empty($data['image_url']) && !filter_var($data['image_url'], FILTER_VALIDATE_URL)) {
                $data['image_url_err'] = 'Please enter a valid image URL';
            }

            // Check if all errors are empty
            if (empty($data['product_name_err']) && empty($data['description_err']) && 
                empty($data['price_err']) && empty($data['carbon_footprint_err']) && 
                empty($data['category_id_err']) && empty($data['image_url_err'])) {
                
                if ($this->productModel->addProduct($data)) {
                    flash('product_created', 'Product added successfully!');
                    header('location:' . URLROOT . '/seller/myProducts');
                    exit;
                } else {
                    $data['general_err'] = 'Something went wrong. Please try again.';
                }
            }

            $this->view('seller/add_product', $data);
        } else {
            $data = [
                'product_name' => '',
                'description' => '',
                'price' => '',
                'carbon_footprint' => '',
                'category_id' => '',
                'image_url' => '',
                'product_name_err' => '',
                'description_err' => '',
                'price_err' => '',
                'carbon_footprint_err' => '',
                'category_id_err' => '',
                'image_url_err' => ''
            ];

            $this->view('seller/add_product', $data);
        }
    }

    /**
     * View all seller's products
     */
    public function myProducts() {
        $sellerId = $_SESSION['user_id'];
        $products = $this->productModel->getSellerProducts($sellerId);

        $data = [
            'title' => 'My Products',
            'products' => $products ?? []
        ];

        $this->view('seller/my_products', $data);
    }

    /**
     * Edit Product
     */
    public function editProduct($productId) {
        $sellerId = $_SESSION['user_id'];
        
        // Check if product belongs to seller
        if (!$this->productModel->isProductBySeller($productId, $sellerId)) {
            flash('product_error', 'You do not have permission to edit this product');
            header('location:' . URLROOT . '/seller/myProducts');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'id' => $productId,
                'product_name' => trim($_POST['product_name'] ?? ''),
                'description' => trim($_POST['description'] ?? ''),
                'price' => trim($_POST['price'] ?? ''),
                'carbon_footprint' => trim($_POST['carbon_footprint'] ?? ''),
                'category_id' => trim($_POST['category_id'] ?? ''),
                'image_url' => trim($_POST['image_url'] ?? ''),
                'product_name_err' => '',
                'description_err' => '',
                'price_err' => '',
                'carbon_footprint_err' => '',
                'category_id_err' => '',
                'image_url_err' => ''
            ];

            // Validate
            if (empty($data['product_name'])) {
                $data['product_name_err'] = 'Product name is required';
            }
            if (empty($data['description'])) {
                $data['description_err'] = 'Description is required';
            }
            if (empty($data['price']) || !is_numeric($data['price']) || $data['price'] <= 0) {
                $data['price_err'] = 'Valid price is required';
            }
            if (empty($data['carbon_footprint']) || !is_numeric($data['carbon_footprint']) || $data['carbon_footprint'] < 0) {
                $data['carbon_footprint_err'] = 'Valid carbon footprint value is required';
            }
            // Validate image URL format (optional field, but if provided must be valid URL)
            if (!empty($data['image_url']) && !filter_var($data['image_url'], FILTER_VALIDATE_URL)) {
                $data['image_url_err'] = 'Please enter a valid image URL';
            }

            if (empty($data['product_name_err']) && empty($data['description_err']) && 
                empty($data['price_err']) && empty($data['carbon_footprint_err']) && empty($data['image_url_err'])) {
                
                if ($this->productModel->updateProduct($data)) {
                    flash('product_updated', 'Product updated successfully!');
                    header('location:' . URLROOT . '/seller/myProducts');
                    exit;
                } else {
                    $data['general_err'] = 'Something went wrong. Please try again.';
                }
            }

            $this->view('seller/edit_product', $data);
        } else {
            $product = $this->productModel->getProductById($productId);
            
            if (!$product) {
                flash('product_error', 'Product not found');
                header('location:' . URLROOT . '/seller/myProducts');
                exit;
            }

            $data = [
                'id' => $productId,
                'product_name' => $product->product_name,
                'description' => $product->description,
                'price' => $product->price,
                'carbon_footprint' => $product->carbon_footprint,
                'category_id' => $product->category_id,
                'image_url' => $product->image_url ?? '',
                'product_name_err' => '',
                'description_err' => '',
                'price_err' => '',
                'carbon_footprint_err' => '',
                'category_id_err' => '',
                'image_url_err' => ''
            ];

            $this->view('seller/edit_product', $data);
        }
    }

    /**
     * Delete Product
     */
    public function deleteProduct($productId) {
        $sellerId = $_SESSION['user_id'];
        
        // Check if product belongs to seller
        if (!$this->productModel->isProductBySeller($productId, $sellerId)) {
            flash('product_error', 'You do not have permission to delete this product');
            header('location:' . URLROOT . '/seller/myProducts');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->productModel->deleteProduct($productId)) {
                flash('product_deleted', 'Product deleted successfully!');
            } else {
                flash('product_error', 'Failed to delete product');
            }
            header('location:' . URLROOT . '/seller/myProducts');
            exit;
        } else {
            header('location:' . URLROOT . '/seller/myProducts');
            exit;
        }
    }

    /**
     * View seller's orders
     */
    public function myOrders() {
        $sellerId = $_SESSION['user_id'];
        $orders = $this->orderModel->getSellerOrders($sellerId);

        $data = [
            'title' => 'My Orders',
            'orders' => $orders ?? []
        ];

        $this->view('seller/my_orders', $data);
    }

    /**
     * View specific order details
     */
    public function orderDetails($orderId) {
        $sellerId = $_SESSION['user_id'];
        $orderItems = $this->orderModel->getSellerOrderItems($orderId, $sellerId);

        if (empty($orderItems)) {
            flash('order_error', 'Order not found or you do not have access');
            header('location:' . URLROOT . '/seller/myOrders');
            exit;
        }

        $order = null;
        $allOrders = $this->orderModel->getSellerOrders($sellerId);
        foreach ($allOrders as $o) {
            if ($o->order_id == $orderId) {
                $order = $o;
                break;
            }
        }

        $data = [
            'title' => 'Order Details',
            'order' => $order,
            'orderItems' => $orderItems
        ];

        $this->view('seller/order_details', $data);
    }

    /**
     * Earnings & Carbon Report
     */
    public function report() {
        $sellerId = $_SESSION['user_id'];
        
        $totalSales = $this->orderModel->getSellerTotalSales($sellerId);
        $monthlySales = $this->orderModel->getSellerMonthlySales($sellerId);
        $products = $this->productModel->getSellerProducts($sellerId);

        // Calculate carbon reduction potential (assuming 1kg CO2 per product on average)
        $carbonReductionPotential = 0;
        if ($products) {
            $carbonReductionPotential = count($products) * 2.5; // Placeholder calculation
        }

        $data = [
            'title' => 'Earnings & Carbon Report',
            'totalSales' => $totalSales,
            'monthlySales' => $monthlySales ?? [],
            'products' => $products ?? [],
            'carbonReductionPotential' => $carbonReductionPotential
        ];

        $this->view('seller/report', $data);
    }
}