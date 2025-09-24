<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Domain;
use App\Models\Invoice;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the customer dashboard.
     */
    public function index(): View
    {
        $customer = auth()->user()->customer;
        
        if (!$customer) {
            // If no customer record exists, create one
            $customer = \App\Models\Customer::create([
                'user_id' => auth()->id(),
                'name' => auth()->user()->name,
                'email' => auth()->user()->email,
                'currency' => 'KES',
                'is_active' => true,
            ]);
        }

        // Get customer's domains
        $domains = Domain::where('customer_id', $customer->id)->get();
        $recentDomains = $domains->sortByDesc('created_at')->take(5);

        // Get customer's invoices
        $invoices = Invoice::where('customer_id', $customer->id)->get();
        $recentInvoices = $invoices->sortByDesc('created_at')->take(5);

        // Get customer's services
        $services = Service::where('customer_id', $customer->id)->get();

        // Calculate statistics
        $stats = [
            'total_domains' => $domains->count(),
            'active_domains' => $domains->where('status', 'active')->count(),
            'total_invoices' => $invoices->count(),
            'outstanding_amount' => $invoices->whereIn('status', ['sent', 'overdue'])->sum('total'),
            'overdue_count' => $invoices->where('status', 'overdue')->count(),
            'total_services' => $services->count(),
            'active_services' => $services->where('status', 'active')->count(),
        ];

        return view('customer.dashboard', compact('stats', 'recentDomains', 'recentInvoices'));
    }
}
