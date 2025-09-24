<?php
// Simple installation redirect
// This file should be placed in a folder called "install" in your public_html directory

echo "<!DOCTYPE html>
<html>
<head>
    <title>Customer Portal - Installation</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f5f5f5; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { color: #333; margin: 0; }
        .step { margin: 20px 0; padding: 15px; background: #f8f9fa; border-radius: 5px; }
        .step h3 { margin-top: 0; color: #495057; }
        .btn { display: inline-block; padding: 12px 24px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; margin: 10px 5px; }
        .btn:hover { background: #0056b3; }
        .warning { background: #fff3cd; border: 1px solid #ffeaa7; color: #856404; padding: 15px; border-radius: 5px; margin: 20px 0; }
        .error { background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 15px; border-radius: 5px; margin: 20px 0; }
        .success { background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 15px; border-radius: 5px; margin: 20px 0; }
    </style>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <h1>🚀 Customer Portal Installation</h1>
            <p>Welcome to the Customer Portal installation process</p>
        </div>
        
        <div class='warning'>
            <strong>⚠️ Important:</strong> Make sure you have uploaded all the application files to your public_html directory before proceeding.
        </div>
        
        <div class='step'>
            <h3>Step 1: Debug Information</h3>
            <p>First, let's check if everything is working correctly:</p>
            <a href='../debug.php' class='btn'>🔍 Run Debug Check</a>
        </div>
        
        <div class='step'>
            <h3>Step 2: Test PHP</h3>
            <p>Test if PHP is working correctly:</p>
            <a href='../test.php' class='btn'>Test PHP</a>
        </div>
        
        <div class='step'>
            <h3>Step 3: Try Installation</h3>
            <p>If debug check passes, try the installation:</p>
            <a href='../install' class='btn'>Start Installation</a>
        </div>
        
        <div class='step'>
            <h3>Step 4: Alternative .htaccess</h3>
            <p>If installation still fails, try the simple .htaccess:</p>
            <div class='warning'>
                <strong>Instructions:</strong><br>
                1. Download <a href='../.htaccess.simple' target='_blank'>.htaccess.simple</a><br>
                2. Rename your current .htaccess to .htaccess.backup<br>
                3. Rename .htaccess.simple to .htaccess<br>
                4. Try installation again
            </div>
        </div>
        
        <div class='step'>
            <h3>Step 5: After Installation</h3>
            <p>Once installation is complete, you can delete this install folder for security.</p>
        </div>
        
        <div style='text-align: center; margin-top: 30px; color: #666;'>
            <p>Customer Portal v1.0 | <a href='https://github.com/davidrukahu/simple-customer-portal'>GitHub</a></p>
        </div>
    </div>
</body>
</html>";
?>
