# 🎯 Seller Dashboard - Features Guide

A complete walkthrough of all features available in the CarbonSphere Seller Dashboard.

---

## 📍 Navigation

### Main Sidebar Menu
Located on the left side of your dashboard, providing quick access to all features:

```
🏠 Dashboard          → See overview and recent activity
🛍️  My Products       → Manage your product listings
➕ Add Product        → Create new product listing
🛒 My Orders          → View customer orders
📊 Reports            → Analyze sales and carbon impact
🚪 Logout             → Sign out of dashboard
```

### Top Navigation Bar
Shows your store branding and quick links:
- **Carbon Sphere Logo** - Click to go to main store
- **Browse Products** - Shop on the main store
- **User Menu** - Dropdown with profile and logout

---

## 🎯 Dashboard Overview

### What You See
When you first login, you'll see key performance metrics:

#### 📈 Performance Cards (4 Cards at Top)

1. **Total Revenue**
   - Shows: `$X,XXX.XX`
   - Meaning: Total money earned from all orders
   - Includes: Orders from current and past months
   - Update: Real-time

2. **Products Listed**
   - Shows: `X`
   - Meaning: Number of products you've added
   - Includes: Active products only
   - Quick Link: Click to go to My Products

3. **Items Sold**
   - Shows: `X` units
   - Meaning: Total quantity of products sold
   - Calculation: Sum of quantities across all orders
   - Help: Identifies your bestselling quantity

4. **Carbon Emitted**
   - Shows: `X.XX` kg CO₂
   - Meaning: Total carbon footprint of sold products
   - Calculation: Sum of carbon_footprint × quantity
   - Goal: Monitor your environmental impact

#### 📊 Charts & Visualizations

**Monthly Sales & Carbon Trend Chart**
- **Type**: Line chart with two axes
- **Left Axis**: Revenue in dollars (green line)
- **Right Axis**: Carbon in kg (red line)
- **Time Period**: Last 12 months
- **Use**: Identify sales patterns and carbon correlation
- **Interaction**: Hover over points to see values

**Product Eco Performance Chart**
- **Type**: Doughnut/Pie chart
- **Shows**: Sold vs Unsold inventory carbon
- **Sold Products**: Carbon from sold items (red)
- **Unsold Inventory**: Estimated carbon in stock (gray)
- **Use**: Understand inventory carbon impact

**Recent Orders Table**
- **Shows**: Last 5 orders
- **Columns**: Order ID, Customer, Amount, Carbon, Status, Date
- **Status Colors**:
  - 🟢 Green (Paid): Payment received
  - 🟡 Yellow (Pending): Awaiting payment
- **Action**: Click "View" to see full order details

#### ⚡ Quick Actions
Buttons to quickly navigate to common tasks:
- **Add New Product** - Create a new listing
- **Manage Products** - View and edit products
- **View Reports** - See detailed analytics

---

## 📦 Product Management

### Add Product (`/seller/addProduct`)

**Purpose**: Create and list a new product for sale

**Form Fields**:

1. **Product Name** ⭐ Required
   - Example: "Eco-Friendly Water Bottle"
   - Limit: Up to 100 characters
   - Tip: Be descriptive and clear

2. **Category** ⭐ Required
   - Options:
     - Electronics (15-50 kg CO₂)
     - Clothing (2-10 kg CO₂)
     - Food & Beverage (0.5-5 kg CO₂)
     - Home & Garden (varies)
     - Sports & Outdoors (varies)
     - Books & Media (0.5-2 kg CO₂)
   - Tip: Choose matching category for better visibility

3. **Description** ⭐ Required
   - Details about your product
   - Include: Materials, quality, care instructions
   - Length: At least 20 characters
   - Tip: More details attract more buyers

4. **Price ($)** ⭐ Required
   - Example: `29.99`
   - Format: Decimal with 2 places
   - Minimum: $0.01
   - Validation: Must be a number

5. **Carbon Footprint (kg CO₂)** ⭐ Required
   - Example: `2.5`
   - Meaning: CO₂ emitted per unit of product
   - Includes: Manufacturing, shipping, packaging
   - Format: Decimal with 2 places
   - Minimum: 0 (only for intangible products)
   - **Carbon Footprint Guide Provided**:
     - Electronics: 15-50 kg
     - Clothing: 2-10 kg
     - Food: 0.5-5 kg
     - Books: 0.5-2 kg

**Tips Section**:
- Get visibility tips
- Learn about carbon footprint
- Understand eco-conscious buyer preferences

**Validation**:
- All fields required
- Numbers must be valid
- Price must be positive
- Carbon can't be negative

**After Submission**:
- Success: Redirected to My Products
- Error: Form redisplayed with error messages
- Flash Message: "Product added successfully!"

---

### My Products (`/seller/myProducts`)

**Purpose**: View, edit, and delete all your products

**Product Table Shows**:
- **Product Name**: With truncated description
- **Category**: Badge showing category
- **Price**: In green color (`$X.XX`)
- **Carbon Footprint**: Red badge with kg value
- **Created Date**: When product was added
- **Actions**: Edit and Delete buttons

