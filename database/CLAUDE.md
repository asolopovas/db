# Database Structure

## Overview
MySQL database with Laravel migrations and seeders. Uses SQLite for testing.

## Directory Structure

### migrations/
Database schema migrations:
- Core tables: orders, customers, products, users
- Junction tables: order_materials, order_services, product_areas
- Supporting tables: companies, projects, invoices, payments
- Catalog tables: materials, services, floors, grades, extras, dimensions, areas
- System tables: roles, settings, statuses

Recent migrations:
- `2025_08_16_111517_modify_address_columns_on_customers_table.php` - Address field updates
- `2025_08_16_181139_add_sku_to_floors_table.php` - Floor SKU tracking
- `2025_08_22_115522_add_services_name_to_order_services_table.php` - Service name caching

### factories/
Model factories for testing and seeding:
- **AreaFactory.php** - Generate test areas
- **CompanyFactory.php** - Generate test companies
- **CustomerFactory.php** - Generate test customers
- **DimensionFactory.php** - Generate test dimensions
- **ExtraFactory.php** - Generate test extras
- **FloorFactory.php** - Generate test floors
- **GradeFactory.php** - Generate test grades
- **InvoiceFactory.php** - Generate test invoices
- **MaterialFactory.php** - Generate test materials
- **OrderFactory.php** - Generate test orders
- **OrderMaterialFactory.php** - Generate test order materials
- **OrderServiceFactory.php** - Generate test order services
- **PaymentFactory.php** - Generate test payments
- **ProductAreaFactory.php** - Generate test product areas
- **ProductFactory.php** - Generate test products
- **RoleFactory.php** - Generate test roles
- **ServiceFactory.php** - Generate test services
- **StatusFactory.php** - Generate test statuses
- **TaxDeductionFactory.php** - Generate test tax deductions
- **UserFactory.php** - Generate test users

### seeds/
Database seeders for initial data:
- **DatabaseSeeder.php** - Main seeder orchestrator
- **CompaniesTableSeeder.php** - Seed initial companies
- **CustomersTableSeeder.php** - Seed initial customers
- **OrderMaterialsTableSeeder.php** - Seed order materials
- **OrderServicesTableSeeder.php** - Seed order services

## Key Tables

### Core Business Tables
- `orders` - Main order records with customer, status, totals
- `customers` - Customer records with Xero integration
- `products` - Product catalog with pricing
- `invoices` - Generated invoices linked to orders
- `payments` - Payment records against orders
- `companies` - Customer organizations
- `projects` - Order grouping mechanism

### Junction Tables
- `order_materials` - Materials assigned to orders
- `order_services` - Services on orders
- `product_areas` - Product area calculations
- `tax_deductions` - Tax deduction records

### Catalog Tables
- `materials` - Material definitions
- `services` - Service definitions
- `floors` - Floor type catalog with SKUs
- `grades` - Product grade definitions
- `extras` - Additional product options
- `dimensions` - Size specifications
- `areas` - Area type definitions
- `statuses` - Order status definitions

### System Tables
- `users` - System users
- `roles` - Permission roles
- `settings` - Application configuration
- `expenses` - Expense tracking

## Relationships
- Orders have many products, materials, services, payments
- Customers belong to companies
- Orders belong to projects
- Products have many areas through product_areas
- Orders track tax deductions

## Testing Database
- Uses in-memory SQLite for unit tests
- Factories generate test data
- Migrations run fresh for each test suite