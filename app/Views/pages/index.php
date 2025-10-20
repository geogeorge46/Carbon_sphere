<?php require APPROOT . '/Views/inc/header.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/home-page.css">

<style>
  /* Override body background for home page only */
  body { background: #ffffff !important; color: #333 !important; }
  .container { max-width: 1200px; margin: 0 auto; padding: 0 15px; }
</style>

<!-- Hero Section -->
<div class="hero-section">
  <div class="container">
    <div class="hero-content">
      <h1>🌍 Welcome to EcoWorld</h1>
      <p class="hero-tagline">Shop sustainable, save the planet, earn rewards</p>
      
      <div class="hero-cta">
        <a href="<?php echo URLROOT; ?>/auth/register" class="btn btn-primary-cta">Join Now</a>
        <a href="<?php echo URLROOT; ?>/products" class="btn btn-secondary-cta">Browse Products</a>
      </div>

      <div class="hero-stats">
        <div class="hero-stat">
          <span class="hero-stat-number">10K+</span>
          <span class="hero-stat-label">Products</span>
        </div>
        <div class="hero-stat">
          <span class="hero-stat-number">50K+</span>
          <span class="hero-stat-label">Customers</span>
        </div>
        <div class="hero-stat">
          <span class="hero-stat-number">500K+</span>
          <span class="hero-stat-label">CO₂ Saved</span>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Features Section -->
<div class="features-section">
  <div class="container">
    <div class="row">
      <div class="col-md-3">
        <div class="feature-card">
          <div class="feature-icon">🌱</div>
          <h4>Eco-Friendly</h4>
          <p>100% sustainable products from verified sellers who care about the planet</p>
        </div>
      </div>
      <div class="col-md-3">
        <div class="feature-card">
          <div class="feature-icon">💚</div>
          <h4>Track Impact</h4>
          <p>Monitor your carbon savings with our Eco Tokens and see your impact grow</p>
        </div>
      </div>
      <div class="col-md-3">
        <div class="feature-card">
          <div class="feature-icon">🚚</div>
          <h4>Fast Shipping</h4>
          <p>Quick delivery from local eco-conscious sellers near you</p>
        </div>
      </div>
      <div class="col-md-3">
        <div class="feature-card">
          <div class="feature-icon">🛡️</div>
          <h4>Secure & Safe</h4>
          <p>Protected transactions and trusted sellers with verified reviews</p>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Stats Section -->
<div class="stats-section">
  <div class="container">
    <div class="row">
      <div class="col-md-4">
        <div class="stat-box">
          <span class="stat-number">10M+</span>
          <span class="stat-label">Tons of CO₂ Prevented</span>
        </div>
      </div>
      <div class="col-md-4">
        <div class="stat-box">
          <span class="stat-number">500K+</span>
          <span class="stat-label">Active Community Members</span>
        </div>
      </div>
      <div class="col-md-4">
        <div class="stat-box">
          <span class="stat-number">₹50M+</span>
          <span class="stat-label">Eco Tokens Distributed</span>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Categories Section -->
<div class="categories-section">
  <div class="container">
    <h2 class="section-title">Shop by Category</h2>
    <div class="row">
      <div class="col-md-3">
        <div class="category-item">
          <div class="category-icon">🌿</div>
          <h5>Organic & Natural</h5>
        </div>
      </div>
      <div class="col-md-3">
        <div class="category-item">
          <div class="category-icon">♻️</div>
          <h5>Recycled Products</h5>
        </div>
      </div>
      <div class="col-md-3">
        <div class="category-item">
          <div class="category-icon">⚡</div>
          <h5>Energy Efficient</h5>
        </div>
      </div>
      <div class="col-md-3">
        <div class="category-item">
          <div class="category-icon">🌾</div>
          <h5>Fair Trade</h5>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Featured Products Section -->
<div class="products-section">
  <div class="container">
    <h2 class="section-title">✨ Featured Products</h2>
    <p class="section-subtitle">Handpicked sustainable products from verified eco-conscious sellers</p>
    
    <?php if(!empty($products)): ?>
      <div class="row g-4 mb-5">
        <?php 
          $featured = array_slice($products, 0, 6);
          foreach($featured as $product): 
        ?>
          <div class="col-md-4 mb-4">
            <div class="product-card">
              <div class="product-image">
                <?php if($product->image_url): ?>
                  <img src="<?php echo htmlspecialchars($product->image_url); ?>" alt="<?php echo htmlspecialchars($product->product_name); ?>">
                <?php else: ?>
                  <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #e0f2f1, #c8e6c9);">
                    <span style="color: #0d7e4d; font-weight: bold;">No Image</span>
                  </div>
                <?php endif; ?>
                <span class="product-badge eco">⭐ FEATURED</span>
              </div>
              
              <div class="product-body">
                <h5 class="product-title"><?php echo htmlspecialchars($product->product_name); ?></h5>
                <p class="product-description">
                  <?php echo htmlspecialchars(substr($product->description, 0, 80)); ?>...
                </p>
                
                <div class="product-footer">
                  <div class="product-price">$<?php echo number_format($product->price, 2); ?></div>
                  <span class="product-carbon">🌍 <?php echo htmlspecialchars($product->carbon_footprint); ?>kg</span>
                </div>
              </div>
              
              <a href="<?php echo URLROOT; ?>/products/show/<?php echo $product->product_id; ?>" class="btn-view">
                View Details →
              </a>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
      
      <div class="text-center mb-5">
        <a href="<?php echo URLROOT; ?>/products" class="btn btn-lg" style="background: linear-gradient(135deg, #0d7e4d, #1ab366); color: white; border-radius: 50px; padding: 15px 50px; font-weight: 700;">
          🛒 Browse All Products →
        </a>
      </div>
    <?php else: ?>
      <div class="empty-state">
        <div class="empty-state-icon">📦</div>
        <h3>No Products Available Yet</h3>
        <p>Check back soon for amazing eco-friendly products from our sellers!</p>
      </div>
    <?php endif; ?>
  </div>
</div>

<!-- Testimonials Section -->
<div class="testimonials-section">
  <div class="container">
    <h2 class="section-title">💬 What Our Customers Say</h2>
    <div class="row">
      <div class="col-md-4">
        <div class="testimonial-card">
          <div class="stars">⭐⭐⭐⭐⭐</div>
          <p class="testimonial-text">"Carbon Sphere has completely changed how I shop. I feel great knowing my purchases are helping the planet!"</p>
          <div class="testimonial-author">👤 Sarah Johnson</div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="testimonial-card">
          <div class="stars">⭐⭐⭐⭐⭐</div>
          <p class="testimonial-text">"Love the Eco Tokens feature! I'm actually rewarded for making sustainable choices. Brilliant!"</p>
          <div class="testimonial-author">👤 Michael Chen</div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="testimonial-card">
          <div class="stars">⭐⭐⭐⭐⭐</div>
          <p class="testimonial-text">"The quality of products is amazing, and knowing my purchase reduces carbon footprint is priceless."</p>        
          <div class="testimonial-author">👤 Emma Williams</div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Newsletter Section -->
<div class="newsletter-section">
  <div class="container">
    <div class="newsletter-content">
      <h3>📧 Stay Updated</h3>
      <p>Subscribe to our newsletter and get exclusive deals, tips, and updates on new eco-friendly products</p>
      <form class="newsletter-form">
        <input type="email" placeholder="Enter your email" required>
        <button type="submit">Subscribe</button>
      </form>
    </div>
  </div>
</div>

<!-- CTA Section -->
<div class="container">
  <div class="cta-section">
    <h2>🌟 Ready to Make a Difference?</h2>
    <p>Join our community of 500K+ conscious shoppers and start earning Eco Tokens today</p>
    <a href="<?php echo URLROOT; ?>/auth/register" class="btn btn-light btn-lg" style="border-radius: 50px; padding: 15px 50px; font-weight: 700;">   
      ✨ Get Started Today
    </a>
  </div>
</div>

<?php require APPROOT . '/Views/inc/footer.php'; ?>