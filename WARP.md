# WARP.md

This file provides guidance to WARP (warp.dev) when working with code in this repository.

## Project Overview

This is a Laravel 12 application for SIF (Société d'Investissement et de Financement) - a financial services platform managing member savings, credits, and financial operations. The system uses PHP 8.2+ with a hybrid frontend architecture combining Laravel Blade views with Vite-powered assets and Tailwind CSS 4.0.

## Development Commands

### Environment Setup
```powershell
# Install PHP dependencies
composer install

# Install Node.js dependencies  
npm install

# Copy environment file and generate app key
copy .env.example .env
php artisan key:generate

# Create SQLite database and run migrations
php artisan migrate

# Start full development environment (concurrent processes)
composer run dev
```

### Daily Development
```powershell
# Start Laravel development server only
php artisan serve

# Start Vite asset compilation with hot reload
npm run dev

# Build production assets
npm run build

# Run database migrations
php artisan migrate

# Fresh migration with seeding
php artisan migrate:fresh --seed

# Clear application caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

### Testing and Quality
```powershell
# Run all tests
php artisan test
# Or alternatively:
composer run test

# Run specific test file
php artisan test tests/Feature/CreditTest.php

# Code formatting with Laravel Pint
vendor/bin/pint

# View logs in real-time
php artisan pail --timeout=0
```

### Background Jobs & Queues
```powershell
# Start queue worker
php artisan queue:work

# Process queue with retry limit
php artisan queue:listen --tries=1

# View failed jobs
php artisan queue:failed
```

## Architecture Overview

### Core Business Domain
The application manages a complete financial services workflow centered around:
- **Member Management**: User registration, profile management, beneficiaries (ayants droit)
- **Financial Products**: Savings plans (épargnes), credit products, membership subscriptions (adhésions)  
- **Credit System**: Credit applications, approval workflow, repayment schedules, payment tracking
- **Document Management**: KYC documents, proof uploads, validation workflow
- **Audit & Compliance**: Complete audit trails, payment proofs, regulatory compliance

### Key Models & Relationships
- `Adherent` (Member) → `User` (1:1), `AyantDroit` (1:N), `Documents` (1:N), `Adhesions` (1:N)
- `Credit` → `Adherent` (N:1), `EcheanceCredit` (1:N), `PaiementCredit` (1:N), `CreditGarantie` (1:N)
- `Adhesion` (Subscription) → `Adherent` (N:1), manages membership to savings plans
- `Epargne` (Savings) → `Adherent` (N:1), tracks savings deposits and withdrawals

### Authentication & Authorization  
Multi-role system with role-based middleware:
- **adherent**: Member portal with self-service capabilities
- **agent**: Customer service operations
- **chef_service**: Service manager permissions  
- **superviseur**: Supervisory oversight
- **admin**: Full system administration

Routes are organized by role with dedicated middleware groups and policy-based authorization.

### Frontend Architecture
- **Backend**: Laravel Blade templating with component-based views
- **Assets**: Vite build system with hot module replacement
- **Styling**: Tailwind CSS 4.0 with modern utility-first approach
- **JavaScript**: Vanilla JS with Laravel Echo for real-time features (configured via `resources/js/bootstrap.js`)

### Data Layer
- **ORM**: Eloquent with factory pattern for testing
- **Migrations**: Schema versioning in `database/migrations/`
- **Seeders**: Development data in `database/seeders/`
- **Storage**: File uploads handled via Laravel's storage system

### Business Logic Organization
- **Controllers**: Feature-organized with separate concern areas (Credit, Payment, Member management)
- **Services**: Custom business logic in `app/Services/`
- **Policies**: Authorization logic for domain models
- **Console Commands**: Background processing for credit penalties (`ApplyCreditPenalties`) and notifications (`SendCreditDueNotifications`)
- **Exports**: Excel export functionality using Maatwebsite/Excel package

### Key Business Workflows
1. **Member Onboarding**: Registration → Profile completion → Document upload → Validation → Account activation
2. **Credit Application**: Eligibility check → Application submission → Agent review → Approval/Rejection → Schedule generation
3. **Payment Processing**: Payment submission → Proof upload → Validation → Balance updates
4. **Document Management**: Upload → Validation workflow → Status tracking

### Configuration Notes
- Uses SQLite for development (auto-created on setup)
- Concurrent development mode runs server, queue worker, logs, and asset compilation
- Rate limiting on credit applications (5/minute) to prevent abuse
- Audit middleware (`AuditActions`) tracks sensitive operations
- File exports include member savings data (`EpargnesExport`)

### Database Schema Patterns
- Soft deletes for audit compliance
- Status enums for workflow states
- JSON columns for flexible data storage
- Foreign key constraints for data integrity
- Timestamp tracking for all business operations