<?php
    $title = 'Shopping Cart - EcoWorld';
    require APPROOT . '/Views/inc/header.php';
?>

<div class="container mt-5 mb-5">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="mb-2">🛒 Shopping Cart</h1>
            <p class="text-muted">Review your eco-friendly selections</p>
        </div>
    </div>

    <!-- Flash Messages -->
    <?php $msg = flash('cart_added'); ?>
    <?php if ($msg) : ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            ✅ <?php echo htmlspecialchars($msg['message']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php $msg = flash('item_removed'); ?>
    <?php if ($msg) : ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($msg['message']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php $msg = flash('cart_cleared'); ?>
    <?php if ($msg) : ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($msg['message']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <!-- Cart Items -->
        <div class="col-md-8">
            <?php if (empty($data['items'])) : ?>
                <div class="card p-5 text-center">
                    <h4 class="text-muted mb-3">Your cart is empty</h4>
                    <p class="text-muted mb-4">Start shopping for eco-friendly products!</p>
                    <a href="<?php echo URLROOT; ?>/products/browse" class="btn btn-success">Continue Shopping</a>
                </div>
            <?php else : ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <th>Carbon</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data['items'] as $item) : ?>
                                <tr class="align-middle">
                                    <td>
                                        <strong><?php echo htmlspecialchars($item->productName); ?></strong><br>
                                        <small class="text-muted">ID: <?php echo $item->product_id; ?></small>
                                    </td>
                                    <td>₹<?php echo number_format($item->productPrice, 2); ?></td>
                                    <td>
                                        <form method="POST" action="<?php echo URLROOT; ?>/cart/updateQuantity" class="d-inline quantity-form">
                                            <input type="hidden" name="cart_item_id" value="<?php echo $item->cart_item_id; ?>">
                                            <input type="number" name="quantity" value="<?php echo $item->quantity; ?>" min="1" max="100" class="form-control form-control-sm" style="width: 70px;">
                                        </form>
                                    </td>
                                    <td>
                                        <strong>₹<?php echo number_format($item->productPrice * $item->quantity, 2); ?></strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">
                                            ♻️ <?php echo round($item->carbon_footprint * $item->quantity, 2); ?> kg
                                        </span>
                                    </td>
                                    <td>
                                        <form method="POST" action="<?php echo URLROOT; ?>/cart/remove/<?php echo $item->cart_item_id; ?>" style="display: inline;">
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Remove this item?');">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Continue Shopping -->
                <div class="mb-3">
                    <a href="<?php echo URLROOT; ?>/products/browse" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Continue Shopping
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <!-- Cart Summary -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-4">Order Summary</h5>

                    <!-- Items -->
                    <div class="d-flex justify-content-between mb-2">
                        <span>Items:</span>
                        <strong><?php echo $data['item_count']; ?></strong>
                    </div>

                    <!-- Subtotal -->
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <strong>₹<?php echo number_format($data['total_amount'], 2); ?></strong>
                    </div>

                    <!-- Carbon Footprint -->
                    <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                        <span>Total Carbon:</span>
                        <strong class="text-success">
                            ♻️ <?php echo number_format($data['total_carbon'], 2); ?> kg CO₂e
                        </strong>
                    </div>

                    <!-- Estimated Tokens -->
                    <div class="alert alert-info mb-3">
                        <small>
                            <strong>🪙 Tokens You'll Earn:</strong><br>
                            ~<?php echo (int)($data['total_carbon'] * 10); ?> Carbon Tokens
                        </small>
                    </div>

                    <!-- Shipping (static) -->
                    <div class="d-flex justify-content-between mb-3">
                        <span>Shipping:</span>
                        <strong>FREE</strong>
                    </div>

                    <!-- Tax (if applicable) -->
                    <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                        <span>Tax (18% GST):</span>
                        <strong>₹<?php echo number_format($data['total_amount'] * 0.18, 2); ?></strong>
                    </div>

                    <!-- Total -->
                    <div class="d-flex justify-content-between mb-4">
                        <h6>Total Amount:</h6>
                        <h6 class="text-success">
                            ₹<?php echo number_format($data['total_amount'] * 1.18, 2); ?>
                        </h6>
                    </div>

                    <!-- Checkout Button -->
                    <?php if ($data['item_count'] > 0) : ?>
                        <a href="<?php echo URLROOT; ?>/checkout/index" class="btn btn-success w-100 btn-lg mb-2">
                            <i class="fas fa-credit-card"></i> Proceed to Payment
                        </a>

                        <!-- Clear Cart -->
                        <form method="POST" action="<?php echo URLROOT; ?>/cart/clear">
                            <button type="submit" class="btn btn-outline-danger w-100" onclick="return confirm('Clear your entire cart?');">
                                <i class="fas fa-trash"></i> Clear Cart
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Eco Info Card -->
            <div class="card mt-3 bg-light">
                <div class="card-body">
                    <h6 class="card-title">💚 Environmental Impact</h6>
                    <small class="text-muted">
                        By purchasing eco-friendly products, you're helping reduce carbon emissions and earning tokens towards your Green rank!
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .quantity-form input {
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 0.5rem;
    }

    .quantity-form input:focus {
        border-color: #28a745;
        box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
    }
</style>

<?php require APPROOT . '/Views/inc/footer.php'; ?>