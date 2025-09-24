<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InstallController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
use Illuminate\Support\Facades\Route;

// Installation routes (only available if not installed)
if (!file_exists(base_path('.installed'))) {
    // Test route to verify application is working
    Route::get('/test', function () {
        return response()->json([
            'status' => 'success',
            'message' => 'Application is working',
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'installed' => file_exists(base_path('.installed'))
        ]);
    });
    
    Route::get('/install', [InstallController::class, 'index'])->name('install');
    Route::get('/install/requirements', [InstallController::class, 'checkRequirements'])->name('install.requirements');
    Route::post('/install', [InstallController::class, 'install'])->name('install.process');
    
    // Redirect all other routes to install if not installed
    Route::get('/{any}', function () {
        return redirect('/install');
    })->where('any', '.*');
} else {
    // Normal application routes
    Route::get('/', function () {
        return redirect()->route('login');
    });

// Admin routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Customer management
    Route::resource('customers', \App\Http\Controllers\Admin\CustomerController::class);
    Route::patch('/customers/{customer}/toggle-status', [\App\Http\Controllers\Admin\CustomerController::class, 'toggleStatus'])->name('customers.toggle-status');
    Route::get('/customers-import', [\App\Http\Controllers\Admin\CustomerController::class, 'importForm'])->name('customers.import-form');
    Route::post('/customers-import', [\App\Http\Controllers\Admin\CustomerController::class, 'import'])->name('customers.import');
    Route::get('/customers-export', [\App\Http\Controllers\Admin\CustomerController::class, 'export'])->name('customers.export');
    Route::get('/customers-sample-csv', [\App\Http\Controllers\Admin\CustomerController::class, 'sampleCsv'])->name('customers.sample-csv');
    Route::patch('/customers/{id}/restore', [\App\Http\Controllers\Admin\CustomerController::class, 'restore'])->name('customers.restore');
    
    // Domain management
    Route::resource('domains', \App\Http\Controllers\Admin\DomainController::class);
    Route::get('/domains-import', [\App\Http\Controllers\Admin\DomainController::class, 'importForm'])->name('domains.import-form');
    Route::post('/domains-import', [\App\Http\Controllers\Admin\DomainController::class, 'import'])->name('domains.import');
    Route::get('/domains-export', [\App\Http\Controllers\Admin\DomainController::class, 'export'])->name('domains.export');
    Route::get('/domains-sample-csv', [\App\Http\Controllers\Admin\DomainController::class, 'sampleCsv'])->name('domains.sample-csv');
    
    // Service management
    Route::resource('services', \App\Http\Controllers\Admin\ServiceController::class);
    
    // Invoice management
        Route::resource('invoices', \App\Http\Controllers\Admin\InvoiceController::class);
        Route::patch('/invoices/{invoice}/mark-paid', [\App\Http\Controllers\Admin\InvoiceController::class, 'markPaid'])->name('invoices.mark-paid');
        Route::get('/invoices/{invoice}/download', [\App\Http\Controllers\Admin\InvoiceController::class, 'download'])->name('invoices.download');
    
    // Reports
    Route::get('/reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
    
    // Settings
    Route::get('/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('settings.update');
});

// Customer routes
Route::middleware(['auth', 'role:customer'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');
    
    // Customer domain management
    Route::get('/domains', [\App\Http\Controllers\Customer\DomainController::class, 'index'])->name('domains.index');
    Route::get('/domains/{domain}', [\App\Http\Controllers\Customer\DomainController::class, 'show'])->name('domains.show');
    
    // Customer service management
    Route::get('/services', [\App\Http\Controllers\Customer\ServiceController::class, 'index'])->name('services.index');
    Route::get('/services/{service}', [\App\Http\Controllers\Customer\ServiceController::class, 'show'])->name('services.show');
    
    // Customer invoice management
    Route::get('/invoices', [\App\Http\Controllers\Customer\InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/invoices/{invoice}', [\App\Http\Controllers\Customer\InvoiceController::class, 'show'])->name('invoices.show');
    Route::get('/invoices/{invoice}/download', [\App\Http\Controllers\Customer\InvoiceController::class, 'download'])->name('invoices.download');
});

// General dashboard route (redirects based on role)
Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user->isAdmin()) {
        return redirect()->route('admin.dashboard');
    } else {
        return redirect()->route('customer.dashboard');
    }
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
}
