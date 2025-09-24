<?php
// Fix storage permissions script
// Run this after uploading to cPanel

echo "🔧 Fixing Laravel Storage Permissions\n";
echo "====================================\n\n";

// Create necessary directories
$directories = [
    'storage/framework/views',
    'storage/framework/cache', 
    'storage/framework/sessions',
    'storage/logs',
    'bootstrap/cache'
];

foreach ($directories as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
        echo "✅ Created directory: $dir\n";
    } else {
        echo "📁 Directory exists: $dir\n";
    }
    
    // Set permissions
    chmod($dir, 0755);
    echo "🔒 Set permissions (755): $dir\n";
}

// Create .gitkeep files to ensure directories are tracked
$gitkeepFiles = [
    'storage/framework/views/.gitkeep',
    'storage/framework/cache/.gitkeep',
    'storage/framework/sessions/.gitkeep', 
    'storage/logs/.gitkeep',
    'bootstrap/cache/.gitkeep'
];

foreach ($gitkeepFiles as $file) {
    if (!file_exists($file)) {
        file_put_contents($file, '');
        echo "📄 Created: $file\n";
    }
}

echo "\n✅ Storage permissions fixed!\n";
echo "You can now delete this file.\n";
