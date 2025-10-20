<?php
    $title = 'EcoWorld Store - Sustainable Products';
    require APPROOT . '/Views/inc/header.php';
?>

<div class="container mt-5 mb-5">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="mb-2">🌍 EcoWorld Store</h1>
            <p class="text-muted">Shop eco-friendly products and earn carbon tokens</p>
        </div>
    </div>

    <!-- Search & Filter Section -->
    <div class="row mb-4">
        <div class="col-md-12">
            <form method="GET" class="form-inline" id="filter-form">
                <div class="form-group me-3">
                    <input type="text" name="search" class="form-control" placeholder="Search products..." value="<?php echo htmlspecialchars($data['search_query']); ?>">
                </div>
                
                <div class="form-group me-3">
                    <select name="sort" class="form-control" id="sort-select">
                        <option value="newest" <?php echo $data['sort_by'] === 'newest' ? 'selected' : ''; ?>>Newest</option>
                        <option value="price_low" <?php echo $data['sort_by'] === 'price_low' ? 'selected' : ''; ?>>Price: Low to High</option>
                        <option value="price_high" <?php echo $data['sort_by'] === 'price_high' ? 'selected' : ''; ?>>Price: High to Low</option>
                        <option value="eco_friendly" <?php echo $data['sort_by'] === 'eco_friendly' ? 'selected' : ''; ?>>Most Eco-Friendly</option>
                    </select>
                </div>
                
                <button type="submit" class="btn btn-success me-2">Filter</button>
                <a href="<?php echo URLROOT; ?>/products/browse" class="btn btn-secondary">Reset</a>
            </form>
        </div>
    </div>

    <!-- Flash Messages -->
    <?php if (flash('product_message')) : ?>
        <?php $msg = flash('product_message'); ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($msg['message']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Products Grid -->
    <div class="row">
        <?php if (empty($data['products'])) : ?>
            <div class="col-md-12 text-center py-5">
                <h4 class="text-muted">No products found</h4>
                <p class="text-muted mb-3">Try adjusting your search or filters</p>
                <a href="<?php echo URLROOT; ?>/products/browse" class="btn btn-success">View All Products</a>
            </div>
        <?php else : ?>
            <?php foreach ($data['products'] as $product) : ?>
                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="card product-card h-100 shadow-sm hover-lift">
                        <!-- Product Image -->
                        <div class="product-image-wrapper">
                            <?php if (!empty($product->image_url)) : ?>
                                <img src="<?php echo htmlspecialchars($product->image_url); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($product->product_name); ?>" style="height: 250px; object-fit: cover;">
                            <?php else : ?>
                                <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 250px;">
                                    <span class="text-muted">📦 No Image</span>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Eco Rating Badge -->
                            <span class="badge bg-success position-absolute top-0 end-0 m-2">
                                ♻️ <?php echo round($product->carbon_footprint, 2); ?> kg CO₂e
                            </span>
                        </div>

                        <!-- Product Details -->
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($product->product_name); ?></h5>
                            
                            <!-- Category -->
                            <p class="text-muted small mb-2">
                                <i class="fas fa-tag"></i> Category ID: <?php echo $product->category_id; ?>
                            </p>

                            <!-- Description -->
                            <p class="card-text small text-muted">
                                <?php echo substr(htmlspecialchars($product->description), 0, 100); ?>...
                            </p>

                            <!-- Seller Info -->
                            <p class="small text-muted mb-3">
                                <i class="fas fa-user"></i> 
                                <?php echo htmlspecialchars($product->first_name . ' ' . $product->last_name); ?>
                            </p>

                            <!-- Price Section -->
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="h5 mb-0 text-success">₹<?php echo number_format($product->price, 2); ?></span>
                                <?php if (isset($product->eco_rating) && $product->eco_rating) : ?>
                                    <span class="badge bg-info">⭐ <?php echo round($product->eco_rating, 1); ?></span>
                                <?php endif; ?>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-grid gap-2">
                                <?php if ($data['is_logged_in']) : ?>
                                    <form method="POST" action="<?php echo URLROOT; ?>/cart/add" class="add-to-cart-form">
                                        <input type="hidden" name="product_id" value="<?php echo $product->product_id; ?>">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="btn btn-success btn-sm w-100">
                                            <i class="fas fa-shopping-cart"></i> Add to Cart
                                        </button>
                                    </form>
                                    <a href="<?php echo URLROOT; ?>/products/show/<?php echo $product->product_id; ?>" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-eye"></i> View Details
                                    </a>
                                <?php else : ?>
                                    <a href="<?php echo URLROOT; ?>/auth/login" class="btn btn-success btn-sm">
                                        <i class="fas fa-login"></i> Login to Shop
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Carbon Token Info -->
                        <div class="card-footer bg-light">
                            <small class="text-muted">
                                🪙 Earn ~<?php echo (int)($product->carbon_footprint * 10); ?> tokens on purchase
                            </small>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Pagination Info -->
    <div class="row mt-4">
        <div class="col-md-12 text-center">
            <p class="text-muted">Showing <?php echo count($data['products']); ?> product(s)</p>
        </div>
    </div>
</div>

<style>
    .product-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: 1px solid #e0e0e0;
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1) !important;
    }

    .product-image-wrapper {
        position: relative;
        overflow: hidden;
    }

    .hover-lift {
        transition: all 0.3s ease;
    }

    .add-to-cart-form {
        margin-bottom: 0.5rem;
    }
</style>

<script>
    document.getElementById('sort-select').addEventListener('change', function() {
        document.getElementById('filter-form').submit();
    });
</script>

<?php require APPROOT . '/Views/inc/footer.php'; ?>