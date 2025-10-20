-- Add delivery information columns to orders table
ALTER TABLE `orders` 
ADD COLUMN `delivery_address` VARCHAR(255) AFTER `total_carbon`,
ADD COLUMN `delivery_city` VARCHAR(100) AFTER `delivery_address`,
ADD COLUMN `delivery_state` VARCHAR(100) AFTER `delivery_city`,
ADD COLUMN `delivery_postal_code` VARCHAR(10) AFTER `delivery_state`,
ADD COLUMN `delivery_phone` VARCHAR(15) AFTER `delivery_postal_code`;