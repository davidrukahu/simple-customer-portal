<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Domain;
use App\Models\Customer;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Display the reports dashboard.
     */
    public function index()
    {
        // Accounts Receivable
        $outstandingInvoices = Invoice::whereIn('status', ['sent', 'overdue'])
            ->with('customer')
            ->orderBy('due_date')
            ->get();

        $overdueInvoices = Invoice::where('status', 'overdue')
            ->with('customer')
            ->orderBy('due_date')
            ->get();

        // Expiring Domains
        $expiringDomains30 = Domain::where('status', 'active')
            ->where('expires_at', '<=', now()->addDays(30))
            ->where('expires_at', '>', now())
            ->with('customer')
            ->orderBy('expires_at')
            ->get();

        $expiringDomains60 = Domain::where('status', 'active')
            ->where('expires_at', '<=', now()->addDays(60))
            ->where('expires_at', '>', now()->addDays(30))
            ->with('customer')
            ->orderBy('expires_at')
            ->get();

        $expiringDomains90 = Domain::where('status', 'active')
            ->where('expires_at', '<=', now()->addDays(90))
            ->where('expires_at', '>', now()->addDays(60))
            ->with('customer')
            ->orderBy('expires_at')
            ->get();

        // Revenue by month (last 12 months) - Database agnostic approach
        $payments = Payment::where('paid_on', '>=', now()->subMonths(12))
            ->get()
            ->groupBy(function ($payment) {
                return $payment->paid_on->format('Y-m');
            })
            ->map(function ($monthPayments) {
                return $monthPayments->sum('amount');
            })
            ->sortKeys();
        
        // Convert to the expected format
        $revenueByMonth = $payments->map(function ($total, $month) {
            return (object) ['month' => $month, 'total' => $total];
        })->values();

        // Summary statistics
        $stats = [
            'total_outstanding' => $outstandingInvoices->sum('total'),
            'total_overdue' => $overdueInvoices->sum('total'),
            'outstanding_count' => $outstandingInvoices->count(),
            'overdue_count' => $overdueInvoices->count(),
            'expiring_30_count' => $expiringDomains30->count(),
            'expiring_60_count' => $expiringDomains60->count(),
            'expiring_90_count' => $expiringDomains90->count(),
            'total_revenue_12m' => $revenueByMonth->sum('total'),
        ];

        return view('admin.reports.index', compact(
            'outstandingInvoices',
            'overdueInvoices',
            'expiringDomains30',
            'expiringDomains60',
            'expiringDomains90',
            'revenueByMonth',
            'stats'
        ));
    }
}
