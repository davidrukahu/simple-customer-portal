# OneChamber Customer Portal

A secure customer portal for managing domains and services, built with Laravel 11.

## Features

### Customer Portal
- **Dashboard**: Overview of domains, invoices, and account status
- **Domain Management**: View-only access to domain portfolio
- **Invoice Management**: View and download invoices
- **Service Management**: View additional services

### Admin Area
- **Customer Management**: Full CRUD operations for customer accounts
- **Domain Management**: Add, edit, and track domains
- **Service Management**: Manage additional services
- **Invoice System**: Auto-generate invoices and track payments
- **Reports**: Financial and domain reports
- **Settings**: Business configuration

### Automated Features
- **Invoice Generation**: Automatic renewal invoices 30 days before expiry
- **Status Updates**: Automatic domain status management
- **Overdue Tracking**: Automatic calculation of overdue invoices

## Requirements

- PHP 8.1+
- MySQL 5.7+ or MariaDB
- Composer
- Node.js & NPM (for frontend assets)

## Installation

1. **Clone or download the project**
   ```bash
   cd /path/to/your/project
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install frontend dependencies**
   ```bash
   npm install
   ```

4. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure your .env file**
   ```env
   APP_NAME="OneChamber Customer Portal"
   APP_URL=http://localhost
   APP_TIMEZONE="Africa/Nairobi"
   
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=onechamber_portal
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   
   # Business Settings
   BUSINESS_NAME="OneChamber LTD"
   BUSINESS_ADDRESS="Worldwide Printing Center, 4th Floor, Mushebi Road, Parklands"
   BUSINESS_CITY="Nairobi"
   BUSINESS_COUNTRY="Kenya"
   BUSINESS_EMAIL="info@onechamber.com"
   
   # Currency and Localization
   DEFAULT_CURRENCY=KES
   CURRENCY_SYMBOL=KSh
   
   # Invoice Settings
   INVOICE_PREFIX=INV
   INVOICE_START_NUMBER=1
   DEFAULT_PAYMENT_TERMS=30
   
   # Domain Settings
   DOMAIN_RENEWAL_REMINDER_DAYS=30
   DOMAIN_EXPIRY_GRACE_DAYS=30
   DOMAIN_REDEMPTION_DAYS=30
   ```

6. **Run database migrations**
   ```bash
   php artisan migrate
   ```

7. **Create default settings**
   ```bash
   php artisan db:seed --class=SettingsSeeder
   ```

8. **Build frontend assets**
   ```bash
   npm run build
   ```

9. **Create your first admin user**
   ```bash
   php artisan tinker
   ```
   ```php
   $user = new App\Models\User();
   $user->name = 'Admin User';
   $user->email = 'admin@onechamber.com';
   $user->password = Hash::make('password');
   $user->role = 'admin';
   $user->save();
   ```

## Usage

### Admin Access
- Login with admin credentials
- Access admin dashboard at `/admin/dashboard`
- Manage customers, domains, services, and invoices

### Customer Access
- Admin creates customer accounts
- Customers login and access their portal at `/customer/dashboard`
- View-only access to their domains and invoices

## Business Configuration

The system is pre-configured for OneChamber LTD with:
- **Business Name**: OneChamber LTD
- **Address**: Worldwide Printing Center, 4th Floor, Mushebi Road, Parklands, Nairobi, Kenya
- **Currency**: KES (Kenyan Shillings)
- **Timezone**: Africa/Nairobi (GMT+3)
- **Invoice Format**: INV-{YYYY}-{sequence} (e.g., INV-2025-0001)

## Key Features

### Domain Management
- Track domain registration and expiry dates
- Automatic status updates (active → expired → grace → redemption)
- Renewal reminders and auto-invoicing
- Registrar information tracking

### Invoice System
- Automatic invoice generation 30 days before domain expiry
- Manual invoice creation
- PDF invoice generation
- Payment tracking with simple "Mark as Paid" functionality
- Multiple payment methods (Wire, M-Pesa, Cash, Cheque, Other)

### Customer Portal
- Professional dashboard with account overview
- Domain portfolio view with expiry tracking
- Invoice history with PDF downloads
- Payment instructions display

### Admin Features
- Comprehensive customer management
- Bulk operations and CSV import/export
- Financial reporting and analytics
- Settings management for business configuration

## Security Features

- Role-based access control (Admin vs Customer)
- CSRF protection on all forms
- Password hashing and secure authentication
- Input validation and sanitization
- Rate limiting on authentication routes

## Deployment

### cPanel Deployment
1. Upload files to your cPanel hosting
2. Create MySQL database and user
3. Update .env file with production settings
4. Run migrations: `php artisan migrate`
5. Set document root to `/public` directory
6. Configure SSL certificate

### Production Considerations
- Set `APP_DEBUG=false` in production
- Use strong database passwords
- Configure proper mail settings for password resets
- Set up regular database backups
- Monitor application logs

## Support

For support and questions, contact OneChamber LTD at info@onechamber.com.

## License

This project is proprietary software for OneChamber LTD.
