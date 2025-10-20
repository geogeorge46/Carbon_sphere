<?php
    $title = 'My Dashboard - EcoWorld';
    require APPROOT . '/Views/inc/header.php';
?>

<div class="container mt-5 mb-5">
    <!-- Welcome Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="mb-2">👋 Welcome, <?php echo htmlspecialchars($data['user_name']); ?></h1>
            <p class="text-muted">Track your eco-friendly purchases and carbon tokens</p>
        </div>
    </div>

    <!-- Flash Messages -->
    <?php if (flash('payment_success')) : ?>
        <?php $msg = flash('payment_success'); ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($msg['message']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Eco Stats Row -->
    <div class="row mb-4">
        <!-- Carbon Tokens -->
        <div class="col-md-3 mb-3">
            <div class="card bg-success text-white shadow-sm">
                <div class="card-body text-center">
                    <h6 class="card-title">🪙 Carbon Tokens</h6>
                    <h2 class="mb-2"><?php echo number_format($data['eco_stats']['tokens'] ?? 0); ?></h2>
                    <small>Eco-credit balance</small>
                </div>
            </div>
        </div>

        <!-- Eco Rating -->
        <div class="col-md-3 mb-3">
            <div class="card bg-info text-white shadow-sm">
                <div class="card-body text-center">
                    <h6 class="card-title">⭐ Eco Rating</h6>
                    <h2 class="mb-2"><?php echo htmlspecialchars($data['eco_stats']['rating'] ?? 'Bronze'); ?></h2>
                    <small>Keep earning to rank up!</small>
                </div>
            </div>
        </div>

        <!-- Carbon Saved -->
        <div class="col-md-3 mb-3">
            <div class="card bg-info text-white shadow-sm">
                <div class="card-body text-center">
                    <h6 class="card-title">♻️ Carbon Saved</h6>
                    <h2 class="mb-2"><?php echo number_format($data['eco_stats']['carbon_saved'] ?? 0, 1); ?> kg</h2>
                    <small>CO₂e reduced</small>
                </div>
            </div>
        </div>

        <!-- Eco Rank -->
        <div class="col-md-3 mb-3">
            <div class="card bg-warning text-white shadow-sm">
                <div class="card-body text-center">
                    <h6 class="card-title">📈 Rank</h6>
                    <h2 class="mb-2">#<?php echo $data['user_rank'] ?? 'N/A'; ?></h2>
                    <small>On leaderboard</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Progress to Next Level -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">🎯 Progress to Next Level</h5>
                    <div class="progress" style="height: 30px;">
                        <div class="progress-bar bg-success" role="progressbar" 
                             style="width: <?php echo $data['eco_stats']['progress_percentage'] ?? 0; ?>%"
                             aria-valuenow="<?php echo $data['eco_stats']['progress_percentage'] ?? 0; ?>" 
                             aria-valuemin="0" aria-valuemax="100">
                            <?php echo $data['eco_stats']['progress_percentage'] ?? 0; ?>%
                        </div>
                    </div>
                    <small class="text-muted mt-2 d-block">
                        Need <?php echo number_format(($data['eco_stats']['next_level_tokens'] ?? 0) - ($data['eco_stats']['tokens'] ?? 0)); ?> more tokens for next rank
                    </small>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Orders -->
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">📦 Recent Orders</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($data['orders'])) : ?>
                        <p class="text-muted text-center py-4">No orders yet. <a href="<?php echo URLROOT; ?>/products/browse">Start shopping!</a></p>
                    <?php else : ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Carbon</th>
                                        <th>Tokens</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach (array_slice($data['orders'], 0, 5) as $order) : ?>
                                        <tr>
                                            <td><strong>#<?php echo $order->order_id; ?></strong></td>
                                            <td><?php echo date('M d, Y', strtotime($order->created_at)); ?></td>
                                            <td>₹<?php echo number_format($order->total_amount, 2); ?></td>
                                            <td>
                                                <span class="badge bg-success">
                                                    <?php echo number_format($order->total_carbon, 2); ?> kg
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-primary">
                                                    <?php echo (int)($order->total_carbon * 10); ?> 🪙
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?php echo ($order->payment_status === 'completed') ? 'success' : 'warning'; ?>">
                                                    <?php echo ucfirst($order->payment_status ?? 'pending'); ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php if (count($data['orders']) > 5) : ?>
                            <div class="text-center mt-3">
                                <a href="<?php echo URLROOT; ?>/pages/orderHistory" class="btn btn-sm btn-outline-success">
                                    View All Orders
                                </a>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Side Info -->
        <div class="col-md-4">
            <!-- Eco Tips -->
            <div class="card mb-3 bg-light">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0">💡 Eco Tips</h6>
                </div>
                <div class="card-body">
                    <ul class="small text-muted">
                        <li>Every purchase helps reduce carbon emissions</li>
                        <li>Earn more tokens to unlock rewards</li>
                        <li>Share your eco-journey with friends</li>
                        <li>Reach Green Elite status for exclusive benefits</li>
                    </ul>
                </div>
            </div>

            <!-- Actions -->
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0">⚡ Quick Actions</h6>
                </div>
                <div class="card-body d-grid gap-2">
                    <a href="<?php echo URLROOT; ?>/products/browse" class="btn btn-success btn-sm">
                        <i class="fas fa-shopping-bag"></i> Continue Shopping
                    </a>
                    <a href="<?php echo URLROOT; ?>/cart" class="btn btn-outline-success btn-sm">
                        <i class="fas fa-shopping-cart"></i> View Cart
                    </a>
                    <a href="<?php echo URLROOT; ?>/pages/profile" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-user"></i> Edit Profile
                    </a>
                    <form method="POST" action="<?php echo URLROOT; ?>/auth/logout">
                        <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Carbon Impact Chart Section (Optional - can be extended with Chart.js) -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">📊 Your Impact</h5>
                </div>
                <div class="card-body text-center py-5">
                    <h6 class="text-muted mb-3">You've reduced carbon emissions equivalent to:</h6>
                    <div class="row">
                        <div class="col-md-4">
                            <h3 class="text-success">🌳</h3>
                            <p><?php echo number_format(($data['eco_stats']['carbon_saved'] ?? 0) / 21); ?> Trees<br><small class="text-muted">equivalent CO₂ absorption</small></p>
                        </div>
                        <div class="col-md-4">
                            <h3 class="text-success">🚗</h3>
                            <p><?php echo number_format(($data['eco_stats']['carbon_saved'] ?? 0) / 4.6); ?> Miles<br><small class="text-muted">of car driving</small></p>
                        </div>
                        <div class="col-md-4">
                            <h3 class="text-success">⚡</h3>
                            <p><?php echo number_format(($data['eco_stats']['carbon_saved'] ?? 0) / 0.41); ?> kWh<br><small class="text-muted">of electricity</small></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/Views/inc/footer.php'; ?>