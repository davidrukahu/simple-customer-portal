<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Domain;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DomainController extends Controller
{
    /**
     * Display the customer's domains.
     */
    public function index(Request $request): View
    {
        $customer = auth()->user()->customer;
        
        if (!$customer) {
            abort(404, 'Customer profile not found');
        }

        $query = Domain::where('customer_id', $customer->id);
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('fqdn', 'like', "%{$search}%");
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Sort
        $sortBy = $request->get('sort', 'expires_at');
        $sortDir = $request->get('direction', 'asc');
        $query->orderBy($sortBy, $sortDir);
        
        $domains = $query->paginate(10)->withQueryString();
        
        return view('customer.domains.index', compact('domains'));
    }

    /**
     * Display the specified domain.
     */
    public function show(Domain $domain): View
    {
        $customer = auth()->user()->customer;

        if (! $customer) {
            abort(404, 'Customer profile not found');
        }

        if ((int) $domain->customer_id !== (int) $customer->id) {
            abort(403, 'Unauthorized access to domain');
        }

        return view('customer.domains.show', compact('domain'));
    }
}
