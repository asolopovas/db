# Frontend Application Structure

## Overview
Vue 3 + TypeScript single-page application with Vuex state management and Vue Router.

## Main Entry Points

### Core Files
- **app.ts**: Application bootstrap, initializes Vue app with router, store, and global components
- **App.vue**: Root component with router-view
- **MainLayout.vue**: Main application layout wrapper

### Top-Level Views
- **Home.vue**: Dashboard/home page
- **Login.vue**: Authentication page
- **Orders.vue**: Order listing and management
- **Items.vue**: Generic CRUD interface for various resources
- **Item.vue**: Single item view/edit component
- **Settings.vue**: Application settings dashboard
- **NotFound.vue**: 404 error page

## Directory Structure

### bootstrap/
- **register-base-components.ts**: Auto-registers base UI components
- **register-global-components.ts**: Registers globally available components

### components/
#### Base UI Components (components/base/)
- Reusable form inputs and UI elements
- Auto-registered globally

#### Specialized Components
- **ActiveTabs.vue**: Tab navigation component
- **ChartSection.vue**: Chart display wrapper
- **ChartStats.vue**: Statistical charts for orders
- **EditRow.vue**: Inline editing component
- **ItemsOptions.vue**: Options menu for list items
- **Modal.vue**: Modal dialog wrapper
- **Options.vue**: Generic options component
- **PageHeader.vue**: Page title header
- **Pagination.vue**: Pagination controls
- **Spinner.vue**: Loading indicator
- **StatsButton.vue**: Statistics action button
- **StatsTable.vue**: Statistics table display
- **Timer.vue**: Time tracking component
- **ViewRow.vue**: Read-only row display

#### Form Components (components/form-components/)
- Specialized form inputs and controls

#### Navigation (components/MainNav/)
- Main navigation components

### lib/
- **router.ts**: Vue Router configuration with route definitions
- **global-helpers.ts**: Global utility functions and helpers
- **apiFetch.ts**: Centralized API communication utility
- Various utility functions and composables

### mixins/
- Vue 2-style mixins (being migrated to Composition API)

### nav/
- Navigation-related components

### notifications/
- Notification and alert components

### orders/
- **OrderEdit.vue**: Main order editing interface
- **orders/components/**: Order-specific components
  - **Details.vue**: Order details tab
  - **TaxDeductions.vue**: Tax deduction management
  - **Materials.vue**: Order materials management
  - **Products.vue**: Order products management
  - **Services.vue**: Order services management
  - **Expenses.vue**: Order expenses tracking
  - **Payments.vue**: Payment management
  - **Areas.vue**: Product areas configuration

### plugins/
- Vue plugins and extensions

### store/
#### Root Store
- **rootStore.ts**: Vuex store initialization
- **actions.ts**: Global actions
- **mutations.ts**: Global mutations
- **emptyUser.ts**: Default user state

#### Store Modules (store/modules/)
- **items/**: Generic CRUD state management
  - actions.ts, mutations.ts, state.ts
- **orders/**: Order-specific state
  - Complex nested state for order editing
  - Separate actions/mutations for materials, services, products
- **stats/**: Statistics and reporting state

#### Plugins (store/plugins/)
- **persist.ts**: State persistence to localStorage

### structures/
- TypeScript interfaces and type definitions
- Data structure definitions for API communication

## Routing Structure

### Main Routes
- `/` - Home dashboard
- `/login` - Authentication
- `/orders` - Order listing
- `/orders/:id/*` - Order editing with nested tabs
- `/customers` - Customer management
- `/settings-dashboard` - Settings interface
- `/users` - User management (admin only)

### Generic Resource Routes
- `/areas`, `/companies`, `/dimensions`, `/extras`, `/floors`, `/grades`
- `/materials`, `/projects`, `/services`, `/settings`, `/statuses`
- All use the generic `Items.vue` component

### Order Edit Sub-routes
- `/orders/:id/details` - Order details
- `/orders/:id/products` - Product configuration
- `/orders/:id/materials` - Materials management
- `/orders/:id/services` - Services management
- `/orders/:id/expenses` - Expense tracking
- `/orders/:id/payments` - Payment management
- `/orders/:id/areas` - Area configuration
- `/orders/:id/tax-deductions` - Tax deductions

## State Management Pattern
- Vuex 4 with TypeScript
- Modular store structure
- Persisted authentication state
- Complex nested mutations for order editing
- Actions handle API calls via apiFetch utility

## Key Features
- Component-based architecture
- TypeScript for type safety
- Lazy-loaded routes for performance
- Auto-registered base components
- Centralized API communication
- Persistent state management
- Role-based route guards