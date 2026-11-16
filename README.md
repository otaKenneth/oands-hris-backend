# AMO — Automated Micro-Operations
### Workforce and Payroll Management

AMO (Automated Micro-Operations) is a workforce and payroll management platform designed to simplify and automate essential business operations, particularly employee administration, attendance monitoring, timekeeping, payroll processing, and workforce-related documentation.

The platform aims to provide small and medium-sized businesses with a centralized system for managing their workforce while reducing manual administrative work and payroll processing errors.

This repository contains the **AMO backend application**, which serves as the foundation for the platform's business logic, database management, and backend services.

---

## Project Objectives

AMO aims to provide businesses with tools to:

- Centralize employee and workforce information.
- Monitor employee attendance and working hours.
- Import and process biometric attendance records.
- Generate and manage Daily Time Records (DTR).
- Automate payroll calculations.
- Calculate Philippine statutory contributions and withholding taxes.
- Generate payroll-related documents and reports.
- Deliver documents and notifications through email.
- Manage platform subscriptions, payments, and transaction records.
- Maintain reliable historical records and auditable business operations.

## Current Modules and Database Scope

The backend is under active development. The following areas are represented in the current database migration structure.

| Module | Description |
|---|---|
| Business and User Management | Business ownership and user relationships for multi-tenant operations. |
| Employee Management | Employee information and workforce-related records. |
| Biometric Imports | Import tracking for attendance files, including CSV and DAT formats. |
| Biometric Logs | Raw attendance punches and employee attendance references. |
| Daily Time Records | Attendance periods, daily records, and adjustment history. |
| Payroll Management | Payroll periods, payroll runs, employee payroll records, earnings, and deductions. |
| Statutory Contributions | Configurable SSS, PhilHealth, and Pag-IBIG contribution structures. |
| Tax Configuration | Configurable withholding tax brackets and effective dates. |
| Generated Documents | Records of generated documents and associated metadata. |
| Email Delivery | Email recipients, delivery statuses, provider information, and delivery attempts. |
| Payments | Business-related platform payment records. |
| Transactions | Payment transaction history, processing statuses, and provider references. |

**Development note:** The presence of a database migration does not necessarily mean that its corresponding business functionality, API endpoints, or user interface is already complete.

## Technology Stack

### Backend
- **PHP 8.3**
- **Laravel 13**
- Laravel Eloquent ORM
- Laravel Database Migrations
- Laravel Service and Application Architecture

### Database
- **MySQL**
- Relational database design
- Foreign key constraints
- JSON columns for structured metadata
- Indexed relationships
- Decimal-based financial values

### Frontend Asset Tooling
- Node.js and npm
- Vite
- Tailwind CSS 4

The current repository is the backend application. Frontend application development and integration should be documented separately when those components are established.

### Testing and Code Quality
- Pest 5
- Pest Laravel Plugin
- Laravel Pint
- Mockery

### Development Tools
- Composer
- Git and GitHub
- Laravel Artisan CLI
- Laravel Boost

---

## Architecture and Design Principles

### 1. Multi-Tenant Architecture

AMO is designed to support multiple businesses within one platform.

Business-owned operational records use `business_id` to associate data with the appropriate business.

The application must enforce tenant isolation through authorization, validation, and properly scoped database queries.

A user must never be able to access another business's records without explicit authorization.

### 2. Historical Data Integrity

Workforce and payroll information changes over time. The system must preserve historical information instead of relying exclusively on current employee settings.

Examples include:

- Employee compensation history.
- Shift and work schedule history.
- Deduction configurations.
- Statutory contribution rates.
- Tax brackets and effective dates.
- Finalized payroll calculations.

Finalized payroll records should preserve the inputs and calculation results used during payroll processing.

### 3. Philippine Payroll Compliance

AMO is designed around Philippine payroll requirements.

The statutory calculation modules should support:

- Social Security System (SSS).
- Philippine Health Insurance Corporation (PhilHealth).
- Home Development Mutual Fund (Pag-IBIG).
- Bureau of Internal Revenue (BIR) withholding tax.

