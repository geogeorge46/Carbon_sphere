ALTER TABLE `users`
CHANGE COLUMN `name` `first_name` VARCHAR(50) NOT NULL,
ADD COLUMN `last_name` VARCHAR(50) NOT NULL AFTER `first_name`,
ADD COLUMN `phone_number` VARCHAR(15) NOT NULL UNIQUE AFTER `email`;
