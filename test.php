<?php
// Simple test file to verify PHP is working
echo "<h1>PHP Test</h1>";
echo "<p>PHP Version: " . PHP_VERSION . "</p>";
echo "<p>Current Time: " . date('Y-m-d H:i:s') . "</p>";
echo "<p>Server: " . $_SERVER['SERVER_SOFTWARE'] . "</p>";

// Check if Laravel files exist
echo "<h2>File Check</h2>";
$files = ['artisan', 'composer.json', 'app', 'routes', 'public'];
foreach ($files as $file) {
    $exists = file_exists($file);
    echo "<p>" . $file . ": " . ($exists ? "✅ Exists" : "❌ Missing") . "</p>";
}

// Check if .installed file exists
$installed = file_exists('.installed');
echo "<h2>Installation Status</h2>";
echo "<p>Installed: " . ($installed ? "✅ Yes" : "❌ No") . "</p>";

if (!$installed) {
    echo "<p><a href='/install'>Go to Installation Wizard</a></p>";
    echo "<p><a href='/test'>Test Application</a></p>";
}
?>
