<?php
// OneChamber Portal - Simple cPanel Installer
// This single file handles everything automatically

echo "<!DOCTYPE html>
<html>
<head>
    <title>OneChamber Portal - Simple Installer</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { color: #333; margin: 0; }
        .step { margin: 20px 0; padding: 15px; background: #f8f9fa; border-radius: 5px; }
        .step h3 { margin-top: 0; color: #495057; }
        .btn { display: inline-block; padding: 12px 24px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; margin: 10px 5px; }
        .btn:hover { background: #0056b3; }
        .success { background: #d4edda; border: 1px solid #c3e6cb; color: #155724; }
        .error { background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; }
        .form-group { margin: 15px 0; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        .form-group small { color: #666; }
    </style>
</head>
<body>
<div class='container'>";

echo "<div class='header'>
    <h1>🚀 OneChamber Portal - Simple Installer</h1>
    <p>This installer will set up everything automatically for cPanel hosting</p>
</div>";

// Step 1: Check requirements
echo "<div class='step'>
    <h3>📋 Step 1: System Requirements</h3>";

$php_version = PHP_VERSION;
$php_ok = version_compare($php_version, '8.1.0', '>=');
echo "<p><strong>PHP Version:</strong> $php_version " . ($php_ok ? "✅" : "❌") . "</p>";

$mod_rewrite = function_exists('apache_get_modules') ? in_array('mod_rewrite', apache_get_modules()) : true;
echo "<p><strong>mod_rewrite:</strong> " . ($mod_rewrite ? "✅ Available" : "❌ Not Available") . "</p>";

$writable = is_writable('.');
echo "<p><strong>Directory Writable:</strong> " . ($writable ? "✅ Yes" : "❌ No") . "</p>";

if (!$php_ok || !$writable) {
    echo "<div class='error'>
        <p><strong>❌ Requirements not met!</strong></p>
        <p>Please contact your hosting provider to:</p>
        <ul>
            <li>Upgrade PHP to 8.1+ (if needed)</li>
            <li>Enable mod_rewrite (if needed)</li>
            <li>Set proper file permissions</li>
        </ul>
    </div>";
    echo "</div></div></body></html>";
    exit;
}

echo "<div class='success'><p>✅ All requirements met!</p></div>";
echo "</div>";

// Step 2: Database configuration
if (!isset($_POST['db_host'])) {
    echo "<div class='step'>
        <h3>🗄️ Step 2: Database Configuration</h3>
        <form method='POST'>
            <div class='form-group'>
                <label>Database Host:</label>
                <input type='text' name='db_host' value='localhost' required>
                <small>Usually 'localhost' for cPanel</small>
            </div>
            <div class='form-group'>
                <label>Database Name:</label>
                <input type='text' name='db_name' required>
                <small>Your cPanel database name</small>
            </div>
            <div class='form-group'>
                <label>Database Username:</label>
                <input type='text' name='db_user' required>
                <small>Your cPanel database username</small>
            </div>
            <div class='form-group'>
                <label>Database Password:</label>
                <input type='password' name='db_pass' required>
                <small>Your cPanel database password</small>
            </div>
            <div class='form-group'>
                <label>Admin Email:</label>
                <input type='email' name='admin_email' required>
                <small>Email for the admin account</small>
            </div>
            <div class='form-group'>
                <label>Admin Password:</label>
                <input type='password' name='admin_password' required>
                <small>Password for the admin account</small>
            </div>
            <button type='submit' class='btn'>🚀 Install Now</button>
        </form>
    </div>";
} else {
    // Step 3: Installation
    echo "<div class='step'>
        <h3>⚙️ Step 3: Installing...</h3>";
    
    try {
        // Create .env file
        $env_content = "APP_NAME=\"Customer Portal\"
APP_ENV=production
APP_KEY=base64:+j8Z/SFIK/aQtRfcztQP26FacFPadWeUDXYfGyEFnkU=
APP_DEBUG=false
APP_TIMEZONE=UTC
APP_URL=http://portal.onechamber.co.ke

DB_CONNECTION=mysql
DB_HOST={$_POST['db_host']}
DB_PORT=3306
DB_DATABASE={$_POST['db_name']}
DB_USERNAME={$_POST['db_user']}
DB_PASSWORD={$_POST['db_pass']}

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

CACHE_STORE=database
CACHE_PREFIX=

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

MAIL_MAILER=log
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=\"hello@example.com\"
MAIL_FROM_NAME=\"\${APP_NAME}\"";

        file_put_contents('.env', $env_content);
        echo "<p>✅ Created .env file</p>";
        
        // Create storage directories
        $dirs = [
            'storage/framework/views',
            'storage/framework/cache',
            'storage/framework/sessions',
            'storage/logs',
            'bootstrap/cache'
        ];
        
        foreach ($dirs as $dir) {
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
                echo "<p>✅ Created directory: $dir</p>";
            }
        }
        
        // Set permissions
        foreach ($dirs as $dir) {
            chmod($dir, 0755);
        }
        
        // Create .installed file
        file_put_contents('.installed', date('Y-m-d H:i:s'));
        echo "<p>✅ Marked as installed</p>";
        
        // Test database connection
        $pdo = new PDO("mysql:host={$_POST['db_host']};dbname={$_POST['db_name']}", $_POST['db_user'], $_POST['db_pass']);
        echo "<p>✅ Database connection successful</p>";
        
        // Create admin user (simplified)
        $admin_password = password_hash($_POST['admin_password'], PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (name, email, password, email_verified_at, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW(), NOW())";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['Admin', $_POST['admin_email'], $admin_password]);
        echo "<p>✅ Created admin user</p>";
        
        echo "<div class='success'>
            <h3>🎉 Installation Complete!</h3>
            <p><strong>Admin Login:</strong></p>
            <p>Email: {$_POST['admin_email']}</p>
            <p>Password: [The password you entered]</p>
            <p><a href='/' class='btn'>🚀 Go to Application</a></p>
        </div>";
        
    } catch (Exception $e) {
        echo "<div class='error'>
            <h3>❌ Installation Failed</h3>
            <p>Error: " . htmlspecialchars($e->getMessage()) . "</p>
            <p>Please check your database credentials and try again.</p>
        </div>";
    }
    
    echo "</div>";
}

echo "</div></body></html>";
?>
