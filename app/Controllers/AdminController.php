<?php
class AdminController extends Controller {
    public function __construct() {
        // ensure models available
        $this->adminModel = $this->model('Admin');
        $this->userModel = $this->model('User');
        $this->productModel = $this->model('Product');
        $this->categoryModel = $this->model('Category');
        $this->orderModel = $this->model('Order');

        // Guard: only admin
        if(!isAdmin()){
            header('location:' . URLROOT . '/auth/login');
            exit;
        }
    }

    public function index(){
        // Dashboard metrics
        $metrics = $this->adminModel->getDashboardMetrics();

        // Analytics datasets
        $carbonByMonth = $this->adminModel->getCarbonByMonth(12);
        $revenueByMonth = $this->adminModel->getRevenueByMonth(12);
        $topProducts = $this->adminModel->getTopProductsByCarbon(10);
        $carbonByCategory = $this->adminModel->getCarbonByCategory();

        $data = [
            'metrics' => $metrics,
            'carbonByMonth' => $carbonByMonth,
            'revenueByMonth' => $revenueByMonth,
            'topProducts' => $topProducts,
            'carbonByCategory' => $carbonByCategory
        ];

        $this->view('admin/dashboard', $data);
    }

    public function users(){
        $users = $this->adminModel->getAllUsers();
        $this->view('admin/users', ['users' => $users]);
    }

    public function editUser($id){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $role = $_POST['role'] ?? 'buyer';
            $this->adminModel->updateUserRole($id, $role);
            header('location:' . URLROOT . '/admin/users');
        } else {
            $user = $this->adminModel->getUserById($id);
            $this->view('admin/edit_user', ['user' => $user]);
        }
    }

    public function deleteUser($id){
        $this->adminModel->deleteUser($id);
        header('location:' . URLROOT . '/admin/users');
    }

    public function products(){
        $products = $this->adminModel->getAllProducts();
        $this->view('admin/products', ['products' => $products]);
    }

    public function approveProduct($id){
        $this->adminModel->setProductStatus($id, 'active');
        header('location:' . URLROOT . '/admin/products');
    }

    public function deactivateProduct($id){
        $this->adminModel->setProductStatus($id, 'inactive');
        header('location:' . URLROOT . '/admin/products');
    }

    public function deleteProduct($id){
        $this->adminModel->deleteProduct($id);
        header('location:' . URLROOT . '/admin/products');
    }

    public function categories(){
        $categories = $this->adminModel->getCategories();
        $this->view('admin/categories', ['categories' => $categories]);
    }

    public function addCategory(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $this->adminModel->addCategory($_POST['category_name']);
            header('location:' . URLROOT . '/admin/categories');
        }
        $this->view('admin/add_category');
    }

    public function editCategory($id){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $this->adminModel->updateCategory($id, $_POST['category_name']);
            header('location:' . URLROOT . '/admin/categories');
        } else {
            $cat = $this->adminModel->getCategoryById($id);
            $this->view('admin/edit_category', ['category' => $cat]);
        }
    }

    public function deleteCategory($id){
        $this->adminModel->deleteCategory($id);
        header('location:' . URLROOT . '/admin/categories');
    }

    public function orders(){
        $orders = $this->adminModel->getAllOrders();
        $this->view('admin/orders', ['orders' => $orders]);
    }
}