Statutory rates, contribution brackets, and tax rules must be configurable and effective-date-driven.

Government rates and regulations must be verified against official sources before being introduced or updated.

### 4. Financial Data Integrity

Financial records must be accurate, traceable, and protected against accidental deletion or duplicate processing.

The backend should follow these principles:

- Use appropriate decimal precision for monetary values.
- Preserve financial transaction history.
- Use unique payment references.
- Apply idempotency mechanisms to payment processing.
- Maintain appropriate transaction statuses and timestamps.
- Avoid destructive cascading deletes on critical financial records.

### 5. Separation of Financial Operations

AMO distinguishes between two different financial responsibilities:

**Platform Billing**
- Business subscriptions.
- Paid features and add-ons.
- Payment requests.
- Payment provider transactions.

**Employee Payroll**
- Employee gross pay.
- Employee deductions.
- Employer contributions.
- Net salary.
- Future employee salary disbursement.

Platform billing and employee salary disbursement must remain separate business processes and data structures.

---

## Getting Started

### Prerequisites

Ensure the following tools are installed:

- PHP 8.3 or later compatible with the project requirements.
- Composer.
- MySQL.
- Node.js and npm.
- Git.

Required PHP extensions should follow Laravel 13 and the dependencies declared in `composer.json`.

### 1. Clone the Repository

```bash
git clone https://github.com/otaKenneth/oands-hris-backend.git

cd oands-hris-backend
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Configure Environment Variables

Create your environment file:

```bash
cp .env.example .env
```

Generate the application key:

```bash
php artisan key:generate
```

### 4. Configure the Database

Update the database configuration in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=amo
DB_USERNAME=root
DB_PASSWORD=
```

Create the corresponding MySQL database before running the migrations.

### 5. Run Database Migrations

```bash
php artisan migrate
```

For local development, seeders can be executed when the required seed data is available:

```bash
php artisan db:seed
```

### 6. Install Frontend Asset Dependencies

```bash
npm install
```

Build the assets:

```bash
npm run build
```

For development:

```bash
npm run dev
```

### 7. Start the Laravel Development Server

```bash
php artisan serve
```

The application will normally be available at:

```text
http://127.0.0.1:8000
```

---

## Testing

Run the test suite:

```bash
php artisan test
```

Or use the Composer script:

```bash
composer test
```

Tests should cover critical business logic, particularly:

- Employee and business data isolation.
- Attendance imports and processing.
- DTR calculations and adjustments.
- Payroll calculations.
- Statutory contribution calculations.
- Withholding tax calculations.
- Payment processing and transaction integrity.

---

## Development Guidelines

When contributing to AMO, follow these guidelines:

1. Follow Laravel conventions and maintain a consistent application structure.
2. Keep controllers focused on HTTP handling and delegate complex business logic to appropriate services.
3. Validate all incoming requests.
4. Enforce authorization and business-level data isolation.
5. Use database transactions for operations that must succeed or fail together.
6. Preserve historical payroll and financial records.
7. Use database migrations for schema modifications.
8. Avoid hardcoding statutory contribution rates and tax brackets.
9. Write automated tests for critical calculations and workflows.
10. Document significant architectural and database changes.

### Migration Guidelines

Before adding or modifying a migration:

- Review existing table relationships.
- Check whether the required column or table already exists.
- Define appropriate indexes and unique constraints.
- Determine whether foreign keys should cascade, restrict, or nullify on deletion.
- Consider historical data retention.
- Ensure rollback behavior is safe and predictable.

---

## Project Status

**Status:** Active Development

AMO is currently undergoing backend database and application development.

The existing migration structure establishes the foundation for workforce management, attendance, payroll, statutory configurations, document generation, email delivery, and platform billing.

The project will continue to expand as business workflows, backend services, and integrations are implemented.

Features should be considered production-ready only after their implementation, validation, testing, and security requirements have been completed.

---

## Repository

**Backend Repository:**  
https://github.com/otaKenneth/oands-hris-backend

**Default Development Branch:** `main`

---

## License

The project's licensing terms should be defined by the repository owner before public distribution.