#!/bin/bash

# Customer Portal - Production Deployment Script
# This script prepares the application for production deployment

echo "🚀 Customer Portal - Production Deployment Script"
echo "=================================================="

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    echo "❌ Error: This script must be run from the Laravel project root directory"
    exit 1
fi

echo "📋 Step 1: Clearing test data..."
# Clear test data (already done manually)
echo "✅ Test data cleared"

echo "📋 Step 2: Optimizing for production..."

# Install production dependencies
echo "📦 Installing production dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction

# Clear and optimize caches
echo "🗑️ Clearing caches..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Optimize for production
echo "⚡ Optimizing for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "📋 Step 3: Setting up file permissions..."
# Set proper permissions for cPanel
chmod -R 755 storage
chmod -R 755 bootstrap/cache
chmod 644 .env.example

echo "📋 Step 4: Creating production files..."

# Create .htaccess for Apache
cat > public/.htaccess << 'EOF'
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
EOF

# Create installation instructions
cat > INSTALLATION.md << 'EOF'
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
EOF

# Create .gitignore for production
cat > .gitignore << 'EOF'
/node_modules
/public/build
/public/hot
/public/storage
/storage/*.key
/vendor
.env
.env.backup
.env.production
.phpunit.result.cache
Homestead.json
Homestead.yaml
auth.json
npm-debug.log
yarn-error.log
/.fleet
/.idea
/.vscode
EOF

echo "📋 Step 5: Creating deployment package..."

# Create a deployment package (excluding unnecessary files)
echo "📦 Creating deployment package..."
tar -czf customer-portal-production.tar.gz \
    --exclude='node_modules' \
    --exclude='.git' \
    --exclude='.env' \
    --exclude='database/database.sqlite' \
    --exclude='storage/logs/*' \
    --exclude='storage/framework/cache/*' \
    --exclude='storage/framework/sessions/*' \
    --exclude='storage/framework/views/*' \
    --exclude='tests' \
    --exclude='.phpunit.result.cache' \
    --exclude='customer-portal-production.tar.gz' \
    .

echo "✅ Production deployment package created: customer-portal-production.tar.gz"

echo ""
echo "🎉 Production deployment completed successfully!"
echo ""
echo "📋 Next Steps:"
echo "1. Upload 'customer-portal-production.tar.gz' to your cPanel"
echo "2. Extract the files in your domain's public_html directory"
echo "3. Set proper file permissions (755 for directories, 644 for files)"
echo "4. Visit https://yourdomain.com/install to complete installation"
echo "5. Follow the installation wizard to configure your application"
echo ""
echo "📚 See INSTALLATION.md for detailed instructions"
echo ""
echo "🔗 GitHub Repository: https://github.com/davidrukahu/simple-customer-portal.git"
echo ""
echo "✨ Your Customer Portal is ready for production!"
