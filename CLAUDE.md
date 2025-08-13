# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Development Commands

### Frontend (Vue.js + Vite)
- `bun run dev` - Start the Vite development server with HTTPS on localhost
- `bun run prod` or `vite build` - Build production assets
- `bun test` or `vitest` - Run frontend tests
- `bun run test:ui` - Run tests with UI interface
- `bun run test:run` - Run tests once

### Backend (Laravel + PHP)
- `php artisan serve` - Start Laravel development server
- `php artisan migrate` - Run database migrations
- `php artisan db:seed` - Seed the database
- `php artisan tinker` - Interactive PHP shell
- `php artisan cache:clear` - Clear application cache
- `php artisan config:clear` - Clear configuration cache
- `php artisan route:clear` - Clear route cache
- `composer install` - Install PHP dependencies
- `composer update` - Update PHP dependencies

### Testing
- `phpunit` or `npm run phpunit` - Run PHP unit tests
- `phpunit-watcher watch` or `npm run phpunit-watch` - Watch mode for PHP tests
- `vitest` - Run Vue/TypeScript tests
- Tests use in-memory SQLite database for isolation

### Code Quality
- Laravel IDE Helper is configured to auto-generate after composer update
- TypeScript is configured with strict type checking

## Architecture Overview

### Technology Stack
- **Backend**: Laravel 11.x with PHP 8.2+
- **Frontend**: Vue 3 with TypeScript, Vuex 4 for state management, Vue Router 4
- **Database**: MySQL (production), SQLite in-memory (testing)
- **Build Tools**: Vite with Laravel integration
- **CSS**: SCSS with Tailwind CSS
- **Authentication**: JWT Auth with Laravel Sanctum
- **Search**: Algolia integration via Laravel Scout
- **External APIs**: Xero accounting integration

### Project Structure
```
/app                    # Laravel application code
  /Console             # Artisan commands
  /Enums               # PHP enums (StatusEnum)
  /Exceptions          # Custom exceptions
  /Generators          # Excel and PDF generators
  /Helpers             # Helper classes (MailerSetup, OrdersHelper, StatsHelper)
  /Http
    /Controllers       # HTTP controllers (RESTful resources)
    /Middleware        # HTTP middleware
  /Jobs                # Queued jobs (RefreshTokenJob)
  /Mail                # Mail classes
  /Models              # Eloquent models
  /Observers           # Model observers for automatic updates
  /Policies            # Authorization policies
  /Providers           # Service providers
  /Services            # Business logic (XeroService)
  /Traits              # Reusable traits (OrderAttributes, OrderRelations, XeroSync)
  /Transformers        # API response transformers

/resources
  /app                 # Vue.js application
    /components        # Vue components organized by feature
    /lib               # Utility functions and composables
    /mixins            # Vue mixins (being phased out for composition API)
    /orders            # Order-specific components
    /store             # Vuex store modules
    /structures        # TypeScript data structures
  /scss                # SCSS stylesheets
  /types               # TypeScript type definitions
  /views               # Blade templates (for PDFs and emails)

/routes
  /api.php             # API routes (main application routes)
  /web.php             # Web routes (mostly development/debug routes)
```

### Key Business Domain Concepts
- **Orders**: Core entity with complex relationships (products, services, materials, payments)
- **Products**: Can have multiple areas with dimensions and calculations
- **Customers**: Integrated with Xero accounting system
- **Companies**: Customer organizations
- **Projects**: Group related orders
- **Invoices**: Generated from orders, can be standard or reverse charge
- **Tax Deductions**: Special handling for construction industry scheme
- **Payments**: Track partial payments against orders

### API Architecture
- RESTful resource controllers following Laravel conventions
- JWT authentication for API access
- Vuex actions make API calls using a centralized `apiFetch` utility
- Response transformers format data for frontend consumption

### State Management
- Vuex 4 with modular store structure
- Persisted state for user session data
- Modules for items, orders, and stats
- Complex order state with nested mutations for materials, services, products

### Frontend Routing
- Vue Router 4 with lazy-loaded components
- Main routes: orders, customers, products, settings, users
- Nested routes for order editing with tabs

### Development Environment
- HTTPS required (SSL certificates in `/ssl` directory)
- Vite dev server runs on HTTPS localhost
- Path aliases configured: @app, @components, @lib, @store, @types
- Hot module replacement enabled

### Testing Strategy
- PHPUnit for backend unit tests
- Vitest for frontend unit tests
- Tests use factories for data generation
- In-memory SQLite for fast test execution

### External Integrations
- **Xero**: OAuth2 integration for customer sync and invoice creation
- **Algolia**: Search functionality via Laravel Scout
- **wkhtmltopdf**: PDF generation for invoices

### Important Patterns
- Observers automatically update order totals and related data
- Traits split complex model logic (OrderAttributes, OrderRelations, OrderScopes)
- Form requests validate API input
- Policies control authorization
- Global Vue components auto-registered from base directory

### Security Considerations
- JWT tokens for API authentication
- CSRF protection enabled
- XSS protection via Vue's automatic escaping
- Input validation on all API endpoints
- Encrypted cookies
