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
            <h3>Step 1: Verify Files</h3>
            <p>Ensure all Laravel files are uploaded to your public_html directory:</p>
            <ul>
                <li>app/ directory</li>
                <li>bootstrap/ directory</li>
                <li>config/ directory</li>
                <li>database/ directory</li>
                <li>public/ directory</li>
                <li>resources/ directory</li>
                <li>routes/ directory</li>
                <li>storage/ directory</li>
                <li>vendor/ directory</li>
                <li>artisan file</li>
                <li>composer.json file</li>
                <li>.htaccess file (in root)</li>
            </ul>
        </div>
        
        <div class='step'>
            <h3>Step 2: Test PHP</h3>
            <p>First, test if PHP is working correctly:</p>
            <a href='../test.php' class='btn'>Test PHP</a>
        </div>
        
        <div class='step'>
            <h3>Step 3: Run Installation</h3>
            <p>If PHP test is successful, proceed with the installation:</p>
            <a href='../install' class='btn'>Start Installation</a>
        </div>
        
        <div class='step'>
            <h3>Step 4: After Installation</h3>
            <p>Once installation is complete, you can delete this install folder for security.</p>
        </div>
        
        <div style='text-align: center; margin-top: 30px; color: #666;'>
            <p>Customer Portal v1.0 | <a href='https://github.com/davidrukahu/simple-customer-portal'>GitHub</a></p>
        </div>
    </div>
</body>
</html>";
?>
