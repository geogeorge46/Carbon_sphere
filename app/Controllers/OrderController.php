<?php
class OrderController extends Controller {
    public function __construct() {
        $this->orderModel = $this->model('Order');
        $this->cartModel = $this->model('Cart');
        $this->productModel = $this->model('Product');
    }

    public function checkout() {
        $cart = $this->cartModel->getCartByUserId($_SESSION['user_id']);
        $items = $this->cartModel->getCartItems($cart->cart_id);
        $total = 0;
        $carbon_total = 0;
        foreach ($items as $item) {
            $total += $item->productPrice * $item->quantity;
            $product = $this->productModel->getProductById($item->product_id);
            $carbon_total += $product->carbon_footprint * $item->quantity;
        }

        $orderId = $this->orderModel->createOrder($_SESSION['user_id'], $total, $carbon_total);

        foreach ($items as $item) {
            $product = $this->productModel->getProductById($item->product_id);
            $this->orderModel->addOrderItem($orderId, $item->product_id, $item->quantity, $product->carbon_footprint * $item->quantity);
        }

        // Clear cart
        $this->cartModel->clearCart($cart->cart_id);

        header('location: ' . URLROOT . '/orders/success');
    }

    public function success() {
        $this->view('orders/success');
    }
}
