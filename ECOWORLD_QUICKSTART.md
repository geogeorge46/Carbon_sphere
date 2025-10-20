# ⚡ EcoWorld Quick Start - 5 Minutes

## 🚀 Get Started in 5 Minutes

### Step 1: Run Database Migrations (2 min)

1. Open `http://localhost/phpmyadmin`
2. Select `carbon_sphere` database
3. Go to **SQL** tab
4. Paste and run each migration in order:

```sql
-- Copy-paste EACH SQL file content below and click GO

-- File 1: 0011_create_order_items_table.sql
CREATE TABLE IF NOT EXISTS `order_items` (
  `order_item_id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `order_id` INT(11) NOT NULL,
  `product_id` INT(11) NOT NULL,
  `quantity` INT(11) NOT NULL DEFAULT 1,
  `price` DECIMAL(10,2) NOT NULL,
  `carbon_footprint` DECIMAL(10,2) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`order_id`) REFERENCES orders(`order_id`) ON DELETE CASCADE,
  FOREIGN KEY (`product_id`) REFERENCES products(`product_id`) ON DELETE RESTRICT,
  INDEX `idx_order_id` (`order_id`),
  INDEX `idx_product_id` (`product_id`)
);

-- File 2: 0012_enhance_orders_table.sql
ALTER TABLE `orders`
ADD COLUMN `payment_id` VARCHAR(100) NULL UNIQUE,
ADD COLUMN `payment_status` ENUM('pending', 'completed', 'failed', 'refunded') DEFAULT 'pending',
ADD COLUMN `payment_method` VARCHAR(50) DEFAULT 'razorpay',
ADD COLUMN `carbon_tokens_earned` INT(11) DEFAULT 0,
ADD INDEX `idx_payment_id` (`payment_id`),
ADD INDEX `idx_user_id` (`user_id`),
ADD INDEX `idx_created_at` (`created_at`);

-- File 3: 0013_add_eco_tokens_to_users.sql
ALTER TABLE `users`
ADD COLUMN `carbon_tokens` INT(11) DEFAULT 0,
ADD COLUMN `eco_rating` ENUM('Bronze', 'Silver', 'Gold', 'Green Elite') DEFAULT 'Bronze',
ADD COLUMN `total_carbon_saved` DECIMAL(10,2) DEFAULT 0,
ADD INDEX `idx_carbon_tokens` (`carbon_tokens`),
ADD INDEX `idx_eco_rating` (`eco_rating`);

-- File 4: 0014_enhance_products_table.sql
ALTER TABLE `products`
ADD COLUMN `eco_rating` DECIMAL(3,1) DEFAULT 4.5 COMMENT 'Rating from 1-5 for eco-friendliness',
ADD COLUMN `rating_count` INT(11) DEFAULT 0,
ADD COLUMN `stock_quantity` INT(11) DEFAULT 100,
ADD INDEX `idx_eco_rating` (`eco_rating`),
ADD INDEX `idx_created_at` (`created_at`);

