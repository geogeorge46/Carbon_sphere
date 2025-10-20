<?php
/**
 * CheckoutController - Handles payment checkout and order creation
 * Integrated with Razorpay payment gateway
 */
class CheckoutController extends Controller {
    private $orderModel;
    private $cartService;
    private $paymentService;
    private $ecoTokenService;

    public function __construct() {
        // Ensure user is logged in
        if (!isLoggedIn()) {
            header('location:' . URLROOT . '/auth/login');
            exit;
        }

        $this->orderModel = $this->model('Order');
        $this->cartService = new CartService();
        $this->paymentService = new PaymentService();
        $this->ecoTokenService = new EcoTokenService();
    }

    /**
     * Display checkout page
     */
    public function index() {
        $userId = $_SESSION['user_id'];
        $cartData = $this->cartService->getUserCart($userId);

        // Check if cart is empty
        if ($cartData['item_count'] == 0) {
            flash('checkout_error', 'Your cart is empty');
            header('location:' . URLROOT . '/cart');
            exit;
        }

        // Get Razorpay key
        $credentials = $this->paymentService->getCredentials();

        $data = [
            'title' => 'Checkout',
            'items' => $cartData['items'],
            'total_amount' => $cartData['total_amount'],
            'total_carbon' => $cartData['total_carbon'],
            'razorpay_key' => $credentials['key_id'],
            'user_name' => $_SESSION['user_name'] ?? '',
            'user_email' => $_SESSION['user_email'] ?? ''
        ];

        $this->view('checkout/index', $data);
    }

    /**
     * Create Razorpay order
     * Called via AJAX before payment
     */
    public function createOrder() {
        if ($_SERVER['REQUEST_METHOD'] != 'POST' || !$this->isAjax()) {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            exit;
        }

        // Get JSON payload
        $input = json_decode(file_get_contents('php://input'), true);

        // Validate delivery information
        $validation = $this->validateDeliveryInfo($input);
        if (!$validation['valid']) {
            echo json_encode(['error' => implode('; ', $validation['errors'])]);
            exit;
        }

        $userId = $_SESSION['user_id'];
        $cartData = $this->cartService->getUserCart($userId);

        if ($cartData['item_count'] == 0) {
            echo json_encode(['error' => 'Cart is empty']);
            exit;
        }

        try {
            // Prepare delivery info
            $deliveryInfo = [
                'address' => trim($input['address'] ?? ''),
                'city' => trim($input['city'] ?? ''),
                'state' => trim($input['state'] ?? ''),
                'postal_code' => trim($input['postal_code'] ?? ''),
                'phone' => trim($input['phone'] ?? '')
            ];

            // Create order in database first
            $orderId = $this->orderModel->createOrder(
                $userId,
                $cartData['total_amount'],
                $cartData['total_carbon'],
                $deliveryInfo
            );

            // Add order items
            foreach ($cartData['items'] as $item) {
                $this->orderModel->addOrderItem(
                    $orderId,
                    $item->product_id,
                    $item->quantity,
                    $item->carbon_footprint * $item->quantity
                );
            }

            // Create Razorpay order
            $razorpayOrder = $this->paymentService->createRazorpayOrder(
                $cartData['total_amount'],
                $userId,
                $_SESSION['user_email'],
                [
                    'order_id' => $orderId,
                    'carbon_tokens' => $this->ecoTokenService->calculateTokensEarned($cartData['total_carbon'])
                ]
            );

            if (isset($razorpayOrder['error'])) {
                echo json_encode(['error' => 'Failed to create payment order']);
                exit;
            }

            // Record payment transaction
            $this->paymentService->recordPaymentTransaction(
                $userId,
                $orderId,
                $razorpayOrder['id'],
                $cartData['total_amount']
            );

            echo json_encode([
                'success' => true,
                'razorpay_order_id' => $razorpayOrder['id'],
                'amount' => $razorpayOrder['amount'],
                'order_id' => $orderId
            ]);
            exit;
        } catch (Exception $e) {
            error_log('Order Creation Error: ' . $e->getMessage());
            echo json_encode(['error' => 'Error creating order: ' . $e->getMessage()]);
            exit;
        }
    }

