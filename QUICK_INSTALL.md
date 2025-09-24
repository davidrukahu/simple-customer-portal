# Quick Installation Guide

## 🚀 One-Click cPanel Installation

### Step 1: Download & Upload
1. Download `customer-portal-production.tar.gz` from the [releases page](https://github.com/davidrukahu/simple-customer-portal/releases)
2. Upload to your cPanel File Manager
3. Extract in your domain's `public_html` directory

### Step 2: Set Permissions
In cPanel File Manager, set these permissions:
- `storage/` directory: **755**
- `bootstrap/cache/` directory: **755**
- All other directories: **755**
- All files: **644**

### Step 3: Create Database
1. Go to **cPanel → MySQL Databases**
2. Create a new database (e.g., `yourdomain_portal`)
3. Create a database user
4. Add user to database with **ALL PRIVILEGES**
5. Note down: database name, username, password

### Step 4: Install Application
1. Visit: `https://yourdomain.com/install`
2. Follow the installation wizard:
   - **App Name**: Customer Portal
   - **App URL**: https://yourdomain.com
   - **Database**: Enter your MySQL details
   - **Admin Account**: Create your admin login
3. Click **Install Application**

### Step 5: Access Your Portal
1. Visit: `https://yourdomain.com`
2. Login with your admin credentials
3. Start adding customers, domains, and services!

## 🎯 What You Get

- **Admin Panel**: Manage customers, domains, services, invoices
- **Customer Portal**: Customers can view their services and invoices
- **PDF Invoices**: Professional invoice generation
- **CSV Import/Export**: Bulk data management
- **Reports**: Business analytics and insights
- **Modern UI**: Clean, responsive design

## 🔧 Configuration

After installation, configure:
- **Business Settings**: Company name, address, currency
- **Email Settings**: SMTP configuration for notifications
- **Invoice Settings**: Numbering scheme, billing instructions

## 🆘 Need Help?

- **Documentation**: See main [README.md](README.md)
- **Issues**: [GitHub Issues](https://github.com/davidrukahu/simple-customer-portal/issues)
- **Support**: admin@onechamber.co.ke

---

**Ready to deploy?** Download the package and follow the steps above! 🚀
