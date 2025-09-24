<?php
// Debug file for shared hosting issues
// Place this in your public_html root and visit: yourdomain.com/debug.php

echo "<h1>🔍 Shared Hosting Debug Information</h1>";
echo "<hr>";

echo "<h2>📋 Server Information</h2>";
echo "<strong>PHP Version:</strong> " . PHP_VERSION . "<br>";
echo "<strong>Server Software:</strong> " . $_SERVER['SERVER_SOFTWARE'] . "<br>";
echo "<strong>Document Root:</strong> " . $_SERVER['DOCUMENT_ROOT'] . "<br>";
echo "<strong>Script Filename:</strong> " . $_SERVER['SCRIPT_FILENAME'] . "<br>";
echo "<strong>Request URI:</strong> " . $_SERVER['REQUEST_URI'] . "<br>";

echo "<h2>🔧 Apache Modules</h2>";
if (function_exists('apache_get_modules')) {
    $modules = apache_get_modules();
    echo "<strong>mod_rewrite:</strong> " . (in_array('mod_rewrite', $modules) ? '✅ Available' : '❌ Not Available') . "<br>";
    echo "<strong>mod_headers:</strong> " . (in_array('mod_headers', $modules) ? '✅ Available' : '❌ Not Available') . "<br>";
} else {
    echo "❌ Cannot check Apache modules (function not available)<br>";
}

echo "<h2>📁 File System Check</h2>";
$files_to_check = [
    'artisan' => 'Laravel artisan file',
    'composer.json' => 'Composer configuration',
    'app' => 'Laravel app directory',
    'public' => 'Laravel public directory',
    '.htaccess' => 'Apache configuration',
    'index.php' => 'Laravel entry point'
];

foreach ($files_to_check as $file => $description) {
    $exists = file_exists($file);
    $status = $exists ? '✅' : '❌';
    echo "<strong>$description ($file):</strong> $status " . ($exists ? 'Found' : 'Missing') . "<br>";
}

echo "<h2>🌐 URL Rewriting Test</h2>";
echo "<p>Testing if URL rewriting is working:</p>";
echo "<ul>";
echo "<li><a href='test.php'>test.php</a> - Should work</li>";
echo "<li><a href='install'>install</a> - Should redirect to Laravel</li>";
echo "<li><a href='install/'>install/</a> - Should redirect to Laravel</li>";
echo "</ul>";

echo "<h2>📝 .htaccess Content</h2>";
if (file_exists('.htaccess')) {
    echo "<pre>" . htmlspecialchars(file_get_contents('.htaccess')) . "</pre>";
} else {
    echo "❌ .htaccess file not found";
}

echo "<h2>🔍 Laravel Routes Test</h2>";
if (file_exists('artisan')) {
    echo "<p>Laravel application detected. Testing routes:</p>";
    echo "<ul>";
    echo "<li><a href='/?test=1'>Home with test parameter</a></li>";
    echo "<li><a href='/install?debug=1'>Install with debug parameter</a></li>";
    echo "</ul>";
} else {
    echo "❌ Laravel application not detected";
}

echo "<hr>";
echo "<p><strong>💡 Next Steps:</strong></p>";
echo "<ul>";
echo "<li>If mod_rewrite is not available, contact your hosting provider</li>";
echo "<li>If files are missing, re-upload the application</li>";
echo "<li>If .htaccess is missing, upload it to the root directory</li>";
echo "<li>Check file permissions (755 for directories, 644 for files)</li>";
echo "</ul>";
?>
