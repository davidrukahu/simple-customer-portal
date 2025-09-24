# Customer Portal

A modern, secure customer portal built with Laravel 11 for managing domains, services, and invoices. Perfect for web hosting companies, domain registrars, and service providers.

## 🚀 Features

### 🔐 Authentication & Security
- **Role-based access control** (Admin/Customer)
- **Admin-only customer registration** (no public registration)
- **Password reset functionality** with email verification
- **Password reveal icons** on all password fields
- **Admin-only account deletion** (customers cannot delete accounts)
- **CSRF protection** and secure password hashing

### 👨‍💼 Admin Features
- **Customer Management**: CRUD operations, CSV import/export, status management
- **Domain Management**: CRUD operations, CSV import/export, expiry tracking
- **Service Management**: CRUD operations, billing cycle management
- **Invoice Management**: Create invoices, PDF generation, payment tracking
- **Reports**: Accounts receivable, expiring domains, revenue analytics
- **Settings**: Business configuration, default currency, timezone settings

### 👤 Customer Features
- **Dashboard**: Overview of domains, services, and billing status
- **Domain View**: List and view domain details with expiry information
- **Service View**: List and view service details with billing information
- **Invoice View**: List invoices, view details, download PDFs
- **Profile Management**: Update personal information and change password

### 🎨 Modern UI/UX
- **Clean, minimal design** with Bootstrap 5
- **Responsive layouts** for mobile and desktop
- **Sidebar navigation** for both admin and customer interfaces
- **Consistent design** across all pages
- **Modern color palette** with proper contrast and accessibility

## 📋 Requirements

- **PHP 8.1+**
- **MySQL 5.7+** or **MariaDB**
- **Composer**
- **Web server** (Apache/Nginx)
- **cPanel hosting** (recommended)

## 🛠️ Installation

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

### Manual Installation (Advanced)

1. **Install dependencies**:
   ```bash
   composer install --no-dev --optimize-autoloader
   ```

2. **Configure environment**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. **Configure database** in `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_database
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

4. **Run migrations**:
   ```bash
   php artisan migrate
   ```

5. **Create admin user**:
   ```bash
   php artisan tinker
   ```
   ```php
   App\Models\User::create([
       'name' => 'Administrator',
       'email' => 'admin@yourdomain.com',
       'password' => Hash::make('your_password'),
       'role' => 'admin',
       'email_verified_at' => now(),
   ]);
   ```

## 📁 Project Structure

```
customer-portal/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/          # Admin controllers
│   │   ├── Customer/       # Customer controllers
│   │   ├── Auth/           # Authentication controllers
│   │   └── InstallController.php
│   ├── Http/Middleware/    # Custom middleware
│   └── Models/             # Eloquent models
├── database/
│   ├── migrations/         # Database migrations
│   └── seeders/           # Database seeders
├── resources/
│   ├── views/
│   │   ├── admin/         # Admin views
│   │   ├── customer/      # Customer views
│   │   ├── auth/          # Authentication views
│   │   ├── install/       # Installation wizard
│   │   └── layouts/       # Layout templates
│   └── css/               # Stylesheets
├── routes/
│   ├── web.php            # Web routes
│   └── auth.php           # Authentication routes
└── public/                # Public assets
```

## 🔧 Configuration

### Environment Variables

Key environment variables in `.env`:

```env
APP_NAME="Customer Portal"
APP_ENV=production
APP_URL=https://yourdomain.com
APP_TIMEZONE=Africa/Nairobi

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

MAIL_MAILER=smtp
MAIL_HOST=your_smtp_host
MAIL_PORT=587
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_password
MAIL_FROM_ADDRESS="noreply@yourdomain.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### Business Settings

Configure your business information through the admin panel:
- Business name and address
- Default currency and timezone
- Invoice numbering scheme
- Billing instructions
- Contact information

## 🎯 Usage

### Admin Workflow

1. **Login** to admin panel
2. **Create customers** through the customer management interface
3. **Add domains** and services for each customer
4. **Generate invoices** automatically or manually
5. **Track payments** and manage billing
6. **View reports** for business insights

### Customer Workflow

1. **Login** to customer portal
2. **View dashboard** for overview of services
3. **Check domains** and expiry dates
4. **Review invoices** and payment status
5. **Download PDF invoices** when needed
6. **Update profile** information

## 🔒 Security Features

- **Role-based access control** with middleware protection
- **CSRF protection** on all forms
- **Password hashing** with bcrypt
- **Input validation** and sanitization
- **SQL injection protection** with Eloquent ORM
- **XSS protection** with Blade templating
- **Secure file uploads** with validation

## 📊 Database Schema

### Core Tables
- `users` - User accounts (admin/customer)
- `customers` - Customer information
- `domains` - Domain registrations
- `services` - Service subscriptions
- `invoices` - Invoice records
- `invoice_items` - Invoice line items
- `payments` - Payment records
- `settings` - Application settings

### Key Relationships
- Customers belong to users
- Domains belong to customers
- Services belong to customers
- Invoices belong to customers
- Invoice items belong to invoices
- Payments belong to invoices

## 🚀 Production Deployment

### cPanel Deployment

1. **Upload files** to `public_html`
2. **Set permissions**:
   - Directories: 755
   - Files: 644
   - `storage/` and `bootstrap/cache/`: 755
3. **Create database** via cPanel
4. **Run installation wizard** at `/install`
5. **Configure email** settings for notifications

### Performance Optimization

- **Composer optimization**: `composer install --no-dev --optimize-autoloader`
- **Laravel optimization**: `php artisan config:cache && php artisan route:cache && php artisan view:cache`
- **Database indexing** on frequently queried columns
- **CDN integration** for static assets

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch: `git checkout -b feature-name`
3. Commit changes: `git commit -am 'Add feature'`
4. Push to branch: `git push origin feature-name`
5. Submit a pull request

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🆘 Support

For support and questions:
- **Email**: admin@onechamber.co.ke
- **Issues**: [GitHub Issues](https://github.com/davidrukahu/simple-customer-portal/issues)
- **Documentation**: See `INSTALLATION.md` for detailed setup instructions

## 🎉 Acknowledgments

- Built with [Laravel 11](https://laravel.com/)
- UI components from [Bootstrap 5](https://getbootstrap.com/)
- PDF generation with [DomPDF](https://github.com/barryvdh/laravel-dompdf)
- Icons from [Bootstrap Icons](https://icons.getbootstrap.com/)

---

**Customer Portal** - Modern, secure, and easy to deploy! 🚀