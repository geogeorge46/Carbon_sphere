<?php
    $title = 'Checkout - EcoWorld';
    require APPROOT . '/Views/inc/header.php';
?>

<div class="container mt-5 mb-5">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="mb-2">💳 Checkout</h1>
            <p class="text-muted">Complete your eco-friendly purchase</p>
        </div>
    </div>

    <!-- Flash Messages -->
    <?php $msg = flash('payment_error'); ?>
    <?php if ($msg) : ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            ❌ <?php echo htmlspecialchars($msg['message']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <!-- Order Summary -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Order Summary</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Qty</th>
                                    <th>Total</th>
                                    <th>Carbon</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data['items'] as $item) : ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo htmlspecialchars(substr($item->productName, 0, 30)); ?></strong>
                                        </td>
                                        <td>₹<?php echo number_format($item->productPrice, 2); ?></td>
                                        <td><?php echo $item->quantity; ?></td>
                                        <td><strong>₹<?php echo number_format($item->productPrice * $item->quantity, 2); ?></strong></td>
                                        <td>
                                            <span class="badge bg-success">
                                                <?php echo round($item->carbon_footprint * $item->quantity, 2); ?> kg
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Delivery Address -->
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">📍 Delivery Information</h5>
                </div>
                <div class="card-body">
                    <!-- Validation Errors Display -->
                    <div id="validation-errors" class="alert alert-danger alert-dismissible fade show d-none" role="alert">
                        <strong>Please fix the following errors:</strong>
                        <ul class="mb-0 mt-2" id="error-list"></ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>

                    <form id="address-form">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Full Name</label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($data['user_name']); ?>" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" value="<?php echo htmlspecialchars($data['user_email']); ?>" readonly>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label">Street Address <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="address" placeholder="Enter your street address" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">City <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="city" placeholder="City" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Postal Code <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="postal_code" placeholder="Postal Code" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">State <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="state" placeholder="State" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control" name="phone" placeholder="10-digit phone number" required>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Payment Summary -->
        <div class="col-md-4">
            <div class="card shadow-sm sticky-top" style="top: 20px;">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">💰 Payment Summary</h5>
                </div>
                <div class="card-body">
                    <!-- Subtotal -->
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <strong>₹<?php echo number_format($data['total_amount'], 2); ?></strong>
                    </div>

                    <!-- Shipping -->
                    <div class="d-flex justify-content-between mb-2">
                        <span>Shipping:</span>
                        <strong class="text-success">FREE</strong>
                    </div>

                    <!-- Tax -->
                    <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                        <span>Tax (18% GST):</span>
                        <strong>₹<?php echo number_format($data['total_amount'] * 0.18, 2); ?></strong>
                    </div>

                    <!-- Total -->
                    <div class="d-flex justify-content-between mb-4">
                        <h6 class="mb-0">Total Amount:</h6>
                        <h5 class="mb-0 text-success">₹<?php echo number_format($data['total_amount'] * 1.18, 2); ?></h5>
                    </div>

                    <!-- Carbon Impact -->
                    <div class="alert alert-info mb-3">
                        <small>
                            <strong>♻️ Carbon Saved:</strong><br>
                            <?php echo number_format($data['total_carbon'], 2); ?> kg CO₂e
                        </small>
                    </div>

                    <!-- Tokens -->
                    <div class="alert alert-success mb-3">
                        <small>
                            <strong>🪙 Tokens to Earn:</strong><br>
                            ~<?php echo (int)($data['total_carbon'] * 10); ?> Carbon Tokens
                        </small>
                    </div>

                    <!-- Payment Button -->
                    <button id="pay-button" class="btn btn-success w-100 btn-lg mb-2" onclick="initiatePayment()">
                        <i class="fas fa-lock"></i> Pay Now
                    </button>

                    <!-- Back Button -->
                    <a href="<?php echo URLROOT; ?>/cart" class="btn btn-outline-secondary w-100">
                        Back to Cart
                    </a>

                    <!-- Security Badge -->
                    <div class="mt-3 text-center">
                        <small class="text-muted">
                            🔒 Secure payment powered by Razorpay
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Razorpay Script -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<style>
    #address-form .form-control {
        border: 2px solid #e0e0e0;
        transition: all 0.3s ease;
    }

    #address-form .form-control:focus {
        border-color: #28a745;
        box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.15);
    }

    .form-label {
        font-weight: 600;
        color: #333;
    }

    .form-label .text-danger {
        margin-left: 2px;
    }

    #validation-errors {
        margin-bottom: 20px;
    }

    #validation-errors ul {
        list-style-position: inside;
    }

    #validation-errors li {
        margin-bottom: 8px;
    }
