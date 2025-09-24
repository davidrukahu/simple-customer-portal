<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServiceController extends Controller
{
    /**
     * Display the customer's services.
     */
    public function index(Request $request): View
    {
        $customer = auth()->user()->customer;
        
        if (!$customer) {
            abort(404, 'Customer profile not found');
        }

        $query = Service::where('customer_id', $customer->id);
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Sort
        $sortBy = $request->get('sort', 'created_at');
        $sortDir = $request->get('direction', 'desc');
        $query->orderBy($sortBy, $sortDir);
        
        $services = $query->paginate(10)->withQueryString();
        
        return view('customer.services.index', compact('services'));
    }

    /**
     * Display the specified service.
     */
    public function show(Service $service): View
    {
        // Ensure the service belongs to the authenticated customer
        if ($service->customer_id !== auth()->user()->customer->id) {
            abort(403, 'Unauthorized access to service');
        }

        return view('customer.services.show', compact('service'));
    }
}
