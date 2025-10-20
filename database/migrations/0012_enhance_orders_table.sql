-- Enhance orders table with payment details
ALTER TABLE `orders`
ADD COLUMN `payment_id` VARCHAR(100) NULL UNIQUE,
ADD COLUMN `payment_status` ENUM('pending', 'completed', 'failed', 'refunded') DEFAULT 'pending',
ADD COLUMN `payment_method` VARCHAR(50) DEFAULT 'razorpay',
ADD COLUMN `carbon_tokens_earned` INT(11) DEFAULT 0,
ADD INDEX `idx_payment_id` (`payment_id`),
ADD INDEX `idx_user_id` (`user_id`),
ADD INDEX `idx_created_at` (`created_at`);