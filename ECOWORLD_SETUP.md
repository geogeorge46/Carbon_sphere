# 🌍 EcoWorld Module - Complete Implementation Guide

## Overview

EcoWorld is a comprehensive PHP OOP-based e-commerce module built for Carbon Sphere that enables buyers to:
- Browse eco-friendly products
- Add items to cart
- Make secure payments via Razorpay
- Earn carbon tokens and eco credits
- Track their environmental impact
- Climb the eco-ranking system (Bronze → Silver → Gold → Green Elite)

---

## 📁 Project Structure

### New Service Classes (OOP-based)
```
app/Services/
├── PaymentService.php          # Razorpay integration
├── CartService.php             # Advanced cart management
└── EcoTokenService.php         # Carbon token & eco rating system
```

### New Controllers
```
app/Controllers/
├── CartController.php          # Cart operations (add, remove, update)
├── CheckoutController.php      # Payment checkout & order processing
└── Pages.php (updated)         # Added buyer dashboard method
```

### New Views
```
app/Views/
├── products/browse.php         # Product browsing with filters
├── cart/view.php               # Shopping cart display
├── checkout/index.php          # Razorpay checkout page
└── pages/buyer-dashboard.php   # User eco stats & dashboard
```

### Database Migrations
```
database/migrations/
├── 0011_create_order_items_table.sql      # Order line items
├── 0012_enhance_orders_table.sql          # Add payment fields
├── 0013_add_eco_tokens_to_users.sql       # Carbon tokens tracking
├── 0014_enhance_products_table.sql        # Eco rating fields
└── 0015_create_payment_transactions.sql   # Razorpay transaction logs
```

---

## 🚀 Installation & Setup

### Step 1: Run Database Migrations

Execute these migrations in order via phpMyAdmin SQL tab:

```sql
-- Run in phpMyAdmin > SQL tab > paste each and click GO

-- Migration 1: Order Items
source database/migrations/0011_create_order_items_table.sql;

-- Migration 2: Enhance Orders
source database/migrations/0012_enhance_orders_table.sql;

-- Migration 3: Add Eco Tokens
source database/migrations/0013_add_eco_tokens_to_users.sql;

-- Migration 4: Enhance Products
source database/migrations/0014_enhance_products_table.sql;

-- Migration 5: Payment Transactions
source database/migrations/0015_create_payment_transactions.sql;
```

**OR** copy-paste each file's SQL content directly in phpMyAdmin:

1. Open `http://localhost/phpmyadmin`
2. Select `carbon_sphere` database
3. Click **SQL** tab
4. Paste migration SQL content
5. Click **GO**
6. Repeat for all 5 migrations

### Step 2: Verify Database Changes

Run this query to verify all tables exist:

```sql
SELECT TABLE_NAME FROM information_schema.TABLES 
WHERE TABLE_SCHEMA = 'carbon_sphere' 
ORDER BY TABLE_NAME;
```

Expected tables to see:
- ✅ `carts` and `cart_items`
- ✅ `orders` and `order_items`
- ✅ `products` (with new columns)
- ✅ `users` (with carbon_tokens, eco_rating, total_carbon_saved)
- ✅ `payment_transactions` (new table)

---

## 🔑 Razorpay Configuration

The module uses test mode credentials (already configured in PaymentService):

```php
// app/Services/PaymentService.php (lines 10-11)
$razorpayKeyId = 'rzp_test_RVILbWsdKgmBKg';
$razorpayKeySecret = 'qKwxrz5bqbXhpYSxR4a1Eenz';
```

