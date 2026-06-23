# Eyecare Optical Management System

Laravel 11 application for managing an optical/eyecare clinic — handles user management, product inventory, patient registration with prescriptions, and point-of-sale transactions.

---

## Improvements Made

### Code Quality

| Improvement | Details |
|---|---|
| **Form Request classes** | 15 dedicated request classes replace inline validation in controllers (`StoreCategoryRequest`, `StoreProductRequest`, `StorePatientRequest`, `StoreSaleTransactionRequest`, `StoreUserRequest`, `StoreFrameRequest`, `StoreLensTypeRequest`, `SearchPatientRequest`, and their `Update*` counterparts). Located in `app/Http/Requests/`. |
| **Policy classes** | 5 policy classes for authorization: `CategoryPolicy`, `ProductPolicy`, `PatientPolicy`, `SaleTransactionPolicy`, `UserPolicy`. Registered via `AuthServiceProvider` in `app/Providers/`. |
| **Service layer** | Business logic extracted from controllers into `app/Services/`: `PatientService` (patient + prescription CRUD), `SaleService` (transactions with stock management), `DashboardService` (aggregated stats). |
| **Dedicated controllers** | `FrameController` and `LensTypeController` handle frame/lens CRUD separately from `ProductController`. |
| **Enums** | `App\Enums\Role` and `App\Enums\PaymentStatus` replace hardcoded role strings and payment statuses throughout the codebase. |
| **Return types** | All controller methods now have proper return type hints. |
| **Pagination** | Category, product, and user listings use pagination (20 per page) instead of loading all records. |

### Architecture

| Improvement | Details |
|---|---|
| **Events/Listeners** | `SaleCompleted` event with `ProcessSaleInventory` listener decouples stock management from the sale controller. |
| **Middleware** | `CheckRole` middleware simplified (removed duplicated auth check). New `CheckInactiveUser` middleware handles inactive user logout separately. |
| **Route organization** | Route definitions reordered: `patients/search` now defined before the resource route to prevent 404 conflicts. `FrameController` and `LensTypeController` routes replace the old `ProductController::storeFrame/storeLensType`. |
| **Service Provider registration** | `AuthServiceProvider` and `EventServiceProvider` registered in `bootstrap/providers.php`. |

### Database

| Improvement | Details |
|---|---|
| **Database indexes** | New migration `2026_06_23_000009_add_indexes_to_tables` adds indexes on: `patients` (`first_name`, `last_name`, `phone_number`), `sale_transactions` (`transaction_date`, `payment_status`), `products` (`name`), `users` (`role`, `status`). |
| **Model factories** | Factory classes for all models: `CategoryFactory`, `ProductFactory`, `FrameFactory`, `LensTypeFactory`, `PatientFactory`, `PrescriptionFactory`, `SaleTransactionFactory`, `SaleItemFactory`. |
| **`HasFactory` trait** | Added to all models to enable factory support. |

### Testing

| Statistic | Value |
|---|---|
| **Total tests** | 78 (was 25) |
| **Test assertions** | 189 |
| **Test database** | Separate `optics_db_test` MySQL database used during testing. |

Test files cover:
- `CategoryControllerTest` — CRUD, validation, product constraint
- `ProductControllerTest` — CRUD, validation
- `PatientControllerTest` — CRUD, search, prescription creation
- `SaleTransactionControllerTest` — CRUD, stock validation, stock decrement/restore
- `UserControllerTest` — CRUD, toggle status, last admin protection, role-based access
- `DashboardControllerTest` — view rendering, guest redirect
- `FrameControllerTest` / `LensTypeControllerTest` — CRUD, validation
- `CheckRoleMiddlewareTest` — role-based access control for Admin/Staff/Doctor
- `CsvPatientSeederTest` — CSV import verification

### CSV Data & Seeding

| Feature | Details |
|---|---|
| **`database/seeders/data/patients.csv`** | 103 patient records with full prescription details (sphere, cylinder, axis, frame, lens type, tint). |
| **`CsvPatientSeeder`** | Imports the CSV into `patients` and `prescriptions` tables during `php artisan db:seed`. |
| **`ImportPatientsCsv` command** | Artisan command: `php artisan import:patients-csv {file?}` to import any CSV with the same format. |
| **`SaleSeeder`** | Seeds 36 realistic sale transactions spanning 12 months with 70 line items — includes Paid, Pending, and Refunded statuses with varying discounts. |
| **`TestDatabaseSeeder`** | Minimal seed data for the test environment. |

### Security

| Fix | Details |
|---|---|
| **XSS in dashboard** | Chart.js data now passed via `data-*` attributes with `JSON.parse()` instead of raw `{!! json_encode() !!}` output. |
| **Search input validation** | `SearchPatientRequest` validates the `q` parameter as `nullable|string|max:100`. |

### Performance

| Fix | Details |
|---|---|
| **N+1 query** | `SaleTransactionController::destroy` now eager-loads `saleItems.product` before iterating. |
| **Database indexes** | Added on columns used in `WHERE`, `LIKE`, and `GROUP BY` queries. |

### Containerization

| Feature | Details |
|---|---|
| **Docker Compose** | 5 services: `nginx` (web server), `php` (PHP 8.3 FPM), `db` (MySQL 8), `phpmyadmin`, `worker` (queue listener). |
| **Multi-stage build** | Node stage builds Vite assets, then PHP-FPM stage produces a lean production image. |
| **Minimal port footprint** | Nginx on `8082`, phpMyAdmin on `8083`, MySQL on `3307` — avoids conflicts with existing containers. |
| **`.env.docker`** | Separate environment file for the Docker setup. Copy from `.env.docker.example` and set `APP_KEY`. |
| **Makefile** | Convenience commands: `make up`, `make down`, `make migrate`, `make test`, `make shell`. |

---

## Setup

### Without Docker

```bash
cp .env.example .env
# Edit .env with your database credentials
composer install
npm install && npm run build
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

### With Docker

```bash
cp .env.docker.example .env.docker
# Ensure APP_KEY in .env.docker matches your local .env or generate a new one
docker compose up -d --build
docker compose exec php php artisan migrate --seed
```

The app is then available at `http://localhost:8082` and phpMyAdmin at `http://localhost:8083`.

#### Makefile Commands

| Command | Description |
|---|---|
| `make up` | Start all containers |
| `make down` | Stop all containers |
| `make build` | Rebuild images |
| `make shell` | Open shell in the PHP container |
| `make artisan cmd=<command>` | Run any Artisan command |
| `make migrate` | Run `php artisan migrate --force` |
| `make seed` | Run `php artisan db:seed --force` |
| `make test` | Run the test suite inside the container |
| `make logs` | Tail all container logs |

## Default Users

| Role | Email | Password |
|---|---|---|
| Admin | admin@eyecare.test | admin123 |
| Staff | staff@eyecare.test | staff123 |
| Doctor | doctor@eyecare.test | doctor123 |

## Running Tests

```bash
php artisan config:clear
php artisan view:clear
php vendor/bin/phpunit
```

## Importing CSV Data

```bash
php artisan import:patients-csv
# Or specify a custom file:
php artisan import:patients-csv /path/to/file.csv
```

## CSV Format

Columns: `first_name,middle_name,last_name,birthdate,gender,phone_number,address,sphere,cylinder,axis,addition,pd,frame_id,lens_type_id,tint`
