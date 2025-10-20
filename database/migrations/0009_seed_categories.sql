-- Seed Categories Table with default product categories
-- This migration populates the categories table with initial data

INSERT INTO `categories` (`category_id`, `category_name`) VALUES
(1, 'Electronics'),
(2, 'Clothing'),
(3, 'Food & Beverage'),
(4, 'Home & Garden'),
(5, 'Sports & Outdoors'),
(6, 'Books & Media')
ON DUPLICATE KEY UPDATE `category_name` = VALUES(`category_name`);