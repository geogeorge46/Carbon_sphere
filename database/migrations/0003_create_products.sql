CREATE TABLE IF NOT EXISTS `products` (
  `product_id` INT AUTO_INCREMENT PRIMARY KEY,
  `seller_id` INT,
  `category_id` INT,
  `product_name` VARCHAR(100) NOT NULL,
  `description` TEXT,
  `price` DECIMAL(10, 2) NOT NULL,
  `carbon_footprint` FLOAT NOT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`seller_id`) REFERENCES `users`(`user_id`) ON DELETE SET NULL,
  FOREIGN KEY (`category_id`) REFERENCES `categories`(`category_id`) ON DELETE SET NULL
) ENGINE=InnoDB;
