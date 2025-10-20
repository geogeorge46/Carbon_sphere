# Carbon Sphere Database Setup - Quick Start

## 🚀 Fast Setup (3 Steps)

### Step 1: Create Database
```sql
CREATE DATABASE IF NOT EXISTS carbon_sphere;
USE carbon_sphere;
```

### Step 2: Run All Migrations in Order

Copy and paste this into phpMyAdmin SQL tab:

```sql
-- Create base tables
source migrations/0001_create_users.sql;
source migrations/0002_create_categories.sql;
source migrations/0003_create_products.sql;
source migrations/0004_create_carts.sql;
source migrations/0005_create_orders.sql;
source migrations/0006_create_suggestions.sql;

-- Modify tables and add features
source migrations/0007_alter_users_table.sql;
source migrations/0010_alter_products_table.sql;

-- Seed initial data
source migrations/0009_seed_categories.sql;
```

**OR manually copy-paste each file in phpMyAdmin SQL tab in this order:**

1. 0001_create_users.sql
2. 0002_create_categories.sql
3. 0003_create_products.sql
4. 0004_create_carts.sql
5. 0005_create_orders.sql
6. 0006_create_suggestions.sql
7. 0007_alter_users_table.sql
8. **0009_seed_categories.sql** ⭐ (NEW)
9. **0010_alter_products_table.sql** ⭐ (NEW)

### Step 3: Verify Setup

```sql
-- Should show 6 categories
SELECT * FROM categories;

-- Should show image_url column
DESCRIBE products;

-- Should show seller_id and category_id indexes
SHOW INDEXES FROM products;
```

---

## 📋 What's New (Product Images Feature)

### New Migrations

| Migration | Purpose |
|-----------|---------|
| **0009_seed_categories.sql** | Adds 6 default product categories (Electronics, Clothing, Food & Beverage, Home & Garden, Sports & Outdoors, Books & Media) |
| **0010_alter_products_table.sql** | Adds `image_url` column to products table + performance indexes |

### Why These Files?

- **0009_seed_categories.sql**: Populates categories so sellers can add products
- **0010_alter_products_table.sql**: Enables product image URLs from external sources (no file uploads)

### Features Enabled

✅ Sellers can add product images via URL  
✅ Real-time image preview while editing  
✅ Thumbnail display in products list  
✅ Performance optimizations with indexes  

---

## 🔍 Migration Files Location

```
database/
├── migrations/
│   ├── 0001_create_users.sql
│   ├── 0002_create_categories.sql
│   ├── 0003_create_products.sql
│   ├── 0004_create_carts.sql
│   ├── 0005_create_orders.sql
│   ├── 0006_create_suggestions.sql
│   ├── 0007_alter_users_table.sql
│   ├── 0008_add_image_url_to_products.sql (deprecated - use 0010)
│   ├── 0009_seed_categories.sql ⭐ (NEW)
│   └── 0010_alter_products_table.sql ⭐ (NEW)
├── seeds/
├── MIGRATION_GUIDE.md (detailed guide)
└── SETUP_INSTRUCTIONS.md (this file)
```

---

## ⚠️ Important Notes

### Do NOT use 0008_add_image_url_to_products.sql
- Use **0010_alter_products_table.sql** instead
- 0010 includes image_url + performance indexes
- 0008 is kept for reference only

### Migration Order is Critical
- Always run CREATE (0001-0006) before ALTER (0007, 0010)
- Always run ALTER before SEED (0009)
- Otherwise: foreign key constraints will fail

### If Categories Are Missing
Error: `Cannot add or update a child row: a foreign key constraint fails`

**Solution**: Run 0009_seed_categories.sql

### If image_url Column Missing
Error: `Unknown column 'image_url' in 'field list'`

**Solution**: Run 0010_alter_products_table.sql

---

## 📊 Database Schema Summary

### Table: categories
```
- category_id (INT, PK)
- category_name (VARCHAR)
```

### Table: products
```
- product_id (INT, PK)
- seller_id (INT, FK)
- category_id (INT, FK)
- product_name (VARCHAR)
- image_url (VARCHAR) ⭐ NEW
- description (TEXT)
- price (DECIMAL)
- carbon_footprint (FLOAT)
- created_at (DATETIME)

Indexes:
- idx_seller_id ⭐ NEW
- idx_category_id ⭐ NEW
```

---

## 🧪 Test After Setup

1. **Add a seller** (register user as seller)
2. **Go to Seller Dashboard** → Add Product
3. **Fill in product details**:
   - Name: "Test Product"
   - Category: "Electronics"
   - Image URL: `https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=200`
   - Description: "Test product"
   - Price: 99.99
   - Carbon Footprint: 20

4. **Submit** → Should work without errors ✅

---

## 📞 Quick Reference

**Run these migrations:**
1. Create database
2. Run 0001-0006 (create tables)
3. Run 0007 (alter users)
4. Run 0009 (seed categories) ⭐
5. Run 0010 (alter products) ⭐
6. Done! ✅

**Key files:**
- Migration guide: `database/MIGRATION_GUIDE.md`
- Product images docs: `PRODUCT_IMAGES_SETUP.md`
- Feature details: `PRODUCT_IMAGES_FEATURE.md`

---

## 🚨 Emergency Revert

If something goes wrong, reset everything:

```sql
DROP DATABASE carbon_sphere;
CREATE DATABASE carbon_sphere;
USE carbon_sphere;

-- Then run all migrations again from step 2 above
```

---

**Status**: Ready to Setup ✅  
**Estimated Time**: 5 minutes  
**Difficulty**: Easy  

Need help? Check `MIGRATION_GUIDE.md` for detailed troubleshooting.