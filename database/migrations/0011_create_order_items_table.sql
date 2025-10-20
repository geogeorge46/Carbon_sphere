-- Create order_items table for detailed order information
CREATE TABLE IF NOT EXISTS `order_items` (
  `order_item_id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `order_id` INT(11) NOT NULL,
  `product_id` INT(11) NOT NULL,
  `quantity` INT(11) NOT NULL DEFAULT 1,
  `price` DECIMAL(10,2) NOT NULL,
  `carbon_footprint` DECIMAL(10,2) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`order_id`) REFERENCES orders(`order_id`) ON DELETE CASCADE,
  FOREIGN KEY (`product_id`) REFERENCES products(`product_id`) ON DELETE RESTRICT,
  INDEX `idx_order_id` (`order_id`),
  INDEX `idx_product_id` (`product_id`)
);