-- Fix orders table by adding missing columns
ALTER TABLE `orders`
ADD COLUMN `payment_id` VARCHAR(100) NULL UNIQUE,
ADD COLUMN `payment_method` VARCHAR(50) DEFAULT 'razorpay',
ADD COLUMN `carbon_tokens_earned` INT(11) DEFAULT 0,
ADD INDEX `idx_payment_id` (`payment_id`),
ADD INDEX `idx_user_id` (`user_id`),
ADD INDEX `idx_created_at` (`created_at`);

-- Update payment_status column to have all required values
ALTER TABLE `orders` 
MODIFY COLUMN `payment_status` ENUM('pending', 'completed', 'failed', 'refunded') DEFAULT 'pending';