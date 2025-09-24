<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index(): View
    {
        // Get dashboard statistics
        $stats = [
            'total_customers' => \App\Models\Customer::count(),
            'active_domains' => \App\Models\Domain::where('status', 'active')->count(),
            'outstanding_invoices' => \App\Models\Invoice::whereIn('status', ['sent', 'overdue'])->count(),
            'overdue_invoices' => \App\Models\Invoice::where('status', 'overdue')->count(),
            'expiring_domains' => \App\Models\Domain::where('status', 'active')
                ->where('expires_at', '<=', now()->addDays(30))
                ->where('expires_at', '>', now())
                ->count(),
        ];

        // Get recent invoices
        $recentInvoices = \App\Models\Invoice::with('customer')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Get expiring domains
        $expiringDomains = \App\Models\Domain::with('customer')
            ->where('status', 'active')
            ->where('expires_at', '<=', now()->addDays(30))
            ->where('expires_at', '>', now())
            ->orderBy('expires_at', 'asc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentInvoices', 'expiringDomains'));
    }
}
