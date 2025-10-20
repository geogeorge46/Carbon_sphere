<?php
/**
 * CartController - Manages user cart operations
 * Integrated with OOP CartService and PaymentService
 */
class CartController extends Controller {
    private $cartService;
    private $cartModel;
    private $productModel;

    public function __construct() {
        // Ensure user is logged in
        if (!isLoggedIn()) {
            header('location:' . URLROOT . '/auth/login');
            exit;
        }

        $this->cartService = new CartService();
        $this->cartModel = $this->model('Cart');
        $this->productModel = $this->model('Product');
    }

    /**
     * View user's cart (default method)
     */
    public function index() {
        $userId = $_SESSION['user_id'];
        $cartData = $this->cartService->getUserCart($userId);

        $data = [
            'title' => 'Shopping Cart',
            'items' => $cartData['items'] ?? [],
            'total_amount' => $cartData['total_amount'] ?? 0,
            'total_carbon' => $cartData['total_carbon'] ?? 0,
            'item_count' => $cartData['item_count'] ?? 0
        ];

        parent::view('cart/view', $data);
    }

    /**
     * Add product to cart (AJAX or redirect)
     */
    public function add($productId = null) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $productId = $_POST['product_id'] ?? $productId;
            $quantity = (int)($_POST['quantity'] ?? 1);

            if (!$productId || $quantity <= 0) {
                if ($this->isAjax()) {
                    echo json_encode(['success' => false, 'message' => 'Invalid product or quantity']);
                    exit;
                }
                header('location:' . URLROOT . '/products/browse');
                exit;
            }

            // Verify product exists
            $product = $this->productModel->getProductById($productId);
            if (!$product) {
                if ($this->isAjax()) {
                    echo json_encode(['success' => false, 'message' => 'Product not found']);
                    exit;
                }
                header('location:' . URLROOT . '/products/browse');
                exit;
            }

            $result = $this->cartService->addToCart($_SESSION['user_id'], $productId, $quantity);

            if ($this->isAjax()) {
                header('Content-Type: application/json');
                echo json_encode($result);
                exit;
            }

            if ($result['success']) {
                flash('cart_added', 'Product added to cart!');
            } else {
                flash('cart_error', $result['message']);
            }

            header('location:' . URLROOT . '/cart');
            exit;
        }

        header('location:' . URLROOT . '/cart');
    }

    /**
     * Update product quantity
     */
    public function updateQuantity() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $cartItemId = (int)$_POST['cart_item_id'];
            $quantity = (int)$_POST['quantity'];

            if ($quantity <= 0) {
                $result = $this->cartService->removeFromCart($cartItemId);
            } else {
                $result = $this->cartService->updateQuantity($cartItemId, $quantity);
            }

            if ($this->isAjax()) {
                header('Content-Type: application/json');
                echo json_encode($result);
                exit;
            }

            header('location:' . URLROOT . '/cart');
        }
    }

    /**
     * Remove product from cart
     */
    public function remove($cartItemId) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' || $this->isAjax()) {
            $result = $this->cartService->removeFromCart($cartItemId);

            if ($this->isAjax()) {
                header('Content-Type: application/json');
                echo json_encode($result);
                exit;
            }

            flash('item_removed', 'Product removed from cart');
            header('location:' . URLROOT . '/cart');
            exit;
        }

        header('location:' . URLROOT . '/cart');
    }

    /**
     * Clear entire cart
     */
    public function clear() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $result = $this->cartService->clearCart($_SESSION['user_id']);
            
            flash('cart_cleared', 'Cart cleared successfully');
            header('location:' . URLROOT . '/cart');
            exit;
        }
    }

    /**
     * Get mini cart info (for AJAX requests)
     */
    public function miniCart() {
        if ($this->isAjax()) {
            $miniCart = $this->cartService->getMiniCart($_SESSION['user_id']);
            header('Content-Type: application/json');
            echo json_encode($miniCart);
            exit;
        }
    }

    /**
     * Check if request is AJAX
     */
    private function isAjax() {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }
}