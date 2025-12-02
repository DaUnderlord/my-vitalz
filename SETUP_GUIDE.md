# MyVitalz Laravel Project - Environment Setup Guide

## 📋 Project Overview

**Project Name:** MyVitalz Healthcare Platform  
**Framework:** Laravel 10.x  
**PHP Version Required:** ^8.1  
**Database:** MySQL (default)  
**Frontend:** Vite + Bootstrap 5 + Sneat Admin Theme

### Application Features
- **Multi-user System:** Patient, Doctor, Hospital, and Pharmacy dashboards
- **Pharmacy Network Management:** Network-based system for pharmacies to manage doctors, hospitals, and patients
- **E-Prescription Workflow:** Complete prescription management system
- **Inventory Management:** Medication tracking with tiered pricing
- **Patient Monitoring:** Medication compliance and refill notifications
- **Doctor Rewards System:** Percentage-based rewards with virtual pharmacy links

---

## ⚠️ Critical Issues Identified

### 🔴 Missing Files
1. **`public/index.php`** - Laravel entry point is **MISSING**
   - This is the main entry point for all HTTP requests
   - Without this file, the application **CANNOT RUN**

### 🟡 System Requirements Not Met
Based on memory, your system is missing:
- **PHP** - Not installed
- **Composer** - Not installed  
- **Docker** - Not installed

**Currently Available:**
- ✅ Node.js v22.14.0
- ✅ npm 10.9.0

---

## 🛠️ Required Software Installation

### 1. Install PHP 8.1 or Higher

**Windows Installation:**
1. Download PHP 8.1+ from: https://windows.php.net/download/
2. Extract to `C:\php`
3. Add `C:\php` to your system PATH
4. Copy `php.ini-development` to `php.ini`
5. Enable required extensions in `php.ini`:
   ```ini
   extension=pdo_mysql
   extension=mbstring
   extension=openssl
   extension=curl
   extension=fileinfo
   extension=zip
   ```

**Verify Installation:**
```powershell
php -v
```

### 2. Install Composer

1. Download from: https://getcomposer.org/download/
2. Run the Windows installer
3. Verify installation:
   ```powershell
   composer --version
   ```

### 3. Install MySQL Database

**Option A: XAMPP (Recommended for Windows)**
1. Download XAMPP from: https://www.apachefriends.org/
2. Install with MySQL component
3. Start MySQL from XAMPP Control Panel

**Option B: MySQL Standalone**
1. Download from: https://dev.mysql.com/downloads/mysql/
2. Install MySQL Server
3. Remember your root password

---

## 📦 Project Setup Steps

### Step 1: Install PHP Dependencies

```powershell
# Navigate to project directory
cd C:\Users\HP\Downloads\app

# Install Composer dependencies
composer install
```

### Step 2: Create Missing Entry Point

**Create `public/index.php`** with the following content:

```php
<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Check If The Application Is Under Maintenance
|--------------------------------------------------------------------------
|
| If the application is in maintenance / demo mode via the "down" command
| we will load this file so that any pre-rendered content can be shown
| instead of starting the framework, which could cause an exception.
|
*/

if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| this application. We just need to utilize it! We'll simply require it
| into the script here so we don't need to manually load our classes.
|
*/

require __DIR__.'/../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request using
| the application's HTTP kernel. Then, we will send the response back
| to this client's browser, allowing them to enjoy our application.
|
*/

$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);
```

### Step 3: Environment Configuration

```powershell
# Copy .env.example to .env (if not already done)
copy .env.example .env

# Generate application key
php artisan key:generate
```

### Step 4: Configure Database

Edit `.env` file with your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=myvitalz
DB_USERNAME=root
DB_PASSWORD=your_password_here
```

### Step 5: Create Database

```sql
-- Connect to MySQL and run:
CREATE DATABASE myvitalz CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### Step 6: Run Migrations

```powershell
# Run all database migrations
php artisan migrate
```

**Expected Migrations:**
- `create_users_table`
- `create_password_reset_tokens_table`
- `create_failed_jobs_table`
- `create_personal_access_tokens_table`
- `create_pharmacy_networks_table`
- `create_pharmacy_inventory_table`
- `create_e_prescriptions_table`
- `create_medication_monitoring_table`
- `create_doctor_rewards_table`
- `create_clearance_sales_table`
- `create_out_of_stock_requests_table`

### Step 7: Install Frontend Dependencies

```powershell
# Install Node packages
npm install

# Build frontend assets
npm run build

# OR run development server
npm run dev
```

### Step 8: Set Permissions (Important!)

```powershell
# Ensure storage and cache directories are writable
# Windows: Right-click folders > Properties > Security > Edit permissions

# Required writable directories:
# - storage/
# - storage/app/
# - storage/framework/
# - storage/logs/
# - bootstrap/cache/
```

### Step 9: Start Development Server

```powershell
# Start Laravel development server
php artisan serve
```

**Default URL:** http://localhost:8000

---

## 🗂️ Project Structure

```
app/
├── Http/Controllers/
│   ├── loginController.php              # Web authentication
│   ├── loginControllerApi.php           # API authentication
│   ├── dashboardController.php          # Patient dashboard
│   ├── dashboardDoctorController.php    # Doctor dashboard
│   ├── dashboardHospitalController.php  # Hospital dashboard
│   ├── dashboardPharmacyController.php  # Pharmacy dashboard
│   ├── PharmacyApiController.php        # Pharmacy API endpoints
│   └── *ControllerApi.php               # API versions
├── Models/                               # Eloquent models (if any)
└── ...

database/
├── migrations/                           # 11 migration files
└── seeders/                              # Database seeders

public/
├── index.php                             # ⚠️ MISSING - MUST CREATE
├── assets/                               # Static assets
├── css/                                  # Stylesheets
├── js/                                   # JavaScript files
└── ...

resources/
├── views/                                # Blade templates
│   ├── dashboard_pharmacy.blade.php
│   └── ...
├── css/
└── js/

routes/
├── web.php                               # Web routes
└── api.php                               # API routes

storage/
├── app/
├── framework/
└── logs/

.env                                      # Environment configuration
composer.json                             # PHP dependencies
package.json                              # Node dependencies
vite.config.js                            # Vite configuration
```

