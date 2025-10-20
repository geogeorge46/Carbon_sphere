# Database Migration Guide

This guide explains how to run database migrations for the Carbon Sphere application.

## Migration Files Overview

| File | Description | Type |
|------|-------------|------|
| 0001_create_users.sql | Create users table | CREATE |
| 0002_create_categories.sql | Create categories table | CREATE |
| 0003_create_products.sql | Create products table | CREATE |
| 0004_create_carts.sql | Create shopping cart table | CREATE |
| 0005_create_orders.sql | Create orders table | CREATE |
| 0006_create_suggestions.sql | Create product suggestions table | CREATE |
| 0007_alter_users_table.sql | Add seller profile fields | ALTER |
| 0008_add_image_url_to_products.sql | ~~Add product image URLs~~ | ~~ALTER~~ |
| **0009_seed_categories.sql** | **Insert initial categories** | **SEED** |
| **0010_alter_products_table.sql** | **Add image_url + performance indexes** | **ALTER** |

## How to Run Migrations

### Option 1: phpMyAdmin (Recommended)

1. Open phpMyAdmin: `http://localhost/phpmyadmin`
2. Select your database (`carbon_sphere`)
3. Click the **SQL** tab
4. Copy and paste migration SQL content
5. Click **Go**
6. Repeat for each migration file

### Option 2: MySQL Command Line

```bash
mysql -u root -p carbon_sphere < database/migrations/0009_seed_categories.sql
mysql -u root -p carbon_sphere < database/migrations/0010_alter_products_table.sql
```

### Option 3: Single SQL File (Recommended)

Create `database/RUN_ALL_MIGRATIONS.sql` and run all at once:

```sql
-- Run all migrations in sequence
source database/migrations/0001_create_users.sql;
source database/migrations/0002_create_categories.sql;
source database/migrations/0003_create_products.sql;
source database/migrations/0004_create_carts.sql;
source database/migrations/0005_create_orders.sql;
source database/migrations/0006_create_suggestions.sql;
source database/migrations/0007_alter_users_table.sql;
source database/migrations/0009_seed_categories.sql;
source database/migrations/0010_alter_products_table.sql;
```

## Fresh Database Setup

Run migrations in this exact order:

```sql
-- 1. Create base tables
source database/migrations/0001_create_users.sql;
source database/migrations/0002_create_categories.sql;
source database/migrations/0003_create_products.sql;
source database/migrations/0004_create_carts.sql;
source database/migrations/0005_create_orders.sql;
source database/migrations/0006_create_suggestions.sql;

-- 2. Alter/Modify tables
source database/migrations/0007_alter_users_table.sql;
source database/migrations/0010_alter_products_table.sql;

-- 3. Seed initial data
source database/migrations/0009_seed_categories.sql;
```

## What Each Migration Does

### 0009_seed_categories.sql
- **Purpose**: Populate the categories table with 6 default product categories
- **Categories Added**:
  - 1: Electronics
  - 2: Clothing
  - 3: Food & Beverage
  - 4: Home & Garden
  - 5: Sports & Outdoors
  - 6: Books & Media
- **Why**: Required for product creation - foreign key constraint depends on these categories existing
- **Safe**: Uses `ON DUPLICATE KEY UPDATE` to prevent errors if run multiple times

### 0010_alter_products_table.sql
- **Purpose**: Add product image URL support with performance improvements
- **Changes**:
  - Adds `image_url VARCHAR(500)` column (nullable)
  - Creates index on `seller_id` for faster seller product lookups
  - Creates index on `category_id` for faster category filtering
- **Why Indexes**: Improves query performance for common operations like "Get all products by seller"
- **Safe**: Column is nullable - existing products without images continue to work

## Verification Queries

### Check if migrations applied successfully

```sql
-- Verify categories were seeded
SELECT COUNT(*) as category_count FROM categories;
-- Should return: 6

-- Verify image_url column exists
DESCRIBE products;
-- Should show image_url column

-- Check table indexes
SHOW INDEXES FROM products;
-- Should show indexes on seller_id and category_id
```

## Troubleshooting

### Error: "Unknown column 'image_url' in 'field list'"
**Solution**: Run migration 0010_alter_products_table.sql to add the image_url column

### Error: "Cannot add or update a child row: a foreign key constraint fails"
**Solution**: Run migration 0009_seed_categories.sql to populate categories first

### Error: "Duplicate entry"
**Solution**: This is safe - migrations use `ON DUPLICATE KEY UPDATE` to handle duplicates

### Error: "Table doesn't exist"
**Solution**: Run all CREATE migrations (0001-0006) first, before ALTER and SEED migrations

## Important Notes

⚠️ **Migration Order Matters**
- Always run CREATE migrations before ALTER migrations
- Always seed categories before inserting products

✅ **Best Practice**
- Keep migrations numbered sequentially
- Never modify old migration files
- Add descriptive comments in new migrations
- Test migrations on development database first

📝 **Documentation**
- Each migration file should have a descriptive comment
- Update this guide when adding new migrations
- Document the purpose and dependencies

## Need Help?

If you encounter any issues:

1. Check the error message carefully
2. Verify migration order
3. Run verification queries above
4. Check that all CREATE migrations ran first
5. Ensure database permissions are correct

---

**Last Updated**: 2024
**Database**: Carbon Sphere Marketplace
**Version**: 1.0