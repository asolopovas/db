# Testing Structure

## Overview
Comprehensive test suite using PHPUnit for backend and Vitest for frontend testing.

## Directory Structure

### Unit/
Backend unit tests for Laravel application:
- Model tests
- Controller tests
- Service tests
- Helper tests
- Observer tests
- Policy tests

### Feature/
Integration and feature tests:
- API endpoint tests
- Authentication flow tests
- Order workflow tests
- Payment processing tests
- PDF generation tests
- Email sending tests

### JavaScript/
Frontend tests using Vitest:
- Component tests
- Store tests
- Utility function tests
- Router tests

## Testing Strategy

### Backend Testing
- **Framework**: PHPUnit
- **Database**: In-memory SQLite
- **Data Generation**: Model factories
- **Assertions**: Laravel test helpers
- **Coverage**: Models, controllers, services, observers

### Frontend Testing
- **Framework**: Vitest
- **Component Testing**: Vue Test Utils
- **Store Testing**: Vuex mock stores
- **API Mocking**: Mock API responses
- **Coverage**: Components, store modules, utilities

## Key Test Files

### API Tests
- Test all RESTful endpoints
- Verify authentication and authorization
- Validate response structures
- Check business logic

### Order Tests
- Order creation and updates
- Total calculations
- Material and service management
- Payment processing
- Invoice generation

### Integration Tests
- Xero synchronization
- PDF generation
- Email sending
- File uploads

## Test Commands
- `phpunit` - Run all PHP tests
- `phpunit-watcher watch` - Watch mode for PHP tests
- `vitest` - Run all JavaScript tests
- `vitest --ui` - Run tests with UI
- `vitest run` - Run tests once

## Test Database
- Separate test database configuration
- Migrations run before test suite
- Database transactions for isolation
- Factories for test data generation

## Mocking
- External API calls mocked (Xero)
- Email sending mocked
- File system operations mocked
- Time-based tests use Carbon test helpers