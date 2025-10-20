<?php
class Pages extends Controller {
  public function __construct(){

  }

  public function index(){
    // Fetch products
    $productModel = $this->model('Product');
    $products = $productModel->getProducts();

    $data = [
      'title' => 'Welcome To Carbon Sphere',
      'products' => $products ?? []
    ];

    $this->view('pages/index', $data);
  }

  public function about(){
    $data = [
      'title' => 'About Us'
    ];

    $this->view('pages/about', $data);
  }

  /**
   * Buyer Dashboard - Shows user eco stats and order history
   */
  public function dashboard(){
    // Check if logged in
    if(!isLoggedIn()){
      header('location:' . URLROOT . '/auth/login');
      exit;
    }

    $userId = $_SESSION['user_id'];
    
    // Initialize services
    $ecoTokenService = new EcoTokenService();
    $orderModel = $this->model('Order');

    // Get eco stats
    $ecoStats = $ecoTokenService->getUserEcoStats($userId);
    
    // Get user orders
    // Create a method to get user orders instead of directly accessing protected property
    $orders = $orderModel->getUserOrders($userId);

    // Get user rank
    $userRank = $ecoTokenService->getUserRank($userId);

    $data = [
      'title' => 'My Dashboard',
      'user_name' => $_SESSION['user_name'] ?? 'User',
      'user_email' => $_SESSION['user_email'] ?? '',
      'eco_stats' => $ecoStats,
      'orders' => $orders ?? [],
      'user_rank' => $userRank
    ];

    $this->view('pages/buyer-dashboard', $data);
  }
}
