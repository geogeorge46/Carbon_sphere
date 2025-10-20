<?php
    $title = 'EcoWorld Store - Sustainable Products';
    require APPROOT . '/Views/inc/header.php';
?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/home-page.css">

<!-- Hero Section -->
<div class="hero-section" style="padding: 100px 0;">
    <div class="container">
        <h1 style="font-size: 3.5rem; margin-bottom: 15px;">🛍️ EcoWorld Store</h1>
        <p class="hero-tagline">Shop sustainable products and earn rewards</p>
    </div>
</div>

<div class="container mt-5 mb-5">
    <!-- Search & Filter Section -->
    <div class="row mb-5">
        <div class="col-md-12">
            <form method="GET" class="filter-form-container" id="filter-form">
                <div class="filter-group">
                    <input type="text" name="search" class="filter-search" placeholder="🔍 Search products..." value="<?php echo htmlspecialchars($data['search_query']); ?>">
                </div>
                
                <div class="filter-group">
                    <select name="sort" class="filter-select" id="sort-select">
                        <option value="newest" <?php echo $data['sort_by'] === 'newest' ? 'selected' : ''; ?>>⭐ Newest</option>
                        <option value="price_low" <?php echo $data['sort_by'] === 'price_low' ? 'selected' : ''; ?>>💰 Price: Low to High</option>
                        <option value="price_high" <?php echo $data['sort_by'] === 'price_high' ? 'selected' : ''; ?>>💰 Price: High to Low</option>
                        <option value="eco_friendly" <?php echo $data['sort_by'] === 'eco_friendly' ? 'selected' : ''; ?>>🌱 Most Eco-Friendly</option>
                    </select>
                </div>
                
                <button type="submit" class="btn-filter-apply">Filter</button>
                <a href="<?php echo URLROOT; ?>/products/browse" class="btn-filter-reset">Reset</a>
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
    <div class="row g-4">
        <?php if (empty($data['products'])) : ?>
            <div class="col-md-12">
                <div class="empty-state">
                    <div class="empty-state-icon">📦</div>
                    <h3>No Products Found</h3>
                    <p>Try adjusting your search or filters to find what you're looking for</p>
                    <a href="<?php echo URLROOT; ?>/products/browse" class="btn" style="background: linear-gradient(135deg, #0d7e4d, #1ab366); color: white; border-radius: 50px; padding: 12px 40px; font-weight: 700; text-decoration: none; display: inline-block;">View All Products</a>
                </div>
            </div>
        <?php else : ?>
            <?php foreach ($data['products'] as $product) : ?>
                <div class="col-md-4 col-sm-6">
                    <div class="product-card">
                        <div class="product-image">
                            <?php if (!empty($product->image_url)) : ?>
                                <img src="<?php echo htmlspecialchars($product->image_url); ?>" alt="<?php echo htmlspecialchars($product->product_name); ?>">
                            <?php else : ?>
                                <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #e0f2f1, #c8e6c9);">
                                    <span style="color: #0d7e4d; font-weight: bold;">📦 No Image</span>
                                </div>
                            <?php endif; ?>
                            <span class="product-badge eco">♻️ <?php echo round($product->carbon_footprint, 2); ?> kg CO₂e</span>
                        </div>

                        <div class="product-body">
                            <h5 class="product-title"><?php echo htmlspecialchars($product->product_name); ?></h5>
                            
                            <p class="product-description">
                                <?php echo htmlspecialchars(substr($product->description, 0, 80)); ?>...
                            </p>
                            
                            <p style="font-size: 0.9rem; color: #666; margin-bottom: 12px;">
                                👤 <?php echo htmlspecialchars($product->first_name . ' ' . $product->last_name); ?>
                            </p>

                            <div class="product-footer">
                                <div class="product-price">₹<?php echo number_format($product->price, 2); ?></div>
                                <span class="product-carbon">🪙 <?php echo (int)($product->carbon_footprint * 10); ?> tokens</span>
                            </div>
                        </div>

                        <div style="padding: 15px 20px; border-top: 1px solid #eee;">
                            <?php if ($data['is_logged_in']) : ?>
                                <form method="POST" action="<?php echo URLROOT; ?>/cart/add" style="margin-bottom: 10px;">
                                    <input type="hidden" name="product_id" value="<?php echo $product->product_id; ?>">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" style="width: 100%; padding: 10px; background: linear-gradient(135deg, #0d7e4d, #1ab366); color: white; border: none; border-radius: 8px; font-weight: 700; cursor: pointer; transition: all 0.3s ease;">
                                        🛒 Add to Cart
                                    </button>
                                </form>
                                <a href="<?php echo URLROOT; ?>/products/show/<?php echo $product->product_id; ?>" class="btn-view" style="text-decoration: none; display: block; text-align: center;">
                                    👁️ View Details
                                </a>
                            <?php else : ?>
                                <a href="<?php echo URLROOT; ?>/auth/login" class="btn-view" style="text-decoration: none; display: block; text-align: center;">
                                    🔐 Login to Shop
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Info Footer -->
    <div class="row mt-5 pt-4">
        <div class="col-md-12 text-center">
            <p style="color: #666; font-size: 0.95rem;">Showing <strong><?php echo count($data['products']); ?></strong> product(s) • Start shopping sustainable today! 🌱</p>
        </div>
    </div>
</div>

<style>
    /* Override body background */
    body { background: #ffffff !important; }
    
    /* Filter Form Styling */
    .filter-form-container {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
        align-items: center;
        background: white;
        padding: 25px;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    }

    .filter-group {
        flex: 1;
        min-width: 200px;
    }

    .filter-search, .filter-select {
        width: 100%;
        padding: 12px 18px;
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        font-family: inherit;
    }

    .filter-search:focus, .filter-select:focus {
        outline: none;
        border-color: #0d7e4d;
        box-shadow: 0 0 0 3px rgba(13, 126, 77, 0.1);
    }

    .btn-filter-apply {
        padding: 12px 28px;
        background: linear-gradient(135deg, #0d7e4d, #1ab366);
        color: white;
        border: none;
        border-radius: 10px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-filter-apply:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(13, 126, 77, 0.3);
    }

    .btn-filter-reset {
        padding: 12px 28px;
        background: white;
        color: #0d7e4d;
        border: 2px solid #0d7e4d;
        border-radius: 10px;
        font-weight: 700;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-block;
    }

    .btn-filter-reset:hover {
        background: #f5f5f5;
        transform: translateY(-2px);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 80px 40px;
        background: linear-gradient(135deg, #f8f9fa, #e0f2f1);
        border-radius: 20px;
    }

    .empty-state-icon {
        font-size: 4rem;
        margin-bottom: 20px;
    }

    .empty-state h3 {
        font-size: 2rem;
        color: #0d7e4d;
        font-weight: 900;
        margin-bottom: 10px;
    }

    .empty-state p {
        color: #666;
        font-size: 1rem;
        margin-bottom: 30px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .filter-form-container {
            flex-direction: column;
            gap: 12px;
        }

        .filter-group {
            width: 100%;
        }

        .btn-filter-apply, .btn-filter-reset {
            width: 100%;
        }
    }
</style>

<script>
    document.getElementById('sort-select').addEventListener('change', function() {
        document.getElementById('filter-form').submit();
    });
</script>

<?php require APPROOT . '/Views/inc/footer.php'; ?>