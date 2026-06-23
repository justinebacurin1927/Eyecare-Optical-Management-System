# Eyecare Optical Management System

A clinic and point-of-sale management system for optical/eyecare clinics built with **Laravel 11**, **Bootstrap 5**, **Alpine.js**, and **Chart.js**.

## Features

- **Role-based access** — Admin, Staff, and Doctor roles with different permissions
- **Dashboard** — Summary cards (products, patients, sales, users) + pie chart (products by category) + bar chart (monthly sales trend)
- **User Management** (Admin only) — Add, edit, activate/deactivate, and delete users
- **Inventory Management**
  - **Categories** — CRUD with search, product count tracking
  - **Products** — Add/list products with pricing, reorder levels
  - **Frames & Lens Types** — Manage frame inventory and lens type catalog
- **Patient Management** (Admin only) — CRUD with prescription details (sphere, cylinder, axis, addition, PD, tint, frame, lens type)
- **Point of Sale** — New transaction with dynamic line items, discount, subtotal/total calculation; sales list with edit/delete
- **Profile** — Update profile info, change password, delete account

## Requirements

- PHP 8.2+
- Composer
- MySQL/MariaDB
- Node.js & NPM (for frontend assets)

## Installation

```bash
# 1. Clone the repository
git clone <repo-url> eyecare-laravel
cd eyecare-laravel

# 2. Install PHP dependencies
composer install

# 3. Install frontend dependencies
npm install

# 4. Configure environment
cp .env.example .env
php artisan key:generate
# Edit .env with your database credentials (DB_DATABASE, DB_USERNAME, DB_PASSWORD)

# 5. Create the database
mysql -u root -p -e "CREATE DATABASE optics_db"

# 6. Run migrations and seed data
php artisan migrate
php artisan db:seed

# 7. Build frontend assets
npm run build

# 8. Start the development server
php artisan serve
```

## Default Accounts

| Role | Username | Email | Password |
|------|----------|-------|----------|
| **Admin** | `justine` | admin@eyecare.test | `admin123` |
| **Staff** | `rogelyn` | staff@eyecare.test | `staff123` |
| **Doctor** | `drsantos` | doctor@eyecare.test | `doctor123` |

## Role Permissions

| Feature | Admin | Staff | Doctor |
|---------|-------|-------|--------|
| Dashboard | ✅ | ✅ | ✅ |
| Users (CRUD) | ✅ | ❌ | ❌ |
| Categories | ✅ | ✅ | ✅ |
| Products | ✅ | ✅ | ✅ |
| Frames & Lens Types | ✅ | ✅ | ✅ |
| Patients (CRUD) | ✅ | ❌ | ❌ |
| Point of Sale | ✅ | ✅ | ✅ |

## Tech Stack

- **Backend:** Laravel 11, PHP 8.2+
- **Database:** MySQL
- **Frontend:** Bootstrap 5.3 (CDN), Alpine.js 3, Chart.js 4
- **Build:** Vite 6
- **Design:** Custom CSS (ported from vanilla PHP version)

## Build Commands

```bash
npm run dev    # Development with hot-reload
npm run build  # Production build
```
