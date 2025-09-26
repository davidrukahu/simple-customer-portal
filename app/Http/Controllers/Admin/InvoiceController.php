<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Customer;
use App\Models\Domain;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the invoices.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');

        $invoices = Invoice::query()
            ->with('customer')
            ->when($search, function ($query, $search) {
                $query->where('number', 'like', "%{$search}%")
                      ->orWhereHas('customer', function ($q) use ($search) {
                          $q->where('name', 'like', "%{$search}%")
                            ->orWhere('company', 'like', "%{$search}%");
                      });
            })
            ->when($status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->orderBy($sort, $direction)
            ->paginate(10);

        return view('admin.invoices.index', compact('invoices'));
    }

    /**
     * Show the form for creating a new invoice.
     */
    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        $domains = Domain::where('status', 'active')->with('customer')->get();
        $services = Service::where('status', 'active')->with('customer')->get();
        
        return view('admin.invoices.create', compact('customers', 'domains', 'services'));
    }

    /**
     * Store a newly created invoice in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'status' => ['required', Rule::in(['draft', 'sent', 'paid', 'overdue', 'void'])],
            'issue_date' => ['required', 'date'],
            'due_date' => ['required', 'date', 'after_or_equal:issue_date'],
            'subtotal' => ['required', 'numeric', 'min:0'],
            'tax_total' => ['nullable', 'numeric', 'min:0'],
            'total' => ['required', 'numeric', 'min:0'],
            'currency' => ['required', 'string', 'max:3'],
            'notes' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.description' => ['required', 'string'],
            'items.*.qty' => ['required', 'numeric', 'min:0.01'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
            'items.*.line_total' => ['required', 'numeric', 'min:0'],
        ]);

        $invoiceNumber = Invoice::generateInvoiceNumber($validated['issue_date']);

        $invoice = Invoice::create([
            'customer_id' => $validated['customer_id'],
            'number' => $invoiceNumber,
            'status' => $validated['status'],
            'issue_date' => $validated['issue_date'],
            'due_date' => $validated['due_date'],
            'subtotal' => $validated['subtotal'],
            'tax_total' => $validated['tax_total'] ?? 0,
            'total' => $validated['total'],
            'currency' => $validated['currency'],
            'notes' => $validated['notes'],
        ]);

        // Create invoice items
        foreach ($validated['items'] as $item) {
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'item_type' => 'manual',
                'description' => $item['description'],
                'qty' => $item['qty'],
                'unit_price' => $item['unit_price'],
                'line_total' => $item['line_total'],
            ]);
        }

        $invoice->updateStatus();

        return redirect()->route('admin.invoices.index')->with('success', 'Invoice created successfully.');
    }

    /**
     * Display the specified invoice.
     */
    public function show(Invoice $invoice)
    {
        $invoice->load(['customer', 'items', 'payments']);
        return view('admin.invoices.show', compact('invoice'));
    }

    /**
     * Show the form for editing the specified invoice.
     */
    public function edit(Invoice $invoice)
    {
        $customers = Customer::orderBy('name')->get();
        $invoice->load('items');
        return view('admin.invoices.edit', compact('invoice', 'customers'));
    }

    /**
     * Update the specified invoice in storage.
     */
    public function update(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'status' => ['required', Rule::in(['draft', 'sent', 'paid', 'overdue', 'void'])],
            'issue_date' => ['required', 'date'],
            'due_date' => ['required', 'date', 'after_or_equal:issue_date'],
            'subtotal' => ['required', 'numeric', 'min:0'],
            'tax_total' => ['nullable', 'numeric', 'min:0'],
            'total' => ['required', 'numeric', 'min:0'],
            'currency' => ['required', 'string', 'max:3'],
            'notes' => ['nullable', 'string'],
        ]);

        $invoice->update($validated);

        return redirect()->route('admin.invoices.show', $invoice)->with('success', 'Invoice updated successfully.');
    }

    /**
     * Remove the specified invoice from storage.
     */
    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return redirect()->route('admin.invoices.index')->with('success', 'Invoice deleted successfully.');
    }

    /**
     * Mark invoice as paid.
     */
    public function markPaid(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'amount' => ['nullable', 'numeric', 'min:0'],
            'method' => ['nullable', 'string', 'max:50'],
            'reference' => ['nullable', 'string', 'max:255'],
            'paid_on' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        $invoice->markAsPaid(
            $validated['amount'] ?? null,
            $validated['method'] ?? 'manual',
            $validated['reference'] ?? null,
            $validated['paid_on'] ?? null,
            $validated['notes'] ?? null,
        );

        return redirect()->back()->with('success', 'Invoice marked as paid.');
    }

    /**
     * Mark invoice as sent.
     */
    public function markSent(Invoice $invoice)
    {
        $invoice->update(['status' => 'sent']);

        return redirect()->back()->with('success', 'Invoice marked as sent.');
    }

    /**
     * Download invoice PDF.
     */
    public function download(Invoice $invoice)
    {
        $invoice->load(['items', 'payments', 'customer']);
        
        $pdf = Pdf::loadView('pdf.invoice', compact('invoice'));
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->download("invoice-{$invoice->number}.pdf");
    }
}
