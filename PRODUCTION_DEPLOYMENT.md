# Production Deployment Guide

## Pre-Deployment Checklist

### 1. Files Cleaned Up ✅
- Removed duplicate `app/User.php` (kept `app/Models/User.php`)
- Removed test PDF file `Invoice_197_2025-10-03_250924_150818.pdf`
- Removed unused command `app/Console/Commands/UpdateCustomerPassword.php`
- Removed unused view `resources/views/dashboard.blade.php`
- Removed unused migration `database/migrations/2024_01_01_000005_create_customer_services_table.php`
- Removed sample data seeder `database/seeders/SampleDataSeeder.php`
- **Removed register page** - Admin must create customer accounts
- **Fixed password reset controller** - Added missing `create` method
- **Created missing auth controllers** - Added `ConfirmablePasswordController`
- **Fixed profile page layout selection** - Customers now see customer nav correctly

### 2. Environment Configuration
Create a `.env` file with production settings:

```env
APP_NAME="Customer Portal"
APP_ENV=production
APP_KEY=base64:your-generated-key-here
APP_DEBUG=false
APP_TIMEZONE=Africa/Nairobi
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=customer_portal
DB_USERNAME=your_db_username
DB_PASSWORD=your_db_password

SESSION_DRIVER=database
SESSION_LIFETIME=120

MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-smtp-username
MAIL_PASSWORD=your-smtp-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@your-domain.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### 3. Database Setup
1. Create MySQL database: `customer_portal`
2. Run migrations: `php artisan migrate`
3. Seed admin user: `php artisan db:seed --class=AdminUserSeeder`

### 4. File Permissions (cPanel)
```bash
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
chmod 644 .env
```

### 5. Composer Dependencies
```bash
composer install --optimize-autoloader --no-dev
```

### 6. Asset Compilation
```bash
npm install
npm run build
```

### 7. Cache Optimization
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Features Implemented ✅

### Authentication & Security
- ✅ Password reveal icons on all password fields
- ✅ Role-based access control (Admin/Customer)
- ✅ CSRF protection
- ✅ Secure password hashing
- ✅ **Admin-only customer registration** (no public registration)
- ✅ **Password reset functionality** (forgot password)
- ✅ **Modern profile page UI/UX** (extends appropriate layout)
- ✅ **Admin-only account deletion** (customers cannot delete accounts)

### Admin Features
- ✅ Customer management (CRUD, CSV import/export)
- ✅ Domain management (CRUD, CSV import/export)
- ✅ Service management (CRUD)
- ✅ Invoice management (CRUD, PDF generation)
- ✅ Payment tracking
- ✅ Reports dashboard
- ✅ Settings management

### Customer Features
- ✅ Dashboard with overview cards
- ✅ Domain management (view-only)
- ✅ Service management (view-only)
- ✅ Invoice management (view-only, PDF download)

### UI/UX
- ✅ Modern, clean design
- ✅ Responsive layout
- ✅ Bootstrap 5 styling
- ✅ Consistent navigation
- ✅ Professional PDF invoices

## Production Notes

### Security
- Ensure `APP_DEBUG=false` in production
- Use strong database passwords
- Configure proper SMTP settings
- Set up SSL certificate

### Performance
- Enable Laravel caching
- Optimize database indexes
- Use CDN for static assets if needed

### Monitoring
- Monitor Laravel logs in `storage/logs/`
- Set up error tracking (Sentry, Bugsnag, etc.)
- Monitor database performance

## Support
For any issues, check:
1. Laravel logs: `storage/logs/laravel.log`
2. Web server error logs
3. Database connection settings
4. File permissions
