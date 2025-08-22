# API Routes Structure

## Overview
RESTful API routes serving the Vue.js frontend application. All routes are prefixed with `/api`.

## Main Entry Points
- **api.php**: API route definitions with resource controllers
- **web.php**: Web routes for development, debugging, and OAuth callbacks

## Authentication
- JWT-based authentication
- Protected routes use auth middleware
- Admin-only routes use `can:is_admin` middleware

## Route Organization

### Admin-Protected Routes
Routes requiring admin privileges:
- `GET /api/users` - User management
- `PATCH /api/users/{id}` - Update user
- `GET /api/stats` - Order statistics
- `GET /api/products-data` - Export products data
- `GET /api/services-data` - Export services data
- `GET /api/materials-data` - Export materials data
- `GET /api/tax-deducted-orders` - Export tax deduction orders
- `GET /api/commissions-data` - Export commissions

### Order Management
Core order endpoints:
- `GET /api/orders` - List orders
- `POST /api/orders` - Create order
- `GET /api/orders/{id}` - Get order details
- `PUT /api/orders/{id}` - Update order
- `DELETE /api/orders/{id}` - Delete order
- `GET /api/orders/totals` - Order totals summary
- `GET /api/orders/notes-view` - Orders notes view
- `GET /api/orders/{order}/related` - Related order IDs

### Order Actions
Special order operations:
- `POST /api/orders/{id}/request-{item}` - Send request for item
- `GET /api/orders/{id}/pdf-default` - Generate standard PDF
- `GET /api/orders/{id}/pdf-reverse-charge` - Generate reverse charge PDF
- `GET /api/orders/{id}/default-html` - View HTML invoice
- `GET /api/orders/{id}/footer-html` - Invoice footer HTML
- `GET /api/orders/{id}/reverse-charge-html` - Reverse charge HTML
- `GET /api/orders/{id}/download` - Download invoice
- `POST /api/orders/{id}/send` - Email invoice

### Resource Controllers
Standard CRUD endpoints for all resources:

#### Customer Management
- `/api/customers` - Customer CRUD
- `/api/customers/{id}/orders` - Customer's orders
- `/api/companies` - Company CRUD
- `/api/projects` - Project CRUD

#### Product Configuration
- `/api/products` - Product catalog CRUD
- `/api/products/{id}/updateMeterage` - Update product measurements
- `/api/product_areas` - Product area CRUD
- `/api/areas` - Area definitions CRUD
- `/api/dimensions` - Dimension CRUD
- `/api/floors` - Floor types CRUD
- `/api/floors/prev/{id}` - Previous floor
- `/api/floors/next/{id}` - Next floor
- `/api/grades` - Grade definitions CRUD
- `/api/extras` - Extra options CRUD

#### Order Components
- `/api/order_materials` - Order materials CRUD
- `/api/order_services` - Order services CRUD
- `/api/materials` - Material catalog CRUD
- `/api/services` - Service catalog CRUD
- `/api/expenses` - Expense tracking CRUD
- `/api/payments` - Payment records CRUD
- `/api/tax_deductions` - Tax deduction CRUD

#### System Configuration
- `/api/settings` - Application settings CRUD
- `/api/statuses` - Order status definitions CRUD
- `/api/roles` - User roles CRUD
- `/api/users` - User management (admin only)

### Settings Management
Special settings endpoints:
- `POST /api/settings-query/save` - Save settings
- `GET /api/settings-query/get/all` - Get all settings
- `GET /api/settings-query/get/{key}` - Get specific setting

### Attachments
File management:
- `GET /api/attachments` - List order attachments
- `POST /api/attachments` - Upload attachments
- `DELETE /api/attachments` - Delete attachments

### User
- `GET /api/user` - Get current authenticated user with role

## Response Format
- All endpoints return JSON responses
- Resource controllers follow Laravel conventions
- Transformers format data for frontend consumption
- Pagination included for list endpoints

## Error Handling
- Validation errors return 422 with field-specific messages
- Authentication errors return 401
- Authorization errors return 403
- Not found errors return 404
- Server errors return 500 with error details (in development)