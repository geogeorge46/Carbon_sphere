-- Add image_url column to products table
-- This migration enables product image URL support for the seller dashboard
-- Allows sellers to add product images via external URL links

ALTER TABLE `products`
ADD COLUMN `image_url` VARCHAR(500) NULL AFTER `product_name`,
ADD INDEX `idx_seller_id` (`seller_id`),
ADD INDEX `idx_category_id` (`category_id`);