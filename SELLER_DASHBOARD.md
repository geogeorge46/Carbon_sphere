# Seller Dashboard - CarbonSphere

## Overview

The Seller Dashboard is a comprehensive platform for sellers to manage their products, track sales, monitor carbon footprint impact, and optimize their eco-performance. Built with PHP OOP (MVC) and MySQL, it provides sellers with powerful tools to grow their business while contributing to environmental sustainability.

## Features

### 1. **Dashboard Overview**
- **Key Metrics Display**
  - Total Revenue: Aggregated earnings from all orders
  - Products Listed: Number of products in catalog
  - Items Sold: Total units sold
  - Carbon Emitted: Total CO₂ footprint of sold products

- **Charts & Analytics**
  - Monthly Revenue Trend: Visualize revenue growth over time
  - Carbon Impact Chart: Track environmental impact monthly
  - Recent Orders: Quick view of latest 5 orders

### 2. **Product Management**
- **Add Product**
  - Product name, description, category
  - Price and carbon footprint per unit
  - Form validation with helpful error messages
  - Carbon footprint guide to help sellers estimate values

- **My Products**
  - View all listed products
  - Edit product details (name, description, price, carbon footprint)
  - Delete products with confirmation
  - See average and total carbon footprint of inventory
  - Product table with sorting and filtering

- **Edit Product**
  - Update all product information
  - Secure ownership verification (only seller can edit their products)
  - Immediate reflection of changes

### 3. **Order Management**
- **My Orders**
  - View all orders containing seller's products
  - Order status tracking (Pending/Paid)
  - Customer information display
  - Order totals and carbon impact
  - Quick stats: Total orders, pending payments, average order value

- **Order Details**
  - Detailed order breakdown
  - Customer information with email
  - Itemized product list with quantities and prices
  - Carbon impact per item
  - Carbon equivalents explanation (miles driven, trees, plastic bags)

### 4. **Earnings & Carbon Report**
- **Revenue Analytics**
  - Total revenue, total orders, items sold
  - Monthly breakdown with charts
  - Average order value calculation

- **Carbon Analytics**
  - Monthly carbon emission tracking
  - Combined Revenue vs Carbon trend chart
  - Product-level carbon analysis
  - Carbon-to-price ratio (helps identify eco-friendly products)

- **Eco Performance Tips**
  - Guidance on reducing carbon footprint
  - Best practices for sustainable selling
  - Tips for local sourcing and packaging optimization

## Architecture

### Controllers

**SellerController.php** - Main controller for all seller dashboard operations

```php
// Methods:
- index()           // Dashboard overview
- addProduct()      // Add new product
- myProducts()      // List seller's products
- editProduct()     // Edit product
- deleteProduct()   // Delete product
- myOrders()        // List seller's orders
- orderDetails()    // View order details
- report()          // Earnings & carbon report
```

### Models

**Product.php** - Enhanced with seller-specific methods
```php
// New methods:
- getSellerProducts($sellerId)
- getSellerProductCount($sellerId)
- getSellerAvgCarbonFootprint($sellerId)
- getSellerTotalCarbonSold($sellerId)
- isProductBySeller($productId, $sellerId)
```

**Order.php** - Enhanced with seller-specific methods
```php
// New methods:
- getSellerOrders($sellerId)
- getSellerOrderItems($orderId, $sellerId)
- getSellerTotalSales($sellerId)
- getSellerMonthlySales($sellerId)
```

### Views

Located in `app/Views/seller/`:

- **layout.php** - Main layout with sidebar navigation
- **layout_end.php** - Footer and closing tags
- **dashboard.php** - Dashboard overview with metrics and charts
- **add_product.php** - Add product form
- **edit_product.php** - Edit product form with delete option
- **my_products.php** - Products table with edit/delete actions
- **my_orders.php** - Orders list with summary statistics
- **order_details.php** - Detailed order view
- **report.php** - Comprehensive earnings and carbon reports

## Database Queries

### Key SQL Patterns

**Get seller's orders:**
```sql
SELECT DISTINCT o.order_id, o.user_id, o.total_amount, o.total_carbon, 
       o.created_at, o.payment_status, u.first_name, u.last_name, u.email
FROM orders o
INNER JOIN order_items oi ON o.order_id = oi.order_id
INNER JOIN products p ON oi.product_id = p.product_id
INNER JOIN users u ON o.user_id = u.user_id
WHERE p.seller_id = :seller_id
ORDER BY o.created_at DESC
```

**Get seller's total sales:**
```sql
SELECT SUM(p.price * oi.quantity) as total_revenue,
       COUNT(DISTINCT o.order_id) as total_orders,
       SUM(oi.carbon_footprint) as total_carbon_emitted,
       SUM(oi.quantity) as total_items_sold
FROM orders o
INNER JOIN order_items oi ON o.order_id = oi.order_id
INNER JOIN products p ON oi.product_id = p.product_id
WHERE p.seller_id = :seller_id
```

**Get monthly sales data:**
```sql
SELECT DATE_FORMAT(o.created_at, '%Y-%m') as month,
       SUM(p.price * oi.quantity) as revenue,
       SUM(oi.carbon_footprint) as carbon
FROM orders o
INNER JOIN order_items oi ON o.order_id = oi.order_id
INNER JOIN products p ON oi.product_id = p.product_id
WHERE p.seller_id = :seller_id
GROUP BY DATE_FORMAT(o.created_at, '%Y-%m')
ORDER BY month DESC
LIMIT 12
```

## Security Features