**Summary Statistics** (Below Table):
- **Average Carbon Footprint**: Per product average
- **Total Carbon Footprint**: Sum of all products

**Actions**:
- **Edit Button**: Modify product details
- **Delete Button**: Permanently remove product
  - Confirmation: "Are you sure?"
  - Warning: Cannot be undone

**If No Products**:
- Shows empty state message
- Provides "Add Your First Product" button

---

### Edit Product (`/seller/editProduct/:id`)

**Purpose**: Update product information

**Changes You Can Make**:
- ✏️ Product name
- ✏️ Category
- ✏️ Description
- ✏️ Price
- ✏️ Carbon footprint

**Safety Features**:
- Ownership check: Only your products
- Loads current values in form
- Validates all changes
- Confirmation on submit

**Danger Zone** (Red Section):
- **Delete Product Button**:
  - Confirmation dialog: "Are you sure?"
  - Action: Immediately deletes product
  - Impact: Cannot be undone
  - Note: Orders using product remain

**After Save**:
- Success: Redirected to My Products
- Flash: "Product updated successfully!"
- Error: Form redisplayed with errors

---

## 🛒 Order Management

### My Orders (`/seller/myOrders`)

**Purpose**: Track all sales of your products

**Order Summary Cards** (Top):
- **Total Orders**: Count of all orders
- **Pending Payment**: Orders awaiting payment

