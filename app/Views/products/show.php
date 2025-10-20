<?php require APPROOT . '/Views/inc/header.php'; ?>

<style>
  /* Product detail page - Clean white background */
  body { background: #ffffff !important; color: #333 !important; }
  .container { background: #ffffff; margin-top: 30px; margin-bottom: 30px; }
  
  .product-detail-header {
    background: white;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  }
  
  .back-button {
    display: inline-block;
    margin-bottom: 20px;
    padding: 10px 20px;
    background: #f0f0f0;
    border-radius: 6px;
    color: #333;
    text-decoration: none;
    transition: all 0.3s ease;
  }
  
  .back-button:hover {
    background: #e0e0e0;
    color: #0d7e4d;
  }
  
  .product-detail-container {
    background: white;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    overflow: hidden;
  }
  
  .product-detail-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 40px;
    padding: 40px;
  }
  
  .product-image-section img {
    width: 100%;
    max-width: 400px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
  }
  
  .product-info-section h1 {
    color: #0d7e4d;
    font-weight: 900;
    font-size: 2.2rem;
    margin-bottom: 15px;
  }
  
  .product-meta {
    background: #f5f5f5;
    padding: 15px;
    border-radius: 6px;
    margin-bottom: 20px;
    border-left: 4px solid #0d7e4d;
  }
  
  .product-meta p {
    margin: 8px 0;
    font-size: 0.95rem;
    color: #666;
  }
  
  .product-description-section {
    margin-bottom: 30px;
  }
  
  .product-description-section h4 {
    color: #0d7e4d;
    margin-bottom: 15px;
    font-weight: 700;
  }
  
  .product-description-section p {
    line-height: 1.8;
    color: #555;
  }
  
  .product-specs {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin: 30px 0;
    padding: 20px;
    background: #f9f9f9;
    border-radius: 8px;
  }
  
  .spec-item h5 {
    color: #0d7e4d;
    margin-bottom: 10px;
    font-weight: 700;
  }
  
  .price-display {
    font-size: 2.5rem;
    color: #0d7e4d;
    font-weight: 900;
    margin-bottom: 10px;
  }
  
  .carbon-info {
    display: inline-block;
    background: #e8f5e9;
    padding: 12px 20px;
    border-radius: 8px;
    color: #0d7e4d;
    font-weight: 700;
    margin-bottom: 30px;
  }
  
  .quantity-section {
    margin: 30px 0;
  }
  
  .quantity-section label {
    display: block;
    color: #333;
    font-weight: 600;
    margin-bottom: 10px;
  }
  
  .quantity-input {
    width: 100px;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 1rem;
    background: white;
    color: #333;
  }
  
  .action-buttons {
    display: flex;
    gap: 15px;
    margin: 30px 0;
  }
  
  .add-to-cart-btn {
    flex: 1;
    padding: 15px 30px;
    background: linear-gradient(135deg, #0d7e4d, #1ab366);
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: 700;
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.3s ease;
  }
  
  .add-to-cart-btn:hover {
    background: linear-gradient(135deg, #0a5f3b, #158a52);
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(13, 126, 77, 0.3);
  }
  
  .edit-btn, .delete-btn {
    padding: 12px 25px;
    border: none;
    border-radius: 6px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s ease;
  }
  
  .edit-btn {
    background: #6c757d;
    color: white;
  }
  
  .edit-btn:hover {
    background: #5a6268;
    transform: translateY(-2px);
  }
  
  .delete-btn {
    background: #dc3545;
    color: white;
  }
  
  .delete-btn:hover {
    background: #c82333;
    transform: translateY(-2px);
  }
  
  .divider {
    border-top: 1px solid #eee;
    margin: 30px 0;
  }
  
  @media (max-width: 768px) {
    .product-detail-row {
      grid-template-columns: 1fr;
      padding: 20px;
      gap: 20px;
    }
    
    .product-specs {
      grid-template-columns: 1fr;
    }
    
    .price-display {
      font-size: 2rem;
    }
    
    .action-buttons {
      flex-direction: column;
    }
  }
</style>

<div class="container">
  <a href="<?php echo URLROOT; ?>/products" class="back-button">← Back to All Products</a>
  
  <div class="product-detail-container">
    <div class="product-detail-row">
      <!-- Product Image -->
      <div class="product-image-section">
        <?php if($data['product']->image_url): ?>
          <img src="<?php echo htmlspecialchars($data['product']->image_url); ?>" alt="<?php echo htmlspecialchars($data['product']->product_name); ?>">
        <?php else: ?>
          <div style="width: 100%; height: 400px; background: linear-gradient(135deg, #e0f2f1, #c8e6c9); display: flex; align-items: center; justify-content: center; border-radius: 8px;">
            <div style="text-align: center;">
              <div style="font-size: 3rem; margin-bottom: 10px;">📦</div>
              <div style="color: #0d7e4d; font-weight: bold;">Product Image Not Available</div>
            </div>
          </div>
        <?php endif; ?>
      </div>
      
      <!-- Product Details -->
      <div class="product-info-section">
        <h1><?php echo htmlspecialchars($data['product']->product_name); ?></h1>
        
        <!-- Seller & Date Info -->
        <div class="product-meta">
          <p><strong>👤 Seller:</strong> <?php echo htmlspecialchars($data['user']->first_name . ' ' . $data['user']->last_name); ?></p>
          <p><strong>📅 Posted:</strong> <?php echo htmlspecialchars(date('F d, Y', strtotime($data['product']->created_at))); ?></p>
        </div>
        
        <!-- Price & Carbon -->
        <div>
          <div class="price-display">₹<?php echo number_format($data['product']->price, 2); ?></div>
          <div class="carbon-info">🌍 Carbon Footprint: <?php echo htmlspecialchars($data['product']->carbon_footprint); ?> kg CO₂</div>
        </div>
        
        <!-- Description -->
        <div class="product-description-section">
          <h4>📝 Product Description</h4>
          <p><?php echo nl2br(htmlspecialchars($data['product']->description)); ?></p>
        </div>
        
        <!-- Add to Cart Form -->
        <form action="<?php echo URLROOT; ?>/cart/add/<?php echo $data['product']->product_id; ?>" method="post">
          <div class="quantity-section">
            <label for="quantity">Select Quantity:</label>
            <input type="number" id="quantity" name="quantity" class="quantity-input" value="1" min="1" required>
          </div>
          
          <div class="action-buttons">
            <button type="submit" class="add-to-cart-btn">
              🛒 Add to Cart
            </button>
          </div>
        </form>
        
        <!-- Seller Actions (if user is product owner) -->
        <?php if(isset($_SESSION['user_id']) && $data['product']->seller_id == $_SESSION['user_id']): ?>
          <div class="divider"></div>
          <div style="padding-top: 20px;">
            <p style="color: #666; font-size: 0.9rem; margin-bottom: 15px;">⚙️ You are the seller of this product</p>
            <div class="action-buttons">
              <a href="<?php echo URLROOT; ?>/products/edit/<?php echo $data['product']->product_id; ?>" class="edit-btn">
                ✏️ Edit Product
              </a>
              
              <form action="<?php echo URLROOT; ?>/products/delete/<?php echo $data['product']->product_id; ?>" method="post" style="flex: 1;">
                <button type="submit" class="delete-btn" style="width: 100%;">
                  🗑️ Delete Product
                </button>
              </form>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
  
  <!-- Info Box -->
  <div style="background: #e8f5e9; padding: 20px; border-radius: 8px; margin-top: 30px; border-left: 4px solid #0d7e4d;">
    <h5 style="color: #0d7e4d; margin-bottom: 10px; font-weight: 700;">💡 How to proceed:</h5>
    <p style="color: #333; margin: 8px 0;">1. Select your desired quantity above</p>
    <p style="color: #333; margin: 8px 0;">2. Click "Add to Cart" to add this product to your shopping cart</p>
    <p style="color: #333; margin: 8px 0;">3. Go to your cart to review all items and proceed to checkout</p>
    <p style="color: #333; margin: 8px 0;">4. Complete payment with Razorpay (secure & instant)</p>
    <p style="color: #333; margin: 8px 0;">5. Earn Eco Tokens based on your carbon savings! 🪙</p>
  </div>
</div>

<?php require APPROOT . '/Views/inc/footer.php'; ?>