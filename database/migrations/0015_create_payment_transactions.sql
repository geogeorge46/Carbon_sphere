-- Create payment_transactions table for Razorpay integration
CREATE TABLE IF NOT EXISTS `payment_transactions` (
  `transaction_id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT(11) NOT NULL,
  `order_id` INT(11) NOT NULL,
  `razorpay_order_id` VARCHAR(100) NOT NULL UNIQUE,
  `razorpay_payment_id` VARCHAR(100) NULL,
  `razorpay_signature` VARCHAR(255) NULL,
  `amount` DECIMAL(10,2) NOT NULL,
  `status` ENUM('initiated', 'successful', 'failed', 'cancelled') DEFAULT 'initiated',
  `response_data` JSON NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES users(`user_id`) ON DELETE CASCADE,
  FOREIGN KEY (`order_id`) REFERENCES orders(`order_id`) ON DELETE CASCADE,
  INDEX `idx_user_id` (`user_id`),
  INDEX `idx_order_id` (`order_id`),
  INDEX `idx_razorpay_order_id` (`razorpay_order_id`),
  INDEX `idx_status` (`status`)
);