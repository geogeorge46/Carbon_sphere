---
description: Repository Information Overview
alwaysApply: true
---

# Carbon Sphere Information

## Summary
Carbon Sphere is a PHP MVC application for listing products with carbon footprint tracking. It's designed to run with XAMPP on Windows but can be adapted for other environments. The application provides features for product listing, user authentication, shopping cart functionality, order management, and seller dashboard.

## Structure
- **app/**: Core application code (MVC architecture)
  - **Config/**: Configuration files
  - **Controllers/**: Controller classes
  - **Core/**: Core framework classes
  - **Helpers/**: Helper functions
  - **Models/**: Data models
  - **Services/**: Business logic services
  - **Views/**: UI templates
- **database/**: Database migrations and seeds
- **public/**: Web-accessible files
  - **assets/**: Static resources
  - **css/**: Stylesheets
  - **js/**: JavaScript files
  - **uploads/**: User-uploaded content

## Language & Runtime
**Language**: PHP
**Version**: Compatible with PHP 7.x/8.x (XAMPP default)
**Database**: MySQL
**Architecture**: Custom MVC framework

## Dependencies
No formal dependency management system (Composer) is used. The project relies on:
- PHP PDO for database access
- Native PHP session management
- Custom autoloader for class loading

## Build & Installation
```bash
# 1. Place in XAMPP htdocs folder
# 2. Create database
mysql -u root -p -e "CREATE DATABASE carbon_sphere"
# 3. Import migrations
mysql -u root -p carbon_sphere < database\migrations\0001_create_users.sql
mysql -u root -p carbon_sphere < database\migrations\0002_create_categories.sql
# Continue with remaining migration files (0003-0016)
# 4. Configure app/Config/config.php if needed
# 5. Access via http://localhost/carbon-sphere/public
```

## Main Files
**Entry Point**: public/index.php
**Bootstrap**: app/bootstrap.php
**Configuration**: app/Config/config.php
**Core Classes**:
- app/Core/Core.php (Router)
- app/Core/Controller.php (Base controller)
- app/Core/Model.php (Base model)
- app/Core/DB.php (Database connection)

## Database Schema
The database includes tables for:
- users (authentication and profile)
- categories (product categorization)
- products (items for sale with carbon footprint data)
- carts (shopping cart functionality)
- orders (purchase tracking)
- order_items (items within orders)
- payment_transactions (payment processing)

## Development
**PHP Linting**:
```bash
"C:\xampp\php\php.exe" -l "c:\xampp\htdocs\carbon-sphere\app\Controllers\AuthController.php"
```

**Recommended Enhancements**:
- Add Composer for dependency management
- Implement a migration runner script
- Add unit/integration tests
- Move DB credentials to environment variables
- Add CSRF protection