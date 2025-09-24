<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class InstallController extends Controller
{
    public function index()
    {
        // Check if already installed
        if (File::exists(base_path('.installed'))) {
            return redirect('/');
        }

        return view('install.index');
    }

    public function checkRequirements()
    {
        $requirements = [
            'PHP Version >= 8.1' => version_compare(PHP_VERSION, '8.1.0', '>='),
            'OpenSSL Extension' => extension_loaded('openssl'),
            'PDO Extension' => extension_loaded('pdo'),
            'Mbstring Extension' => extension_loaded('mbstring'),
            'Tokenizer Extension' => extension_loaded('tokenizer'),
            'XML Extension' => extension_loaded('xml'),
            'Ctype Extension' => extension_loaded('ctype'),
            'JSON Extension' => extension_loaded('json'),
            'BCMath Extension' => extension_loaded('bcmath'),
            'Fileinfo Extension' => extension_loaded('fileinfo'),
            'GD Extension' => extension_loaded('gd'),
        ];

        $writablePaths = [
            'storage/' => is_writable(storage_path()),
            'bootstrap/cache/' => is_writable(base_path('bootstrap/cache')),
        ];

        return response()->json([
            'requirements' => $requirements,
            'writable_paths' => $writablePaths,
            'all_requirements_met' => !in_array(false, $requirements),
            'all_paths_writable' => !in_array(false, $writablePaths),
        ]);
    }

    public function install(Request $request)
    {
        $request->validate([
            'app_name' => 'required|string|max:255',
            'app_url' => 'required|url',
            'db_host' => 'required|string',
            'db_port' => 'required|integer',
            'db_database' => 'required|string',
            'db_username' => 'required|string',
            'db_password' => 'required|string',
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email',
            'admin_password' => 'required|string|min:8',
        ]);

        try {
            // Test database connection
            $this->testDatabaseConnection($request);

            // Generate app key
            $appKey = 'base64:' . base64_encode(Str::random(32));

            // Create .env file
            $envContent = $this->generateEnvFile($request, $appKey);
            File::put(base_path('.env'), $envContent);

            // Refresh runtime configuration to use the new database connection
            $currentMysqlConfig = config('database.connections.mysql', []);
            $updatedMysqlConfig = array_merge($currentMysqlConfig, [
                'driver' => 'mysql',
                'host' => $request->db_host,
                'port' => $request->db_port,
                'database' => $request->db_database,
                'username' => $request->db_username,
                'password' => $request->db_password,
            ]);

            config([
                'database.connections.mysql' => $updatedMysqlConfig,
                'database.default' => 'mysql',
            ]);

            DB::purge('mysql');
            DB::setDefaultConnection('mysql');

            // Run migrations
            Artisan::call('migrate', [
                '--force' => true,
                '--database' => 'mysql',
            ]);

            // Create admin user
            $this->createAdminUser($request);

            // Create installed flag
            File::put(base_path('.installed'), date('Y-m-d H:i:s'));

            return response()->json([
                'success' => true,
                'message' => 'Installation completed successfully!',
                'redirect_url' => $request->app_url . '/login'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Installation failed: ' . $e->getMessage()
            ], 500);
        }
    }

    private function testDatabaseConnection(Request $request)
    {
        $config = [
            'driver' => 'mysql',
            'host' => $request->db_host,
            'port' => $request->db_port,
            'database' => $request->db_database,
            'username' => $request->db_username,
            'password' => $request->db_password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
        ];

        config(['database.connections.test' => $config]);

        DB::connection('test')->getPdo();
    }

    private function generateEnvFile(Request $request, string $appKey): string
    {
        return "APP_NAME=\"{$request->app_name}\"
APP_ENV=production
APP_KEY={$appKey}
APP_DEBUG=false
APP_TIMEZONE=Africa/Nairobi
APP_URL={$request->app_url}

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

APP_MAINTENANCE_DRIVER=file
APP_MAINTENANCE_STORE=database

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST={$request->db_host}
DB_PORT={$request->db_port}
DB_DATABASE={$request->db_database}
DB_USERNAME={$request->db_username}
DB_PASSWORD={$request->db_password}

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

MEMCACHED_HOST=127.0.0.1

REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=localhost
MAIL_PORT=587
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=\"noreply@onechamber.co.ke\"
MAIL_FROM_NAME=\"{$request->app_name}\"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

VITE_APP_NAME=\"{$request->app_name}\"";
    }

    private function createAdminUser(Request $request)
    {
        DB::table('users')->insert([
            'name' => $request->admin_name,
            'email' => $request->admin_email,
            'password' => Hash::make($request->admin_password),
            'role' => 'admin',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
