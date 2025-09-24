# Quick Deployment Guide for portal.onechamber.co.ke

## 🚀 Updated Production Package

**File**: `customer-portal-production.tar.gz` (8.2MB)
**Updated**: Includes troubleshooting files and fixes

## 📋 Deployment Steps

### 1. Upload & Extract
1. Download `customer-portal-production.tar.gz`
2. Upload to cPanel File Manager
3. Extract in `public_html` directory
4. Ensure ALL files are in `public_html` root (not in subdirectory)

### 2. Set Permissions
Set these permissions via cPanel File Manager:
- **Directories**: 755
- **Files**: 644
- **storage/**: 755
- **bootstrap/cache/**: 755

### 3. Move .htaccess File
**IMPORTANT**: Move the `.htaccess` file from `public/.htaccess` to `public_html/.htaccess`
This is crucial for Laravel routing to work!

### 4. Test Installation
Before running installation, test these URLs:

#### Test PHP is working:
- Visit: `http://portal.onechamber.co.ke/test.php`
- Should show PHP version and file status

#### Test application routes:
- Visit: `http://portal.onechamber.co.ke/test`
- Should return JSON with application status

#### Test installation wizard:
- Visit: `http://portal.onechamber.co.ke/install`
- Should show installation wizard

### 5. Create Database
1. Go to cPanel → MySQL Databases
2. Create database: `portal_onechamber` (or similar)
3. Create user and add to database with ALL PRIVILEGES
4. Note down: database name, username, password

### 6. Run Installation
1. Visit: `http://portal.onechamber.co.ke/install`
2. Fill in the installation form:
   - **App Name**: Customer Portal
   - **App URL**: https://portal.onechamber.co.ke
   - **Database details**: Use your MySQL credentials
   - **Admin account**: Create your admin login
3. Click "Install Application"

### 7. Access Your Portal
1. Visit: `https://portal.onechamber.co.ke`
2. Login with your admin credentials
3. Start managing customers and services!

## 🔧 Troubleshooting

If you still get 404 errors:

### Check File Structure
Ensure this structure in `public_html`:
```
public_html/
├── app/
├── bootstrap/
├── config/
├── database/
├── public/
├── resources/
├── routes/
├── storage/
├── vendor/
├── artisan
├── composer.json
├── test.php
└── .htaccess (moved from public/.htaccess)
```

### Check PHP Version
- Go to cPanel → Software → Select PHP Version
- Ensure PHP 8.1 or higher is selected

### Check mod_rewrite
- Contact your hosting provider if mod_rewrite is not enabled
- This is required for Laravel routing

## 📞 Support

If issues persist:
1. Check `TROUBLESHOOTING.md` for detailed solutions
2. Test with `test.php` and `/test` routes first
3. Verify file structure and permissions
4. Contact hosting provider for server configuration issues

---

**Ready to deploy!** Upload the new package and follow these steps. 🚀
