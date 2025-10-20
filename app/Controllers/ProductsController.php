<?php
class ProductsController extends Controller {
    public function __construct() {
        $this->productModel = $this->model('Product');
        $this->userModel = $this->model('User');
    }

    /**
     * Display all products for buyers (EcoWorld store)
     * Includes filtering and sorting capabilities
     */
    public function browse() {
        $_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
        
        $category = $_GET['category'] ?? '';
        $sortBy = $_GET['sort'] ?? 'newest';
        $search = $_GET['search'] ?? '';

        // Get all products with seller info
        $allProducts = $this->productModel->getProducts();
        $products = $allProducts;

        // Filter by category if selected
        if (!empty($category)) {
            $products = array_filter($products, function($p) use ($category) {
                return $p->category_id == $category;
            });
        }

        // Search filter
        if (!empty($search)) {
            $search = strtolower($search);
            $products = array_filter($products, function($p) use ($search) {
                return strpos(strtolower($p->product_name), $search) !== false ||
                       strpos(strtolower($p->description), $search) !== false;
            });
        }

        // Sort products
        switch ($sortBy) {
            case 'price_low':
                usort($products, function($a, $b) { return $a->price - $b->price; });
                break;
            case 'price_high':
                usort($products, function($a, $b) { return $b->price - $a->price; });
                break;
            case 'eco_friendly':
                usort($products, function($a, $b) { return $b->carbon_footprint - $a->carbon_footprint; });
                break;
            case 'newest':
            default:
                // Already sorted by created_at DESC from model
                break;
        }

        $data = [
            'title' => 'EcoWorld Store',
            'products' => array_values($products),
            'category_filter' => $category,
            'sort_by' => $sortBy,
            'search_query' => $search,
            'total_products' => count($products),
            'is_logged_in' => isLoggedIn()
        ];

        $this->view('products/browse', $data);
    }

    /**
     * Original index - kept for backward compatibility
     */
    public function index() {
        $products = $this->productModel->getProducts();

        $data = [
            'products' => $products
        ];

        $this->view('products/index', $data);
    }

    public function add(){
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        // Sanitize POST array
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        $data = [
          'product_name' => trim($_POST['product_name']),
          'description' => trim($_POST['description']),
          'price' => trim($_POST['price']),
          'carbon_footprint' => trim($_POST['carbon_footprint']),
          'seller_id' => $_SESSION['user_id'],
          'category_id' => trim($_POST['category_id'] ?? 1),
          'image_url' => $_POST['image_url'] ?? '',
          'product_name_err' => '',
          'description_err' => '',
          'price_err' => '',
          'carbon_footprint_err' => ''
        ];

        // Validate data
        if(empty($data['product_name'])){
          $data['product_name_err'] = 'Please enter product name';
        }
        if(empty($data['description'])){
          $data['description_err'] = 'Please enter description';
        }
        if(empty($data['price'])){
          $data['price_err'] = 'Please enter price';
        }
        if(empty($data['carbon_footprint'])){
          $data['carbon_footprint_err'] = 'Please enter carbon footprint';
        }

        // Make sure no errors
        if(empty($data['product_name_err']) && empty($data['description_err']) && empty($data['price_err']) && empty($data['carbon_footprint_err'])){
          // Validated
          if($this->productModel->addProduct($data)){
            header('location: ' . URLROOT . '/products');
          } else {
            die('Something went wrong');
          }
        } else {
          // Load view with errors
          $this->view('products/add', $data);
        }

      } else {
        $data = [
          'product_name' => '',
          'description' => '',
          'price' => '',
          'carbon_footprint' => ''
        ];
  
        $this->view('products/add', $data);
      }
    }

    public function show($id){
      $product = $this->productModel->getProductById($id);
      $user = $this->userModel->getUserById($product->seller_id);

      $data = [
        'product' => $product,
        'user' => $user
      ];

      $this->view('products/show', $data);
    }

    public function edit($id){
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        // Sanitize POST array
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        $data = [
          'id' => $id,
          'product_name' => trim($_POST['product_name']),
          'description' => trim($_POST['description']),
          'price' => trim($_POST['price']),
          'carbon_footprint' => trim($_POST['carbon_footprint']),
          'category_id' => trim($_POST['category_id'] ?? 1),
          'image_url' => $_POST['image_url'] ?? '',
          'product_name_err' => '',
          'description_err' => '',
          'price_err' => '',
          'carbon_footprint_err' => ''
        ];

        // Validate data
        if(empty($data['product_name'])){
          $data['product_name_err'] = 'Please enter product name';
        }
        if(empty($data['description'])){
          $data['description_err'] = 'Please enter description';
        }
        if(empty($data['price'])){
          $data['price_err'] = 'Please enter price';
        }
        if(empty($data['carbon_footprint'])){
          $data['carbon_footprint_err'] = 'Please enter carbon footprint';
        }

        // Make sure no errors
        if(empty($data['product_name_err']) && empty($data['description_err']) && empty($data['price_err']) && empty($data['carbon_footprint_err'])){
          // Validated
          if($this->productModel->updateProduct($data)){
            header('location: ' . URLROOT . '/products');
          } else {
            die('Something went wrong');
          }
        } else {
          // Load view with errors
          $this->view('products/edit', $data);
        }

      } else {
        // Get existing product from model
        $product = $this->productModel->getProductById($id);

        // Check for owner
        if($product->seller_id != $_SESSION['user_id']){
          header('location: ' . URLROOT . '/products');
        }

        $data = [
          'id' => $id,
          'product_name' => $product->product_name,
          'description' => $product->description,
          'price' => $product->price,
          'carbon_footprint' => $product->carbon_footprint,
          'category_id' => $product->category_id,
          'image_url' => $product->image_url
        ];
  
        $this->view('products/edit', $data);
      }
    }

    public function delete($id){
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        // Get existing product from model
        $product = $this->productModel->getProductById($id);
        
        // Check for owner
        if($product->seller_id != $_SESSION['user_id']){
          header('location: ' . URLROOT . '/products');
        }

        if($this->productModel->deleteProduct($id)){
          header('location: ' . URLROOT . '/products');
        } else {
          die('Something went wrong');
        }
      } else {
        header('location: ' . URLROOT . '/products');
      }
    }
}
