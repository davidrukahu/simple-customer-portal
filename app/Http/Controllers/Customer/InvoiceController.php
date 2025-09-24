<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    /**
     * Display the customer's invoices.
     */
    public function index(Request $request): View
    {
        $customer = auth()->user()->customer;
        
        if (!$customer) {
            abort(404, 'Customer profile not found');
        }

        $query = Invoice::where('customer_id', $customer->id);
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('number', 'like', "%{$search}%");
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Sort
        $sortBy = $request->get('sort', 'created_at');
        $sortDir = $request->get('direction', 'desc');
        $query->orderBy($sortBy, $sortDir);
        
        $invoices = $query->paginate(10)->withQueryString();
        
        return view('customer.invoices.index', compact('invoices'));
    }

    /**
     * Display the specified invoice.
     */
    public function show(Invoice $invoice): View
    {
        // Ensure the invoice belongs to the authenticated customer
        if ($invoice->customer_id !== auth()->user()->customer->id) {
            abort(403, 'Unauthorized access to invoice');
        }

        $invoice->load(['items', 'payments']);
        
        return view('customer.invoices.show', compact('invoice'));
    }

    /**
     * Download invoice PDF.
     */
    public function download(Invoice $invoice)
    {
        // Ensure the invoice belongs to the authenticated customer
        if ($invoice->customer_id !== auth()->user()->customer->id) {
            abort(403, 'Unauthorized access to invoice');
        }

        $invoice->load(['items', 'payments', 'customer']);
        
        $pdf = Pdf::loadView('pdf.invoice', compact('invoice'));
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->download("invoice-{$invoice->number}.pdf");
    }
}