-- File 5: 0015_create_payment_transactions.sql
CREATE TABLE IF NOT EXISTS `payment_transactions` (
  `transaction_id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT(11) NOT NULL,
  `order_id` INT(11) NOT NULL,
  `razorpay_order_id` VARCHAR(100) NOT NULL UNIQUE,
  `razorpay_payment_id` VARCHAR(100) NULL,
  `razorpay_signature` VARCHAR(255) NULL,
  `amount` DECIMAL(10,2) NOT NULL,
  `status` ENUM('initiated', 'successful', 'failed', 'cancelled') DEFAULT 'initiated',
  `response_data` JSON NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES users(`user_id`) ON DELETE CASCADE,
  FOREIGN KEY (`order_id`) REFERENCES orders(`order_id`) ON DELETE CASCADE,
  INDEX `idx_user_id` (`user_id`),
  INDEX `idx_order_id` (`order_id`),
  INDEX `idx_razorpay_order_id` (`razorpay_order_id`),
  INDEX `idx_status` (`status`)
);
```

✅ All migrations should complete without errors!

### Step 2: Test the Module (3 min)

1. **Register a new user** (or login if you have one)
   - URL: `http://localhost/carbon-sphere/public/auth/register`
   - Fill all fields and register

2. **Browse Products**
   - URL: `http://localhost/carbon-sphere/public/products/browse`
   - See all products with eco ratings
   - Try searching/sorting

3. **Add to Cart**
   - Click "Add to Cart" on any product
   - Add 2-3 products

4. **Go to Cart**
   - URL: `http://localhost/carbon-sphere/public/cart/view`
   - See cart totals and carbon footprint
   - See estimated tokens

5. **Checkout (Test Payment)**
   - Click "Proceed to Payment"
   - Fill delivery address
   - Click "Pay Now"
   - Use test card: `4111 1111 1111 1111`
   - Any future expiry: `12/25`
   - Any CVV: `123`
   - Click Pay

6. **View Dashboard**
   - Should redirect to dashboard automatically
   - See success message
   - View carbon tokens awarded
   - Check order history

---

## 📱 Feature Checklist

After completing above steps, verify these work:

### Product Browsing
- [ ] Search functionality
- [ ] Sort by price/eco-friendly
- [ ] Filter by category (if added)
- [ ] Product images display
- [ ] Carbon footprint shown
- [ ] Add to Cart button works

### Shopping Cart
- [ ] Products appear in cart
- [ ] Can update quantities
- [ ] Total amount calculates correctly
- [ ] Carbon footprint totals shown
- [ ] Can remove items
- [ ] Cart can be cleared

### Checkout & Payment
- [ ] Delivery address form visible
- [ ] "Pay Now" opens Razorpay popup
- [ ] Can complete test payment
- [ ] Success message displays
- [ ] Redirects to dashboard

### Dashboard
- [ ] Carbon tokens displayed
- [ ] Eco rating shown (Bronze/Silver/Gold/Green Elite)
- [ ] Carbon saved shows
- [ ] Recent orders appear
- [ ] Progress bar shows next level

---

## 📁 New Files Added

```
✅ Services/
   - PaymentService.php       (Razorpay integration)
   - CartService.php          (Cart management)
   - EcoTokenService.php      (Token system)

✅ Controllers/
   - CartController.php       (Cart endpoints)
   - CheckoutController.php   (Payment endpoints)
   - Pages.php (updated)      (Added dashboard)
   - ProductController.php (updated) (Added browse)

✅ Views/
   - products/browse.php      (Product catalog)
   - cart/view.php            (Cart display)
   - checkout/index.php       (Checkout form)
   - pages/buyer-dashboard.php (User dashboard)

✅ Database/
   - 0011-0015_*.sql          (5 new migrations)

✅ Documentation/
   - ECOWORLD_SETUP.md        (Full guide)
   - ECOWORLD_QUICKSTART.md   (This file)
```

---

## 🎯 Key URLs

| Feature | URL |
|---------|-----|
| Browse Products | `/products/browse` |
| Shopping Cart | `/cart/view` |
| Checkout | `/checkout/index` |
| My Dashboard | `/pages/dashboard` |

---

## 🔧 Troubleshooting

**"Call to undefined function flash()"**
→ Already fixed! This function is now in `session_helper.php`

**"table cart_items doesn't exist"**
→ Run migration 0011

**Payment not working?**
→ Make sure you used test card exactly: `4111 1111 1111 1111`
→ Check browser console (F12) for errors

**Orders not showing?**
→ Run all 5 migrations in order
→ Verify order was created in phpMyAdmin: `SELECT * FROM orders;`

---

## 💡 What's New?

| What | Details |
|------|---------|
| **OOP Services** | 3 professional service classes |
| **Payment Gateway** | Razorpay integration (test mode ready) |
| **Token System** | Earn tokens for eco purchases |
| **Eco Ranking** | Bronze → Silver → Gold → Green Elite |
| **Dashboard** | Track all your eco stats |
| **Shopping** | Full product browse & cart |
| **Security** | HMAC signature verification |

---

## 🎓 How It Works

1. User registers and logs in
2. Browses eco-friendly products
3. Adds items to cart (CartService manages)
4. Goes to checkout
5. Enters delivery info
6. Clicks "Pay Now"
7. Razorpay popup opens (secure payment)
8. After payment, order created
9. Carbon tokens calculated & awarded (EcoTokenService)
10. Redirects to dashboard showing new stats

---

## 🚀 Next Steps

After verification:
1. Add more products via seller dashboard
2. Encourage users to make purchases
3. Monitor leaderboard (EcoTokenService has `getEcoLeaderboard()`)
4. Track carbon impact on dashboard

---

**Ready?** Start at Step 1! 🚀

Questions? Check `ECOWORLD_SETUP.md` for detailed documentation.