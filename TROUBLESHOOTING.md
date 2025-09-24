# Production Deployment Troubleshooting

## Common Issues and Solutions

### 1. 404 Error on /install Route

**Problem**: Getting "Not Found" error when accessing `/install`

**Possible Causes & Solutions**:

#### A. Missing .htaccess File
- **Check**: Ensure `public/.htaccess` exists
- **Solution**: Upload the `.htaccess` file to your `public_html` directory
- **Content**: Should contain Apache rewrite rules

#### B. Document Root Configuration
- **Check**: Files should be in `public_html` directory, not a subdirectory
- **Solution**: Move all files to the root of `public_html`
- **Structure**: 
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
  └── .env
  ```

#### C. Apache Mod_Rewrite Not Enabled
- **Check**: Contact your hosting provider
- **Solution**: Enable mod_rewrite module
- **Alternative**: Use `.htaccess` with rewrite rules

#### D. PHP Version Issues
- **Check**: Ensure PHP 8.1+ is enabled
- **Solution**: Change PHP version in cPanel
- **Location**: cPanel → Software → Select PHP Version

#### E. File Permissions
- **Check**: Set correct permissions
- **Solution**: 
  - Directories: 755
  - Files: 644
  - `storage/` and `bootstrap/cache/`: 755

### 2. Installation Wizard Not Loading

**Problem**: Installation page loads but wizard doesn't work

**Solutions**:
- Check browser console for JavaScript errors
- Ensure all CSS/JS files are loading
- Verify database connection settings

### 3. Database Connection Issues

**Problem**: Installation fails at database step

**Solutions**:
- Verify database credentials in cPanel
- Check database user has full privileges
- Ensure database exists and is accessible

### 4. File Upload Issues

**Problem**: Files not uploading correctly

**Solutions**:
- Use cPanel File Manager instead of FTP
- Check file size limits
- Ensure all files uploaded completely

## Quick Fix Checklist

1. ✅ **Files in correct location**: All files in `public_html` root
2. ✅ **PHP version**: 8.1 or higher
3. ✅ **File permissions**: 755 for directories, 644 for files
4. ✅ **Database created**: MySQL database and user
5. ✅ **mod_rewrite enabled**: Apache module active
6. ✅ **No .installed file**: Should not exist before installation

## Manual Installation (If Wizard Fails)

If the installation wizard continues to fail:

1. **Create .env file**:
   ```bash
   cp .env.example .env
   ```

2. **Edit .env with your settings**:
   ```env
   APP_NAME="Customer Portal"
   APP_ENV=production
   APP_KEY=base64:your-generated-key
   APP_URL=https://portal.onechamber.co.ke
   
   DB_CONNECTION=mysql
   DB_HOST=localhost
   DB_PORT=3306
   DB_DATABASE=your_database_name
   DB_USERNAME=your_database_user
   DB_PASSWORD=your_database_password
   ```

3. **Generate application key**:
   ```bash
   php artisan key:generate
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
       'email' => 'admin@onechamber.co.ke',
       'password' => Hash::make('your_password'),
       'role' => 'admin',
       'email_verified_at' => now(),
   ]);
   ```

6. **Create installed flag**:
   ```bash
   touch .installed
   ```

## Contact Support

If issues persist, provide:
- Error messages from browser console
- PHP version and server configuration
- File structure screenshot
- Database connection details
