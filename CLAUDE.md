# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a Laravel 12 application with a Dockerized development environment consisting of:
- Laravel app with PHP 8.4 FPM (Alpine)
- Nginx web server
- MySQL 8.0 database
- Vite for frontend asset compilation with TailwindCSS

## Development Commands

### Docker Environment
- Start the full environment: `docker-compose up -d`
- Stop environment: `docker-compose down`
- Access app container: `docker exec -it zalvadora_app sh`

### Laravel Commands (run inside src/ directory or container)
- Development server: `composer dev` (runs concurrent server, queue, logs, and vite)
- Run tests: `composer test` or `php artisan test`
- Code formatting: `./vendor/bin/pint` (Laravel Pint)
- Database migrations: `php artisan migrate`
- Generate app key: `php artisan key:generate`
- Clear config cache: `php artisan config:clear`

### Frontend Commands (run inside src/ directory)
- Development build: `npm run dev` (Vite with hot reload)
- Production build: `npm run build`

### Testing
- Run all tests: `php artisan test` or `./vendor/bin/phpunit`
- Unit tests are in `tests/Unit/`
- Feature tests are in `tests/Feature/`
- Tests use SQLite in-memory database

## Project Structure

### Core Directories
- `src/` - Laravel application root
- `docker/` - Docker configuration files
- `docker/app/Dockerfile` - PHP-FPM container with Composer
- `docker/nginx/default.conf` - Nginx configuration

### Laravel Structure
- `app/Http/Controllers/` - Application controllers
- `app/Models/` - Eloquent models
- `database/migrations/` - Database schema migrations
- `database/factories/` - Model factories for testing
- `resources/views/` - Blade templates
- `resources/css/` and `resources/js/` - Frontend assets
- `routes/web.php` - Web routes
- `config/` - Configuration files

## Environment Setup

The project uses environment variables defined in `.env` file. Copy `.env.example` to `.env` for local setup.

Database connection for Docker:
- Host: `db` (Docker service name)
- Port: 3306
- Database: `zalvadora_db`
- User: `root`
- Password: `root`

## Port Configuration

- Application: http://localhost:8080 (Nginx)
- Database: localhost:3306 (MySQL)

## Dependencies

### PHP Dependencies (Composer)
- Laravel Framework 12.x
- Laravel Sanctum for API authentication
- L5 Swagger (darkaonline/l5-swagger) for API documentation
- Laravel Tinker for REPL
- PHPUnit for testing
- Laravel Pint for code formatting
- Laravel Pail for log tailing
- Ramsey UUID for UUID generation

### Node Dependencies
- Vite for asset compilation
- TailwindCSS for styling
- Laravel Vite Plugin
- Concurrently for running multiple commands

## API Documentation

The API follows RESTful principles with versioning and is fully documented with Swagger/OpenAPI.

### Swagger Documentation
- **URL**: http://localhost:8080/api/documentation
- **JSON Spec**: http://localhost:8080/docs/api-docs.json
- **Interactive UI**: Full Swagger UI with request testing capabilities

### Base URL
- Local: `http://localhost:8080/api/v1`

### Authentication
- Uses Laravel Sanctum for token-based authentication
- Include `Authorization: Bearer {token}` header for protected endpoints
- Security scheme documented in Swagger as `bearerAuth`

### Plans API
- `GET /plans` - List all plans (requires admin)
- `POST /plans` - Create plan (requires admin)
- `GET /plans/{id}` - Show plan details
- `PUT /plans/{id}` - Update plan (requires admin)
- `DELETE /plans/{id}` - Delete plan (requires admin)

### Swagger Features Implemented
- **Complete API specification** with OpenAPI 3.0
- **Request/Response schemas** for all endpoints
- **Authentication documentation** with Bearer token
- **Error response schemas** (400, 401, 403, 404, 422)
- **Interactive testing** directly from the documentation
- **Example requests and responses** for all operations
- **Data validation rules** clearly documented

## DDD Architecture Implementation

### Domain Layer (`app/Domain/`)
- **Entities**: Plan, Company, User with business logic
- **Value Objects**: Money, UserLimit, PlanName, Email, etc.
- **Events**: Domain events like PlanCreated
- **Repository Interfaces**: Contracts for data persistence

### Application Layer (`app/Application/`)
- **DTOs**: Data Transfer Objects for API boundaries
- **Use Cases**: Business operations (CreatePlan, UpdatePlan, etc.)
- **Services**: Application-specific services

### Infrastructure Layer (`app/Infrastructure/`)
- **Models**: Eloquent models (PlanModel, CompanyModel, UserModel)
- **Repositories**: Concrete repository implementations
- **Events**: Event handling infrastructure

### Presentation Layer (`app/Presentation/`)
- **Controllers**: API controllers with dependency injection
- **Requests**: Form validation classes
- **Resources**: API response transformers
- **Policies**: Authorization logic

## Testing

The application includes comprehensive tests:

### Unit Tests
- Domain Value Objects (Money, UserLimit, PlanName, etc.)
- Domain Entities (Plan, Company, User)
- Use Cases and business logic

### Feature Tests
- API endpoint functionality
- Authentication and authorization
- Database integration

Run tests with: `docker exec zalvadora_app php artisan test`

## Database Schema

### Plans Table
- `id` (UUID) - Primary key
- `name` - Plan name
- `monthly_price_amount` - Price in cents
- `monthly_price_currency` - Currency code
- `user_limit` - Maximum users allowed
- `features` - JSON array of features

### Companies Table
- `id` (UUID) - Primary key
- `name` - Company name
- `email` - Company email (unique)
- `plan_id` - Foreign key to plans

### Users Table
- `id` (UUID) - Primary key
- `name` - User name
- `email` - User email (unique)
- `company_id` - Foreign key to companies
- `role` - User role (admin/user)
- `password` - Hashed password