</style>

<script>
    const RAZORPAY_KEY = '<?php echo $data['razorpay_key']; ?>';
    const URLROOT = '<?php echo URLROOT; ?>';

    function getDeliveryInfo() {
        return {
            address: document.querySelector('input[name="address"]').value.trim(),
            city: document.querySelector('input[name="city"]').value.trim(),
            postal_code: document.querySelector('input[name="postal_code"]').value.trim(),
            state: document.querySelector('input[name="state"]').value.trim(),
            phone: document.querySelector('input[name="phone"]').value.trim()
        };
    }

    function validateDeliveryInfo() {
        const info = getDeliveryInfo();
        const errors = [];

        // Validate address
        if (!info.address || info.address.length < 5) {
            errors.push('❌ Street Address must be at least 5 characters long');
        }

        // Validate city
        if (!info.city || info.city.length < 2) {
            errors.push('❌ City must be at least 2 characters long');
        }

        // Validate postal code (India postal code: 6 digits)
        if (!info.postal_code || !/^\d{6}$/.test(info.postal_code)) {
            errors.push('❌ Postal Code must be exactly 6 digits');
        }

        // Validate state
        if (!info.state || info.state.length < 2) {
            errors.push('❌ State must be at least 2 characters long');
        }

        // Validate phone (India phone: 10 digits)
        if (!info.phone || !/^\d{10}$/.test(info.phone)) {
            errors.push('❌ Phone Number must be exactly 10 digits');
        }

        return {
            valid: errors.length === 0,
            errors: errors
        };
    }

    function displayValidationErrors(errors) {
        const errorDiv = document.getElementById('validation-errors');
        const errorList = document.getElementById('error-list');
        
        if (errors.length === 0) {
            errorDiv.classList.add('d-none');
            return;
        }
        
        errorList.innerHTML = errors.map(error => `<li>${error}</li>`).join('');
        errorDiv.classList.remove('d-none');
        errorDiv.scrollIntoView({ behavior: 'smooth' });
    }

    function initiatePayment() {
        // Validate delivery information first
        const validation = validateDeliveryInfo();
        if (!validation.valid) {
            displayValidationErrors(validation.errors);
            return;
        }
        
        // Clear any previous errors
        displayValidationErrors([]);

        const button = document.getElementById('pay-button');
        button.disabled = true;
        button.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processing...';

        // Get delivery information
        const deliveryInfo = getDeliveryInfo();

        // First, create order on server
        fetch(URLROOT + '/checkout/createOrder', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(deliveryInfo)
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                displayValidationErrors([data.error]);
                button.disabled = false;
                button.innerHTML = '<i class="fas fa-lock"></i> Pay Now';
                return;
            }

            // Open Razorpay checkout
            const options = {
                key: RAZORPAY_KEY,
                amount: data.amount,
                currency: 'INR',
                name: 'EcoWorld',
                description: 'Purchase eco-friendly products',
                order_id: data.razorpay_order_id,
                handler: function(response) {
                    // Payment successful, verify and submit
                    verifyAndSubmitPayment(response);
                },
                prefill: {
                    name: '<?php echo htmlspecialchars($data['user_name']); ?>',
                    email: '<?php echo htmlspecialchars($data['user_email']); ?>'
                },
                theme: {
                    color: '#28a745'
                },
                modal: {
                    ondismiss: function() {
                        button.disabled = false;
                        button.innerHTML = '<i class="fas fa-lock"></i> Pay Now';
                    }
                }
            };

            const rzp = new Razorpay(options);
            rzp.open();
        })
        .catch(error => {
            console.error('Error:', error);
            displayValidationErrors(['Error creating payment order. Please try again.']);
            button.disabled = false;
            button.innerHTML = '<i class="fas fa-lock"></i> Pay Now';
        });
    }

    function verifyAndSubmitPayment(response) {
        // Create form and submit to verify payment
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = URLROOT + '/checkout/paymentSuccess';

        const fields = {
            'razorpay_order_id': response.razorpay_order_id,
            'razorpay_payment_id': response.razorpay_payment_id,
            'razorpay_signature': response.razorpay_signature
        };

        for (const [key, value] of Object.entries(fields)) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = key;
            input.value = value;
            form.appendChild(input);
        }

        document.body.appendChild(form);
        form.submit();
    }
</script>

<?php require APPROOT . '/Views/inc/footer.php'; ?>