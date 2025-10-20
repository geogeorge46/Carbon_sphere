# Seller Dashboard - Quick Setup Guide

## Files Created

### Controllers
- ✅ `app/Controllers/SellerController.php` - Main seller dashboard controller

### Models (Updated)
- ✅ `app/Models/Order.php` - Added seller-specific queries
- ✅ `app/Models/Product.php` - Added seller-specific queries

### Views
- ✅ `app/Views/seller/layout.php` - Main dashboard layout
- ✅ `app/Views/seller/layout_end.php` - Layout closing tags
- ✅ `app/Views/seller/dashboard.php` - Dashboard overview
- ✅ `app/Views/seller/add_product.php` - Add product form
- ✅ `app/Views/seller/edit_product.php` - Edit product form
- ✅ `app/Views/seller/my_products.php` - Products list
- ✅ `app/Views/seller/my_orders.php` - Orders list
- ✅ `app/Views/seller/order_details.php` - Order details view
- ✅ `app/Views/seller/report.php` - Earnings & carbon report

### Styles & Scripts
- ✅ `public/css/seller-dashboard.css` - Dashboard styling
- ✅ `public/js/seller-dashboard.js` - Dashboard JavaScript

### Documentation
- ✅ `SELLER_DASHBOARD.md` - Complete documentation
- ✅ `SELLER_DASHBOARD_SETUP.md` - This file

## Prerequisites

1. **CarbonSphere Project** - Already installed and running
2. **Database** - `carbon_sphere` database with all migrations
3. **User Account** - User with `role = 'seller'`

## Step-by-Step Setup

### Step 1: Verify Database Schema

Ensure the `users` table has the `role` column:

```sql
DESCRIBE users;
```

Expected columns:
- `user_id` (INT)
- `first_name` (VARCHAR)
- `last_name` (VARCHAR)
- `email` (VARCHAR)
- `phone_number` (VARCHAR)
- `password` (VARCHAR)
- `role` (ENUM: 'seller', 'buyer', 'admin')
- `created_at` (DATETIME)

### Step 2: Update User Role

Set a user account as a seller:

```sql
UPDATE users SET role = 'seller' WHERE user_id = 1;
```

Or create a new seller account:

```sql
INSERT INTO users (first_name, last_name, email, phone_number, password, role)
VALUES ('John', 'Seller', 'seller@example.com', '9876543210', 
        '$2y$10$...hashed_password...', 'seller');
```

### Step 3: Clear Browser Cache

- Clear cookies for `localhost`
- Clear browser cache
- Close and reopen browser

### Step 4: Test the Dashboard

1. **Login**: Go to `http://localhost/carbon-sphere/public/auth/login`
2. **Enter Credentials**: Use the seller account credentials
3. **Access Dashboard**: 
   - Look for "Seller Dashboard" link in navbar
   - Or visit directly: `http://localhost/carbon-sphere/public/seller`

## URL Routes

Once installed, the following URLs are active:

```
http://localhost/carbon-sphere/public/seller
http://localhost/carbon-sphere/public/seller/addProduct
http://localhost/carbon-sphere/public/seller/myProducts
http://localhost/carbon-sphere/public/seller/editProduct/1
http://localhost/carbon-sphere/public/seller/deleteProduct/1
http://localhost/carbon-sphere/public/seller/myOrders
http://localhost/carbon-sphere/public/seller/orderDetails/1
http://localhost/carbon-sphere/public/seller/report
```

## File Structure

```
carbon-sphere/
├── app/
│   ├── Controllers/
│   │   └── SellerController.php          [NEW]
│   ├── Models/
│   │   ├── Order.php                     [UPDATED]
│   │   ├── Product.php                   [UPDATED]
│   │   └── ...
│   └── Views/
│       ├── seller/                       [NEW FOLDER]
│       │   ├── layout.php                [NEW]
│       │   ├── layout_end.php            [NEW]
│       │   ├── dashboard.php             [NEW]
│       │   ├── add_product.php           [NEW]
│       │   ├── edit_product.php          [NEW]
│       │   ├── my_products.php           [NEW]
│       │   ├── my_orders.php             [NEW]
│       │   ├── order_details.php         [NEW]
│       │   └── report.php                [NEW]
│       └── inc/
│           └── header.php                [UPDATED]
├── public/
│   ├── css/
│   │   └── seller-dashboard.css          [NEW]
│   └── js/
│       └── seller-dashboard.js           [NEW]
└── SELLER_DASHBOARD.md                   [NEW]
```

## Features Summary

### ✅ Implemented Features

