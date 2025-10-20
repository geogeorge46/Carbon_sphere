-- Add carbon tokens and eco rating to users table
ALTER TABLE `users`
ADD COLUMN `carbon_tokens` INT(11) DEFAULT 0,
ADD COLUMN `eco_rating` ENUM('Bronze', 'Silver', 'Gold', 'Green Elite') DEFAULT 'Bronze',
ADD COLUMN `total_carbon_saved` DECIMAL(10,2) DEFAULT 0,
ADD INDEX `idx_carbon_tokens` (`carbon_tokens`),
ADD INDEX `idx_eco_rating` (`eco_rating`);