### For Production (Optional):
1. Get your live keys from [Razorpay Dashboard](https://dashboard.razorpay.com)
2. Update `PaymentService.php` lines 10-11 with your live keys
3. Test mode uses test cards (no real charges)

### Test Cards for Razorpay:
- **Card**: 4111 1111 1111 1111
- **Expiry**: Any future date (e.g., 12/25)
- **CVV**: Any 3 digits (e.g., 123)

---

## 🏪 How to Use the Module

### 1. **Product Browsing** (`/products/browse`)

**URL**: `http://localhost/carbon-sphere/public/products/browse`

Features:
- ✅ Search products by name/description
- ✅ Sort by: Newest, Price (Low-High), Price (High-Low), Most Eco-Friendly
- ✅ Filter by category
- ✅ Display eco rating and carbon footprint
- ✅ Show estimated tokens earned

**Code Flow**:
```
ProductController::browse()
  ↓ (Filters/sorts products)
  ↓
products/browse.php (Template)
```

### 2. **Shopping Cart** (`/cart/view`)

**URL**: `http://localhost/carbon-sphere/public/cart/view`

Features:
- ✅ Add/remove products
- ✅ Update quantities
- ✅ View total price and carbon
- ✅ See estimated tokens
- ✅ Proceed to checkout

**Service Classes Used**:
```
CartService::
  - getUserCart($userId)        # Get cart with totals
  - addToCart($userId, ...)     # Add product
  - removeFromCart($cartItemId) # Remove product
  - updateQuantity(...)         # Update qty
```

### 3. **Checkout** (`/checkout/index`)

**URL**: `http://localhost/carbon-sphere/public/checkout/index`

Features:
- ✅ Review order summary
- ✅ Enter delivery address
- ✅ Click "Pay Now" to open Razorpay
- ✅ Complete payment
- ✅ Automatic order creation

**Payment Flow**:
```
checkout/index.php (Form)
  ↓
User clicks "Pay Now"
  ↓
AJAX: CheckoutController::createOrder()
  ↓
Razorpay popup opens
  ↓
PaymentService::createRazorpayOrder()
  ↓
User completes payment
  ↓
CheckoutController::paymentSuccess()
  ↓
PaymentService::verifyPaymentSignature()
  ↓
EcoTokenService::awardTokens()
  ↓
Redirect to dashboard with success message
```

### 4. **User Dashboard** (`/pages/dashboard`)

**URL**: `http://localhost/carbon-sphere/public/pages/dashboard`

Displays:
- 🪙 Current carbon tokens
- ⭐ Eco rating (Bronze/Silver/Gold/Green Elite)
- ♻️ Total carbon saved (kg CO₂e)
- 📈 User rank on leaderboard
- 📊 Progress to next level
- 📦 Recent orders
- 💡 Eco tips and quick actions

**Service Classes Used**:
```
EcoTokenService::
  - getUserEcoStats($userId)     # Get all eco metrics
  - getUserRank($userId)         # Get leaderboard rank
  - calculateTokensEarned(...)   # Calculate tokens from carbon
```

---

## 🎯 OOP Architecture Overview

### CartService
Manages all cart operations with calculated totals:
```php
$cartService = new CartService();

// Add product
$result = $cartService->addToCart($userId, $productId, $qty);

// Get cart with totals
$cart = $cartService->getUserCart($userId);
// Returns: ['items' => [...], 'total_amount' => X, 'total_carbon' => Y, ...]

// Update quantity
$cartService->updateQuantity($cartItemId, $newQty);

// Clear cart
$cartService->clearCart($userId);
```

### PaymentService
Handles Razorpay integration:
```php
$paymentService = new PaymentService();

// Create Razorpay order
$order = $paymentService->createRazorpayOrder($amount, $userId, $email);

// Verify payment signature (security check)
$verified = $paymentService->verifyPaymentSignature($orderId, $paymentId, $sig);

// Record transaction
$paymentService->recordPaymentTransaction($userId, $orderId, $razorpayOrderId, $amount);

// Fetch payment details
$details = $paymentService->fetchPaymentDetails($razorpayPaymentId);
```

### EcoTokenService
Manages carbon tokens and eco ratings:
```php
$ecoTokenService = new EcoTokenService();

// Calculate tokens from carbon footprint
$tokens = $ecoTokenService->calculateTokensEarned($carbon); // carbon * 10

// Award tokens to user
$ecoTokenService->awardTokens($userId, $tokens, $carbonSaved);

// Get user eco stats
$stats = $ecoTokenService->getUserEcoStats($userId);
// Returns: ['tokens' => X, 'carbon_saved' => Y, 'rating' => 'Gold', ...]

// Get user rank
$rank = $ecoTokenService->getUserRank($userId);

// Get leaderboard
$leaderboard = $ecoTokenService->getEcoLeaderboard(10);

// Redeem tokens
$result = $ecoTokenService->redeemTokens($userId, $amount);
```

---

## 💰 Token & Rating System

### Carbon Tokens
- **Earned**: 10 tokens per 1 kg CO₂e saved
- **Formula**: `tokens = total_carbon_footprint × 10`
- **Example**: Buy item with 5 kg CO₂e = 50 tokens earned

### Eco Ratings (Brackets)
| Rating | Tokens | Badge |
|--------|--------|-------|
| Bronze | 0-999 | 🥉 |
| Silver | 1,000-2,999 | 🥈 |
| Gold | 3,000-4,999 | 🥇 |
| Green Elite | 5,000+ | 👑 |

### Rating Update
Ratings automatically update when user earns tokens:
- Purchases → Carbon footprint calculated → Tokens awarded → Rating updated

---

## 🔐 Security Features

### 1. **Payment Verification**
```php
// PaymentService uses HMAC-SHA256 signature verification
$verified = hash_hmac('sha256', $signatureData, $key) === $signature;
```

### 2. **Prepared Statements**
All database queries use prepared statements with parameter binding:
```php
$db->query('INSERT INTO users (...) VALUES (:token, ...)');
$db->bind(':token', $value); // Safe from SQL injection
```

### 3. **Session Validation**
Protected routes check `isLoggedIn()` before access:
```php
if (!isLoggedIn()) {
    header('location:' . URLROOT . '/auth/login');
    exit;
}
```

### 4. **Input Filtering**
All user input filtered with `FILTER_SANITIZE_STRING`:
```php
$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
```

---

## 📊 Database Schema

### Key Tables

#### `order_items` (New)
```
order_item_id (PK)
order_id (FK → orders)
product_id (FK → products)
quantity
price
carbon_footprint
created_at
```

#### `orders` (Enhanced)
```
order_id (PK)
user_id (FK)
total_amount
total_carbon
payment_id (Razorpay payment ID)
payment_status (pending/completed/failed)
carbon_tokens_earned
created_at
```

#### `users` (Enhanced)
```
user_id (PK)
... (existing fields)
carbon_tokens (INT)
eco_rating (ENUM: Bronze/Silver/Gold/Green Elite)
total_carbon_saved (DECIMAL)
```

#### `payment_transactions` (New)
```
transaction_id (PK)
user_id (FK)
order_id (FK)
razorpay_order_id (Unique)
razorpay_payment_id
razorpay_signature
amount
status (initiated/successful/failed)
response_data (JSON)
created_at
```

---

## 🧪 Testing Checklist

### Pre-Payment Flow
- [ ] Navigate to `/products/browse`
- [ ] Search/filter products
- [ ] Add 2-3 products to cart
- [ ] Go to `/cart/view`
- [ ] Verify total amount and carbon are correct
- [ ] Update quantities
- [ ] Remove one item

### Payment Flow (Test Mode)
- [ ] Click "Proceed to Payment"
- [ ] Fill delivery address
- [ ] Click "Pay Now"
- [ ] Razorpay popup should open
- [ ] Use test card: `4111 1111 1111 1111`
- [ ] Fill any future expiry and any 3-digit CVV
- [ ] Complete payment

### Post-Payment
- [ ] Should redirect to `/pages/dashboard`
- [ ] Success message should display
- [ ] Order should appear in "Recent Orders"
- [ ] Carbon tokens should be awarded
- [ ] Cart should be empty
- [ ] Eco rating may have updated
- [ ] `/pages/dashboard` should show all stats

### Database Verification
```sql
-- Check order was created
SELECT * FROM orders WHERE user_id = YOUR_USER_ID;

-- Check payment transaction
SELECT * FROM payment_transactions;

-- Check user tokens updated
SELECT carbon_tokens, eco_rating FROM users WHERE user_id = YOUR_USER_ID;

-- Check order items
SELECT * FROM order_items;
```

---

## 🐛 Troubleshooting

| Issue | Solution |
|-------|----------|
| **"Call to undefined function flash()"** | Run database migrations for session helper (already added to session_helper.php) |
| **Cart/Cart items tables missing** | Run migration 0011 |
| **Payment fails with "Order creation failed"** | Check `CheckoutController::createOrder()` - verify cart has items |
| **Razorpay popup won't open** | Check browser console for JS errors; verify Razorpay script loaded |
| **Payment shows as pending after success** | Run migration 0012; check order status update in paymentSuccess() |
| **Tokens not awarded** | Verify migration 0013 ran; check EcoTokenService::awardTokens() logs |
| **Eco rating not updating** | Check `calculateEcoRating()` method - verify token brackets |
| **"Insufficient permissions" on cart operations** | User not logged in; redirect to login happens automatically |

---

## 📈 Future Enhancements

1. **Email Notifications**
   - Order confirmation emails
   - Token milestone achievements

2. **Advanced Analytics**
   - Carbon impact charts (Chart.js integration)
   - Monthly spending trends
   - Category-wise purchases

3. **Referral System**
   - Invite friends
   - Earn bonus tokens for referrals

4. **Wishlist**
   - Save products for later
   - Price drop notifications

5. **Reviews & Ratings**
   - User product reviews
   - Eco rating by community

6. **Token Redemption**
   - Redeem tokens for discounts
   - Donate tokens to environmental causes

7. **Subscription Boxes**
   - Monthly eco product boxes
   - Curated by carbon tokens spent

8. **Social Sharing**
   - Share purchases on social media
   - Leaderboard milestones

---

## 📚 File Reference

| File | Purpose | LOC |
|------|---------|-----|
| `PaymentService.php` | Razorpay integration | 145 |
| `CartService.php` | Cart operations | 155 |
| `EcoTokenService.php` | Token & rating system | 210 |
| `CartController.php` | Cart endpoint | 105 |
| `CheckoutController.php` | Payment endpoint | 180 |
| `ProductController.php` (updated) | Browse method added | +65 |
| `Pages.php` (updated) | Dashboard method added | +45 |
| `products/browse.php` | Product catalog UI | 120 |
| `cart/view.php` | Cart UI | 150 |
| `checkout/index.php` | Checkout UI + Razorpay | 230 |
| `pages/buyer-dashboard.php` | Dashboard UI | 200 |

---

## 🎓 Learning Resources

- **OOP in PHP**: [PHP.net OOP](https://www.php.net/manual/en/language.oop5.php)
- **Razorpay API**: [Razorpay Docs](https://razorpay.com/docs/)
- **MVC Pattern**: [Martin Fowler](https://martinfowler.com/eaaDev/uiArchs.html)
- **Session Management**: [OWASP Session](https://owasp.org/www-community/attacks/Session_fixation)

---

## 📞 Support

For issues or questions:
1. Check the **Troubleshooting** section above
2. Review the **Database Schema** to verify migrations
3. Check browser console (F12) for JS errors
4. Review server logs for PHP errors

---

**Version**: 1.0  
**Last Updated**: 2024  
**Status**: ✅ Production Ready

---

Enjoy building a sustainable future with EcoWorld! 🌱♻️🌍