    /**
     * Handle successful payment
     * Called after Razorpay payment success
     */
    public function paymentSuccess() {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            http_response_code(405);
            exit;
        }

        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        $razorpayOrderId = $_POST['razorpay_order_id'] ?? '';
        $razorpayPaymentId = $_POST['razorpay_payment_id'] ?? '';
        $razorpaySignature = $_POST['razorpay_signature'] ?? '';

        // Verify signature
        if (!$this->paymentService->verifyPaymentSignature(
            $razorpayOrderId,
            $razorpayPaymentId,
            $razorpaySignature
        )) {
            flash('payment_error', 'Payment verification failed');
            header('location:' . URLROOT . '/checkout/index');
            exit;
        }

        try {
            $userId = $_SESSION['user_id'];

            // Update payment transaction
            $this->paymentService->updatePaymentTransaction(
                $razorpayOrderId,
                $razorpayPaymentId,
                $razorpaySignature
            );

            // Get order details from cart to calculate tokens
            $cartData = $this->cartService->getUserCart($userId);
            $tokensEarned = $this->ecoTokenService->calculateTokensEarned($cartData['total_carbon']);

            // Award eco tokens to user
            $this->ecoTokenService->awardTokens(
                $userId,
                $tokensEarned,
                $cartData['total_carbon']
            );

            // Update order payment status
            $db = DB::getInstance();
            $db->query('
                UPDATE orders
                SET payment_status = :status,
                    payment_id = :payment_id,
                    carbon_tokens_earned = :tokens
                WHERE user_id = :user_id
                ORDER BY created_at DESC
                LIMIT 1
            ');
            $db->bind(':status', 'completed');
            $db->bind(':payment_id', $razorpayPaymentId);
            $db->bind(':tokens', $tokensEarned);
            $db->bind(':user_id', $userId);
            $db->execute();

            // Clear cart
            $this->cartService->clearCart($userId);

            flash('payment_success', 'Payment successful! Your order has been placed. 🎉');
            header('location:' . URLROOT . '/pages/dashboard');
            exit;
        } catch (Exception $e) {
            error_log('Payment Success Handler Error: ' . $e->getMessage());
            flash('payment_error', 'Error processing payment: ' . $e->getMessage());
            header('location:' . URLROOT . '/checkout/index');
            exit;
        }
    }

    /**
     * Handle payment failure
     */
    public function paymentFailed() {
        flash('payment_error', 'Payment failed. Please try again.');
        header('location:' . URLROOT . '/checkout/index');
        exit;
    }

    /**
     * Validate delivery information
     */
    private function validateDeliveryInfo($data) {
        $errors = [];

        // Validate address
        if (empty($data['address']) || strlen(trim($data['address'])) < 5) {
            $errors[] = 'Street Address must be at least 5 characters long';
        }

        // Validate city
        if (empty($data['city']) || strlen(trim($data['city'])) < 2) {
            $errors[] = 'City must be at least 2 characters long';
        }

        // Validate postal code (India: 6 digits)
        if (empty($data['postal_code']) || !preg_match('/^\d{6}$/', trim($data['postal_code']))) {
            $errors[] = 'Postal Code must be exactly 6 digits';
        }

        // Validate state
        if (empty($data['state']) || strlen(trim($data['state'])) < 2) {
            $errors[] = 'State must be at least 2 characters long';
        }

        // Validate phone (India: 10 digits)
        if (empty($data['phone']) || !preg_match('/^\d{10}$/', trim($data['phone']))) {
            $errors[] = 'Phone Number must be exactly 10 digits';
        }

        return [
            'valid' => count($errors) === 0,
            'errors' => $errors
        ];
    }

    /**
     * Check if request is AJAX
     */
    private function isAjax() {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }
}