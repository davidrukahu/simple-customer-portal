# Customer Portal

A modern, secure customer portal built with Laravel 11 for managing domains, services, and invoices.

## Features

### Authentication & Security
- Role-based access control (Admin/Customer)
- Admin-only customer registration
- Password reset functionality
- Password reveal icons
- Admin-only account deletion
- CSRF protection and secure password hashing

### Admin Features
- Customer Management: CRUD operations, CSV import/export
- Domain Management: CRUD operations, CSV import/export, expiry tracking
- Service Management: CRUD operations, billing cycle management
- Invoice Management: Create invoices, PDF generation, payment tracking
- Reports: Accounts receivable, expiring domains, revenue analytics
- Settings: Business configuration, default currency, timezone settings

### Customer Features
- Dashboard: Overview of domains, services, and billing status
- Domain View: List and view domain details with expiry information
- Service View: List and view service details with billing information
- Invoice View: List invoices, view details, download PDFs
- Profile Management: Update personal information and change password

### Modern UI/UX
- Clean, minimal design with Bootstrap 5
- Responsive layouts for mobile and desktop
- Sidebar navigation for both admin and customer interfaces
- Consistent design across all pages

## Requirements

- PHP 8.1+
- MySQL 5.7+ or MariaDB
- Composer
- Web server (Apache/Nginx)
- cPanel hosting (recommended)

## Installation

### Quick Installation (Recommended)

1. **Download the application**:
   ```bash
   git clone https://github.com/davidrukahu/simple-customer-portal.git
   cd simple-customer-portal
   ```

2. **Upload to cPanel**:
   - Upload all files to your domain's `public_html` directory
   - Set proper file permissions (755 for directories, 644 for files)

3. **Create database**:
   - Go to cPanel → MySQL Databases
   - Create a new database and user
   - Add user to database with full privileges

4. **Run installation wizard**:
   - Visit `https://yourdomain.com/install`
   - Follow the step-by-step installation process
   - Configure application settings and database connection
   - Create your admin account

5. **Access your application**:
   - Visit your domain
   - Login with your admin credentials
   - Start managing customers, domains, and invoices

## Usage

### Admin Workflow
1. Login to admin panel
2. Create customers through the customer management interface
3. Add domains and services for each customer
4. Generate invoices automatically or manually
5. Track payments and manage billing
6. View reports for business insights

### Customer Workflow
1. Login to customer portal
2. View dashboard for overview of services
3. Check domains and expiry dates
4. Review invoices and payment status
5. Download PDF invoices when needed
6. Update profile information

## Security Features

- Role-based access control with middleware protection
- CSRF protection on all forms
- Password hashing with bcrypt
- Input validation and sanitization
- SQL injection protection with Eloquent ORM
- XSS protection with Blade templating
- Secure file uploads with validation

## Production Deployment

### cPanel Deployment
1. Upload files to `public_html`
2. Set permissions: Directories (755), Files (644)
3. Create database via cPanel
4. Run installation wizard at `/install`
5. Configure email settings for notifications

### Performance Optimization
- Composer optimization: `composer install --no-dev --optimize-autoloader`
- Laravel optimization: `php artisan config:cache && php artisan route:cache && php artisan view:cache`
- Database indexing on frequently queried columns

## License

This project is licensed under the MIT License.