**Orders Table Shows**:
- **Order ID**: Click to view details (#XXXX)
- **Customer**: Full name and email
- **Amount**: Order total in dollars
- **Items**: Indicates multiple items
- **Carbon Impact**: Red badge with kg CO₂
- **Payment Status**: 
  - 🟢 Paid (green)
  - 🟡 Pending (yellow)
- **Date**: When order was placed
- **Action**: View Details button

**Order Summary** (Bottom):
- **Average Order Value**: Mean order amount
- **Total Revenue**: Sum of all orders
- **Total Carbon Impact**: Sum of all carbon

**If No Orders**:
- Empty state message
- Button to add products
- Help text explaining next steps

---

### Order Details (`/seller/orderDetails/:id`)

**Purpose**: See complete breakdown of an order

**Order Information Section**:
- **Order Date & Time**: When order was placed
- **Payment Status**: Paid or Pending (with badge)
- **Order Total**: In green (`$X.XX`)
- **Carbon Footprint**: In red (X.XX kg CO₂)

**Items Table**:
- **Product Name**: What was ordered
- **Unit Price**: Per-unit cost
- **Quantity**: How many units
- **Total**: Line total (`$X.XX`)
- **Carbon**: Carbon per unit (kg badge)

**Subtotal**: Displayed at bottom of items

**Customer Information** (Right Sidebar):
- **Name**: Customer's full name
- **Email**: Clickable mailto link
- **Order Summary**:
  - Item count
  - Total amount
  - Carbon impact
- **Information**: Explains your role as seller

**Carbon Impact Info** (Card):
- **Total Carbon**: In large red text
- **Equivalents**: Helps understand impact
  - Miles of car driving
  - Tree seedlings grown for 10 years
  - Number of plastic bags
- **Purpose**: Educational for eco-impact awareness

---

## 📊 Reports (`/seller/report`)

**Purpose**: Analyze sales performance and carbon impact

### Performance Metrics (Top Cards)

1. **Total Revenue**: All-time earnings
2. **Total Orders**: Complete order count
3. **Items Sold**: Total units sold
4. **Carbon Emitted**: Total kg CO₂

### Charts

#### 1. Monthly Revenue Trend
- **Type**: Bar chart
- **Shows**: Revenue per month
- **Color**: Green bars
- **Time**: Last 12 months
- **Use**: See sales growth pattern
- **Insight**: Identify best-selling months

#### 2. Monthly Carbon Impact
- **Type**: Bar chart
- **Shows**: CO₂ emissions per month
- **Color**: Red bars
- **Time**: Last 12 months
- **Use**: Track environmental impact
- **Insight**: Correlate with sales volume

#### 3. Revenue vs Carbon Trend
- **Type**: Combined line chart
- **Shows**: Both metrics together
- **Green Line**: Revenue ($)
- **Red Line**: Carbon (kg)
- **Use**: Understand relationship
- **Insight**: High-revenue ≠ high-carbon possible

### Product Analysis Table

**Shows Each Product**:
- **Product Name**: Item name
- **Price**: In green
- **Carbon Footprint**: In red badge
- **Carbon-to-Price Ratio**: Calculated metric
  - **Low** (< 0.1): Good eco-score
  - **Medium** (0.1-0.5): Moderate
  - **High** (> 0.5): High carbon per dollar
- **Color**: Green (low), Yellow (medium), Red (high)
- **Use**: Identify most eco-friendly products

### Eco-Performance Tips Section

**4 Key Recommendations**:
1. **Focus on Low-Carbon Products**
   - Prioritize eco-friendly items
   - Eco-conscious buyers prefer these

2. **Optimize Packaging**
   - Use sustainable materials
   - Reduce packaging size
   - Lower shipping emissions

3. **Local Sourcing**
   - Partner with local suppliers
   - Reduce transportation emissions
   - Support community

4. **Track & Report**
   - Monitor your carbon impact
   - Share eco-performance
   - Build customer trust

---

## 💡 Understanding Carbon Footprint

### What is Carbon Footprint?
The total CO₂ emissions produced during a product's lifecycle:
- Manufacturing
- Transportation
- Packaging
- Distribution

### Why It Matters
- Shows environmental impact
- Helps identify eco-friendly options
- Important for eco-conscious buyers
- Supports sustainability goals

### How to Estimate

**Use These Ranges**:
- **Electronics**: 15-50 kg CO₂ per unit
- **Clothing**: 2-10 kg CO₂ per unit
- **Food & Beverage**: 0.5-5 kg CO₂ per unit
- **Home & Garden**: Varies widely
- **Books & Media**: 0.5-2 kg CO₂ per unit

**Factors to Consider**:
- Material sourcing
- Manufacturing location
- Packaging material
- Shipping distance
- Product lifespan

---

## 🎯 Best Practices

### For Maximum Sales
✅ Clear, descriptive product names
✅ Detailed product descriptions
✅ Accurate pricing
✅ Realistic carbon footprints
✅ Relevant category selection

### For Eco-Impact
✅ Prioritize low-carbon products
✅ Optimize packaging
✅ Source locally when possible
✅ Share carbon data with customers
✅ Monitor your environmental impact

### For Business Growth
✅ Regular product updates
✅ Monitor sales trends
✅ Analyze customer orders
✅ Optimize based on reports
✅ Maintain customer satisfaction

---

## 📱 Using on Mobile

### Responsive Design
- ✅ All features work on mobile
- ✅ Touch-friendly buttons
- ✅ Readable text sizes
- ✅ Tables scroll horizontally

### Mobile Tips
- Portrait orientation for navigation
- Landscape for tables
- Tables scrollable on mobile
- Use browser zoom if needed

---

## ⚙️ Settings & Preferences

### Coming Soon Features
- Profile editing
- Store customization
- Notification preferences
- Email settings
- Payment method management

---

## 🔐 Security & Privacy

### Your Data is Protected
- Secure login required
- Only you can see your data
- Products and orders are private
- Customer information protected

### Good Practices
- Keep password secure
- Don't share login
- Logout when done
- Clear browser cache

---

## ❓ Common Questions

### Q: How do I estimate carbon footprint?
A: Use the guide provided in Add Product form. Consider manufacturing, shipping, and packaging.

### Q: Can I change product price?
A: Yes, edit the product anytime. Changes apply to new orders only.

### Q: What if I need to delete a product?
A: Go to My Products, click Delete. Note: Past orders are unaffected.

### Q: How are revenues calculated?
A: Revenue = Product Price × Quantity for each sold item. Sum of all orders.

### Q: Why does carbon vary by month?
A: Carbon depends on quantity sold and products ordered. More sales = more carbon impact.

### Q: Can customers see my reports?
A: No, reports are private to you. Only carbon info on product listings is public.

### Q: What payment statuses mean?
A: Pending = awaiting payment. Paid = payment received and confirmed.

### Q: How often do charts update?
A: Automatically when you refresh the page or navigate.

---

## 🚀 Getting Started Steps

1. **Login**: Enter your seller credentials
2. **Explore Dashboard**: See your overview
3. **Add Products**: Start listing items
4. **Monitor Sales**: Check My Orders
5. **Analyze**: View Reports regularly
6. **Optimize**: Make improvements based on data

---

## 📞 Support & Help

### Documentation
- See SELLER_DASHBOARD.md for technical details
- See SELLER_DASHBOARD_SETUP.md for installation

### Troubleshooting
- Check browser console for errors
- Verify database has your data
- Try refreshing the page
- Clear browser cache if issues persist

### Reporting Issues
- Note the error message
- Screenshot the problem
- Describe what you were doing
- Contact support with details

---

## 🌟 Features Summary

| Feature | Availability | Status |
|---------|--------------|--------|
| Dashboard | ✅ Yes | Real-time metrics & charts |
| Add Products | ✅ Yes | Full form with validation |
| Manage Products | ✅ Yes | Edit, delete, view list |
| Order Tracking | ✅ Yes | See all customer orders |
| Order Details | ✅ Yes | Detailed breakdowns |
| Analytics | ✅ Yes | Revenue & carbon reports |
| Charts | ✅ Yes | Interactive visualizations |
| Mobile Support | ✅ Yes | Fully responsive |
| Security | ✅ Yes | Password & role-based |

---

## 🎓 Learning Path

1. **Beginner**: Learn dashboard basics
2. **Intermediate**: Add and manage products
3. **Advanced**: Analyze reports and optimize
4. **Expert**: Monitor carbon impact and sustainability

---

## 🌱 Contributing to Sustainability

Every product you sell with accurate carbon footprints helps:
- Customers make eco-friendly choices
- Reduce overall environmental impact
- Promote sustainable commerce
- Build a better future 🌍

---

**Happy Selling! Remember: Sustainability Matters! 🌿**

Last Updated: 2024