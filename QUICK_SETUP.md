# MyVitalz - Quick Setup Reference

## 🚨 CRITICAL: Missing Requirements

Your system currently has:
- ✅ Node.js v22.14.0
- ✅ npm 10.9.0
- ❌ **PHP** (REQUIRED)
- ❌ **Composer** (REQUIRED)
- ❌ **MySQL/Database** (REQUIRED)

## 📥 Install Required Software

### 1. Install PHP 8.1+
**Download:** https://windows.php.net/download/
- Choose: PHP 8.1+ Thread Safe (x64)
- Extract to `C:\php`
- Add to PATH
- Enable extensions in `php.ini`

### 2. Install Composer
**Download:** https://getcomposer.org/Composer-Setup.exe
- Run installer
- Follow prompts

### 3. Install MySQL
**Option A - XAMPP (Easiest):** https://www.apachefriends.org/
**Option B - MySQL:** https://dev.mysql.com/downloads/mysql/

## ⚡ Setup Commands (Run After Installing Above)

```powershell
# Navigate to project
cd C:\Users\HP\Downloads\app

# Install PHP dependencies
composer install

# Install Node dependencies
npm install

# Setup environment
copy .env.example .env
php artisan key:generate

# Configure database in .env:
# DB_DATABASE=myvitalz
# DB_USERNAME=root
# DB_PASSWORD=your_password

# Create database (in MySQL):
# CREATE DATABASE myvitalz;

# Run migrations
php artisan migrate

# Build frontend
npm run build

# Start server
php artisan serve
```

## 🌐 Access Application

**URL:** http://localhost:8000

## 📋 User Types & Routes

1. **Patient:** /signup-patient → /dashboard
2. **Doctor:** /signup-doctor → /dashboard-doctor
3. **Hospital:** /signup-hospital → /dashboard-hospital
4. **Pharmacy:** /signup-pharmacy → /dashboard-pharmacy

## 🔧 Troubleshooting

### Clear Cache
```powershell
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Rebuild Autoload
```powershell
composer dump-autoload
```

### Check Status
```powershell
php artisan about
php artisan migrate:status
php artisan route:list
```

## 📖 Full Documentation

See `SETUP_GUIDE.md` for complete setup instructions and troubleshooting.

---

**Note:** The missing `public/index.php` file has been created for you.