---

## 🔧 Configuration Details

### Database Configuration
- **Default Connection:** MySQL
- **Host:** 127.0.0.1
- **Port:** 3306
- **Charset:** utf8mb4
- **Collation:** utf8mb4_unicode_ci

### Authentication
- **Method:** Cookie-based (no Eloquent ORM)
- **Session Driver:** File-based
- **Session Lifetime:** 120 minutes

### Frontend Build Tool
- **Vite** with Laravel plugin
- **Entry Points:**
  - `resources/css/app.css`
  - `resources/js/app.js`

---

## 🚀 Quick Start Commands

```powershell
# 1. Install dependencies
composer install
npm install

# 2. Setup environment
copy .env.example .env
php artisan key:generate

# 3. Configure database in .env file
# DB_DATABASE=myvitalz
# DB_USERNAME=root
# DB_PASSWORD=your_password

# 4. Create database and run migrations
# Create database manually in MySQL
php artisan migrate

# 5. Build frontend
npm run build

# 6. Start server
php artisan serve
```

---

## 🧪 Testing the Application

### Web Routes (Browser)
- **Login:** http://localhost:8000/
- **Patient Signup:** http://localhost:8000/signup-patient
- **Doctor Signup:** http://localhost:8000/signup-doctor
- **Hospital Signup:** http://localhost:8000/signup-hospital
- **Pharmacy Signup:** http://localhost:8000/signup-pharmacy
- **Patient Dashboard:** http://localhost:8000/dashboard
- **Doctor Dashboard:** http://localhost:8000/dashboard-doctor
- **Hospital Dashboard:** http://localhost:8000/dashboard-hospital
- **Pharmacy Dashboard:** http://localhost:8000/dashboard-pharmacy

### API Routes
- **Base URL:** http://localhost:8000/api
- **Login:** POST /api/login
- **Pharmacy Clearance:** POST /api/pharmacy/clearance
- **Out of Stock Request:** POST /api/pharmacy/osr
- **Partners:** GET /api/pharmacy/partners

---

## 🐛 Troubleshooting

### Issue: "Class not found" errors
**Solution:**
```powershell
composer dump-autoload
php artisan clear-cache
php artisan config:clear
```

### Issue: "Permission denied" errors
**Solution:** Ensure storage and bootstrap/cache directories are writable

### Issue: Database connection errors
**Solution:**
1. Verify MySQL is running
2. Check `.env` database credentials
3. Ensure database exists
4. Test connection: `php artisan migrate:status`

### Issue: Vite manifest not found
**Solution:**
```powershell
npm run build
# OR for development
npm run dev
```

### Issue: 404 on all routes
**Solution:**
1. Ensure `public/index.php` exists
2. Check web server configuration
3. Run: `php artisan route:list` to verify routes

---

## 📝 Additional Notes

### Cache Clearing Routes
The application includes utility routes for clearing cache:
- `/route-clear` - Clear route cache
- `/config-cache` - Cache configuration
- `/clear-cache` - Clear application cache
- `/view-clear` - Clear view cache

### Authentication System
- Uses direct database queries (no Eloquent)
- Cookie-based session management
- Separate controllers for web and API

### Pharmacy Features
- Network management for doctors/hospitals/patients
- E-prescription workflow (pending → delivered)
- Inventory with tiered pricing (wholesale/retail/doctor)
- Patient medication monitoring
- Doctor rewards system with QR codes

---

## 🔐 Security Recommendations

1. **Never commit `.env` file** - Contains sensitive credentials
2. **Change APP_KEY** - Run `php artisan key:generate`
3. **Use strong database passwords**
4. **Set APP_DEBUG=false** in production
5. **Configure proper file permissions** on server
6. **Enable HTTPS** in production
7. **Implement CSRF protection** (Laravel includes this by default)

---

## 📚 Resources

- **Laravel Documentation:** https://laravel.com/docs/10.x
- **Laravel Installation:** https://laravel.com/docs/10.x/installation
- **Vite Documentation:** https://vitejs.dev/
- **Bootstrap 5:** https://getbootstrap.com/docs/5.0/
- **Composer:** https://getcomposer.org/doc/

---

## ✅ Setup Checklist

- [ ] PHP 8.1+ installed
- [ ] Composer installed
- [ ] MySQL installed and running
- [ ] Node.js and npm installed (✅ Already done)
- [ ] Run `composer install`
- [ ] Run `npm install`
- [ ] Create `public/index.php` file
- [ ] Copy `.env.example` to `.env`
- [ ] Generate application key
- [ ] Configure database credentials
- [ ] Create database
- [ ] Run migrations
- [ ] Build frontend assets
- [ ] Set proper permissions
- [ ] Test application

---

## 🆘 Need Help?

If you encounter issues:
1. Check Laravel logs: `storage/logs/laravel.log`
2. Enable debug mode: Set `APP_DEBUG=true` in `.env`
3. Run diagnostics: `php artisan about`
4. Check PHP version: `php -v`
5. Verify extensions: `php -m`

---

**Last Updated:** 2025-09-30  
**Laravel Version:** 10.x  
**PHP Version:** ^8.1