1. **Authentication & Authorization**
   - Session-based login check
   - Role-based access control (seller only)
   - Ownership verification for products

2. **Dashboard Overview**
   - Key metrics cards (revenue, products, sales, carbon)
   - Monthly revenue & carbon charts
   - Recent orders display
   - Quick action buttons

3. **Product Management**
   - Add products with validation
   - Edit product details
   - Delete products with confirmation
   - Product inventory view
   - Carbon footprint tracking

4. **Order Management**
   - View all seller's orders
   - Customer information display
   - Payment status tracking
   - Detailed order breakdown
   - Order statistics

5. **Analytics & Reports**
   - Monthly revenue trends
   - Carbon emission tracking
   - Combined revenue vs carbon chart
   - Product analysis with carbon-to-price ratio
   - Eco-performance tips

6. **UI/UX**
   - Responsive Bootstrap-based design
   - Sidebar navigation
   - Cards and metrics display
   - Interactive charts (Chart.js)
   - Form validation
   - Flash messages

## Testing Checklist

- [ ] Login with seller account works
- [ ] Seller Dashboard accessible from navbar
- [ ] Dashboard overview loads with metrics
- [ ] Charts render properly
- [ ] Can add new product
- [ ] Can view product list
- [ ] Can edit product
- [ ] Can delete product
- [ ] Can view orders
- [ ] Order details page loads
- [ ] Reports page with charts displays
- [ ] Responsive design on mobile
- [ ] All links navigate correctly
- [ ] Forms validate properly
- [ ] Flash messages display

## Troubleshooting

### Problem: "You do not have permission to access this page"
**Solution**: 
- Verify user role is 'seller' in database
- `SELECT * FROM users WHERE user_id = YOUR_ID;`
- Update role if needed: `UPDATE users SET role = 'seller' WHERE user_id = YOUR_ID;`

### Problem: Dashboard shows empty metrics
**Solution**:
- Add some products first
- Create orders for those products
- Wait for page to load completely
- Check browser console for JavaScript errors

### Problem: Charts not displaying
**Solution**:
- Verify Chart.js CDN is accessible
- Check browser console for errors
- Ensure data is properly formatted
- Try refreshing the page

### Problem: "Form validation errors"
**Solution**:
- Check all required fields are filled
- Verify number fields contain valid numbers
- For carbon footprint: must be >= 0
- For price: must be > 0

### Problem: Product/Order not found
**Solution**:
- Verify the ID exists in database
- Check that product belongs to current seller
- Verify order contains seller's products

## Performance Tips

1. **Database**
   - Add indexes to `seller_id` columns
   - Archive old orders periodically
   - Optimize queries with EXPLAIN

2. **Frontend**
   - Enable gzip compression
   - Minify CSS and JavaScript
   - Lazy load charts
   - Cache static assets

3. **Caching**
   - Cache dashboard metrics
   - Cache product lists
   - Cache monthly sales data
   - Set expiry times (5-15 minutes)

## Database Optimization

Add these indexes for better performance:

```sql
CREATE INDEX idx_products_seller_id ON products(seller_id);
CREATE INDEX idx_orders_user_id ON orders(user_id);
CREATE INDEX idx_order_items_order_id ON order_items(order_id);
CREATE INDEX idx_order_items_product_id ON order_items(product_id);
```

## Sample Data for Testing

Create test data:

```sql
-- Add seller user
INSERT INTO users (first_name, last_name, email, phone_number, password, role)
VALUES ('Test', 'Seller', 'seller@test.com', '9876543210',
        '$2y$10$bUVnB3hj8JnF8dqKvR9FPOWvs6QzO5vBLQeWtBqKV7JWmNu9m6KMK', 'seller');

-- Add products for seller (seller_id = 1)
INSERT INTO products (seller_id, category_id, product_name, description, price, carbon_footprint)
VALUES (1, 1, 'Eco Backpack', 'Sustainable backpack made from recycled materials', 49.99, 2.5),
       (1, 2, 'Organic T-Shirt', 'Made from 100% organic cotton', 29.99, 1.2),
       (1, 3, 'Coffee Beans', 'Ethically sourced coffee beans', 12.99, 0.8);
```

## Next Steps

1. Test all features thoroughly
2. Create sample products and orders
3. Monitor performance
4. Customize styling as needed
5. Set up backups for database

## Support

For issues or questions:
1. Check SELLER_DASHBOARD.md for detailed documentation
2. Review controller code for logic
3. Check browser console for errors
4. Review database queries in Model classes

## License

Part of CarbonSphere E-commerce Platform