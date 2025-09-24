# Customer Portal - Installation Guide

## Quick Installation for cPanel

### Step 1: Upload Files
1. Download the application files
2. Upload to your cPanel File Manager or extract in your domain's public_html directory
3. Ensure all files are uploaded correctly

### Step 2: Set Permissions
Set the following permissions via cPanel File Manager:
- `storage/` directory: 755 (or 777 if needed)
- `bootstrap/cache/` directory: 755 (or 777 if needed)
- `public/` directory: 755

### Step 3: Create Database
1. Go to cPanel → MySQL Databases
2. Create a new database
3. Create a database user
4. Add the user to the database with full privileges
5. Note down the database name, username, and password

### Step 4: Run Installation
1. Visit your domain: `https://yourdomain.com/install`
2. Follow the installation wizard:
   - Enter application details
   - Configure database connection
   - Create admin account
3. Complete the installation

### Step 5: Access Application
1. Visit your domain
2. Login with the admin account you created
3. Start managing customers, domains, and invoices

## Manual Installation (Advanced)

If you prefer manual installation:

1. Copy `.env.example` to `.env`
2. Configure database settings in `.env`
3. Run: `php artisan key:generate`
4. Run: `php artisan migrate`
5. Create admin user manually in database

## Support

For support, contact: admin@onechamber.co.ke