1. **Session-based Authentication**
   - Check if user is logged in
   - Verify user role is 'seller'
   - Redirect unauthorized users to login

2. **Ownership Verification**
   - Verify seller owns the product before editing/deleting
   - Verify seller has access to order items

3. **Input Validation**
   - Server-side validation on all forms
   - Sanitized POST data
   - Type checking for numeric values

4. **SQL Injection Prevention**
   - Prepared statements with bound parameters
   - All user inputs properly escaped

## Styling & UI

### CSS Framework
- Bootstrap 4.5.2 for responsive design
- Custom CSS in `public/css/seller-dashboard.css`
- Modern gradient effects and transitions

### Design Features
- **Sidebar Navigation**: Fixed sidebar with active state indicators
- **Responsive Layout**: Mobile-friendly responsive design
- **Card-based UI**: Consistent card design for all sections
- **Color Coding**: Green for success/revenue, Red for carbon/danger
- **Icons**: Font Awesome icons for visual clarity
- **Animations**: Smooth transitions and hover effects

### Responsive Breakpoints
- Desktop (768px+): Full sidebar visible
- Mobile (<768px): Sidebar hidden, full-width main content

## JavaScript Functionality

### Chart Library
- Chart.js v3.9.1 for data visualization
- Line charts for trend analysis
- Bar charts for monthly breakdown
- Doughnut charts for distribution

### Interactive Features
- Form validation
- Auto-dismissing alerts (5 seconds)
- Tooltip initialization
- Active link highlighting
- Carbon equivalents calculation
- Data export functionality

## Installation & Setup

### 1. Database Setup
Ensure all migrations (0001-0007) are applied:
```bash
mysql -u root -p carbon_sphere < database/migrations/0001_create_users.sql
mysql -u root -p carbon_sphere < database/migrations/0002_create_categories.sql
mysql -u root -p carbon_sphere < database/migrations/0003_create_products.sql
mysql -u root -p carbon_sphere < database/migrations/0004_create_carts.sql
mysql -u root -p carbon_sphere < database/migrations/0005_create_orders.sql
mysql -u root -p carbon_sphere < database/migrations/0006_create_suggestions.sql
mysql -u root -p carbon_sphere < database/migrations/0007_alter_users_table.sql
```

### 2. User Role Setup
Ensure users have `role` column set to 'seller':
```sql
UPDATE users SET role = 'seller' WHERE user_id = 1;
```

### 3. Access Dashboard
- URL: `http://localhost/carbon-sphere/public/seller`
- Must be logged in with `role = 'seller'`

## Usage Guide

### For Sellers

1. **Getting Started**
   - Login with seller account
   - Click "Seller Dashboard" in navigation bar
   - Complete profile information

2. **Adding Products**
   - Go to "Add Product" or click the add button on dashboard
   - Fill in product details
   - Set accurate carbon footprint value
   - Submit form

3. **Managing Products**
   - View all products in "My Products"
   - Edit or delete as needed
   - Monitor product inventory

4. **Tracking Sales**
   - Check "My Orders" for incoming orders
   - View order details and customer information
   - Monitor payment status

5. **Analyzing Performance**
   - Visit "Reports" for analytics
   - Track revenue trends
   - Monitor carbon footprint impact
   - Identify best-performing products

## API Endpoints

### Seller Routes

```
GET  /seller                    - Dashboard overview
GET  /seller/addProduct         - Add product form
POST /seller/addProduct         - Submit new product
GET  /seller/myProducts         - List all seller's products
GET  /seller/editProduct/:id    - Edit product form
POST /seller/editProduct/:id    - Update product
POST /seller/deleteProduct/:id  - Delete product
GET  /seller/myOrders           - List seller's orders
GET  /seller/orderDetails/:id   - View order details
GET  /seller/report             - Earnings & carbon report
```

## Performance Optimization

### Database Optimization
- Indexes on `seller_id` in products table
- Indexes on foreign keys
- Efficient JOIN queries with aggregations

### Caching Opportunities
- Cache dashboard metrics (refresh every 5 minutes)
- Cache monthly sales data
- Cache product lists for sellers

### Frontend Optimization
- Lazy loading for charts
- Debounced search functions
- Minified CSS and JavaScript

## Future Enhancements

1. **Advanced Analytics**
   - Predictive analytics for sales forecasting
   - Customer segmentation
   - Product recommendations

2. **Integrations**
   - Payment gateway integration
   - Email notifications
   - SMS alerts for orders

3. **Automation**
   - Automatic carbon offset recommendations
   - Bulk product operations
   - Scheduled reports

4. **AI & ML**
   - Carbon footprint estimation AI
   - Price optimization recommendations
   - Demand forecasting

## Troubleshooting

### Issue: Dashboard not accessible
- **Solution**: Verify user role is 'seller' in database
- Check session variables: `$_SESSION['user_role']`

### Issue: Charts not displaying
- **Solution**: Check Chart.js CDN is loaded
- Verify data is properly formatted as JSON
- Check browser console for errors

### Issue: Products not showing
- **Solution**: Verify products have correct `seller_id`
- Check database connection
- Verify SQL queries in Model

### Issue: Orders not appearing
- **Solution**: Ensure order_items are created when orders are placed
- Verify seller_id matches in products table
- Check order dates are recent

## Support & Documentation

For additional help:
- Check inline code comments
- Review Model methods for database operations
- Test with sample data
- Review error logs in browser console

## License

This project is part of CarbonSphere E-commerce Platform.

## Contributors

Developed as part of CarbonSphere initiative to promote sustainable commerce.