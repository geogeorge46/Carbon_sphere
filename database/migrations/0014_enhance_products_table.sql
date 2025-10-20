-- Enhance products table with eco rating
ALTER TABLE `products`
ADD COLUMN `eco_rating` DECIMAL(3,1) DEFAULT 4.5 COMMENT 'Rating from 1-5 for eco-friendliness',
ADD COLUMN `rating_count` INT(11) DEFAULT 0,
ADD COLUMN `stock_quantity` INT(11) DEFAULT 100,
ADD INDEX `idx_eco_rating` (`eco_rating`),
ADD INDEX `idx_created_at` (`created_at`);