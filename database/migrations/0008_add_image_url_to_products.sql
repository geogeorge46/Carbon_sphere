-- DEPRECATED: Use 0010_alter_products_table.sql instead
-- This file is kept for reference only
-- 0010 includes image_url column PLUS performance indexes on seller_id and category_id

ALTER TABLE products ADD COLUMN image_url VARCHAR(500) AFTER product_name;
