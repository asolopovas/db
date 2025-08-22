# Backend Application Structure

## Overview
Laravel 11 backend application handling business logic, API endpoints, and data persistence.

## Directory Structure

### Console/
- **Kernel.php**: Artisan command scheduler and console kernel configuration

### Enums/
- **StatusEnum.php**: Order status enumerations (draft, pending, completed, etc.)

### Exceptions/
- **Handler.php**: Global exception handling and error responses
- **IncorrectEmailException.php**: Custom exception for email validation errors

### Generators/
- **ExcelGenerator.php**: Creates Excel exports for orders, products, and reports
- **HtmlPdfGenerator.php**: Generates PDF invoices using wkhtmltopdf

### Helpers/
- **MailerSetup.php**: SMTP configuration and mail transport setup
- **OrdersHelper.php**: Order calculations, totals, and business logic helpers
- **StatsHelper.php**: Statistical calculations for orders and revenue

### Http/Controllers/
#### Core Resources
- **OrdersController.php**: Main order management (CRUD, PDF generation, email sending)
- **CustomersController.php**: Customer management with Xero integration
- **ProductsController.php**: Product catalog management
- **InvoiceController.php**: Invoice generation and management

#### Supporting Resources
- **OrderMaterialsController.php**: Materials assigned to orders
- **OrderServicesController.php**: Services within orders
- **ProductAreasController.php**: Product area calculations
- **PaymentsController.php**: Payment tracking and reconciliation
- **TaxDeductionsController.php**: Construction industry scheme deductions
- **ExpensesController.php**: Order-related expenses

#### Configuration
- **SettingsController.php**: Application settings and attachments
- **UsersController.php**: User management
- **RolesController.php**: Permission roles

#### Components
- **MaterialsController.php**: Material catalog
- **ServicesController.php**: Service catalog
- **FloorsController.php**: Floor types
- **GradesController.php**: Product grades
- **ExtrasController.php**: Additional product options
- **DimensionsController.php**: Product dimensions
- **AreasController.php**: Area types
- **StatusesController.php**: Order status management
- **CompaniesController.php**: Customer companies
- **ProjectsController.php**: Project grouping

### Models/
#### Core Entities
- **Order.php**: Central order model with complex relationships
- **Customer.php**: Customer entity with Xero sync
- **Product.php**: Product catalog items
- **Invoice.php**: Generated invoices
- **User.php**: System users

#### Related Models
- **OrderMaterial.php**: Junction table for order materials
- **OrderService.php**: Junction table for order services
- **ProductArea.php**: Product area calculations
- **Payment.php**: Payment records
- **TaxDeduction.php**: Tax deduction records
- **Company.php**: Customer organizations
- **Project.php**: Order grouping

#### Catalog Models
- **Material.php**: Material definitions
- **Service.php**: Service definitions
- **Floor.php**: Floor type definitions
- **Grade.php**: Grade definitions
- **Extra.php**: Extra options
- **Dimension.php**: Size definitions
- **Area.php**: Area type definitions
- **Status.php**: Status definitions

### Observers/
- **OrderLastPaymentDateUpdate.php**: Updates order payment dates
- **ProductMeterageUpdate.php**: Recalculates product measurements
- **UpdateOrderSums.php**: Maintains order totals when items change

### Policies/
- **UserPolicy.php**: User authorization rules

### Providers/
- **AppServiceProvider.php**: Application bootstrapping
- **AuthServiceProvider.php**: Authentication configuration
- **CustomValidatorsProvider.php**: Custom validation rules
- **EventServiceProvider.php**: Event listener mappings
- **RouteServiceProvider.php**: Route configuration

### Services/
- **XeroService.php**: Xero OAuth2 integration for accounting sync

### Traits/
- **OrderAttributes.php**: Computed order properties and accessors
- **OrderDataBuilder.php**: Order data assembly for API responses
- **OrderRelations.php**: Order relationship definitions
- **OrderScopes.php**: Order query scopes for filtering
- **XeroSync.php**: Xero synchronization functionality

### Transformers/
- **OrderTransformer.php**: Formats order data for API responses

## Key Features
- RESTful API architecture
- Eloquent ORM with complex relationships
- Observer pattern for automatic updates
- Trait composition for model organization
- Policy-based authorization
- Service layer for external integrations