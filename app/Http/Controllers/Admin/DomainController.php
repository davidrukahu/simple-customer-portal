<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Domain;
use App\Models\Customer;
use Illuminate\Http\Request;
use League\Csv\Reader;
use League\Csv\Writer;
use Illuminate\Http\Response;
use Carbon\Carbon;

class DomainController extends Controller
{
    /**
     * Display a listing of domains.
     */
    public function index(Request $request)
    {
        $query = Domain::with('customer');
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('fqdn', 'like', "%{$search}%")
                  ->orWhere('registrar', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($customerQuery) use ($search) {
                      $customerQuery->where('name', 'like', "%{$search}%")
                                   ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by customer
        if ($request->filled('customer')) {
            $query->where('customer_id', $request->customer);
        }
        
        // Filter by expiry
        if ($request->filled('expiry')) {
            switch ($request->expiry) {
                case 'expired':
                    $query->where('expires_at', '<', now());
                    break;
                case 'expiring_30':
                    $query->where('expires_at', '>=', now())
                          ->where('expires_at', '<=', now()->addDays(30));
                    break;
                case 'expiring_60':
                    $query->where('expires_at', '>=', now())
                          ->where('expires_at', '<=', now()->addDays(60));
                    break;
                case 'expiring_90':
                    $query->where('expires_at', '>=', now())
                          ->where('expires_at', '<=', now()->addDays(90));
                    break;
            }
        }
        
        // Sort
        $sortBy = $request->get('sort', 'expires_at');
        $sortDir = $request->get('direction', 'asc');
        $query->orderBy($sortBy, $sortDir);
        
        $domains = $query->paginate(20)->withQueryString();
        
        // Get filter options
        $customers = Customer::orderBy('name')->get();
        $statuses = ['active', 'expired', 'grace', 'redemption', 'transfer-pending'];
        
        return view('admin.domains.index', compact('domains', 'customers', 'statuses'));
    }

    /**
     * Show the form for creating a new domain.
     */
    public function create()
    {
        $customers = Customer::where('is_active', true)->orderBy('name')->get();
        return view('admin.domains.create', compact('customers'));
    }

    /**
     * Store a newly created domain in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'fqdn' => ['required', 'string', 'max:255'],
            'registrar' => ['nullable', 'string', 'max:255'],
            'registered_at' => ['nullable', 'date'],
            'expires_at' => ['required', 'date', 'after:today'],
            'term_years' => ['required', 'integer', 'min:1', 'max:10'],
            'price' => ['required', 'numeric', 'min:0'],
            'currency' => ['required', 'string', 'max:3'],
            'status' => ['required', 'in:active,expired,grace,redemption,transfer-pending'],
            'auto_renew' => ['boolean'],
            'service_notes' => ['nullable', 'string'],
        ]);

        // Check for duplicate domain for the same customer
        $existingDomain = Domain::where('customer_id', $validated['customer_id'])
                               ->where('fqdn', $validated['fqdn'])
                               ->first();
                               
        if ($existingDomain) {
            return back()->withErrors(['fqdn' => 'This domain is already registered for this customer.'])
                         ->withInput();
        }

        Domain::create($validated);

        return redirect()->route('admin.domains.index')
                         ->with('success', 'Domain created successfully.');
    }

    /**
     * Display the specified domain.
     */
    public function show(Domain $domain)
    {
        $domain->load(['customer', 'invoiceItems.invoice']);
        
        // Calculate days until expiry
        $daysUntilExpiry = now()->diffInDays($domain->expires_at, false);
        
        // Get related invoices
        $relatedInvoices = $domain->invoiceItems()->with('invoice')->get()
                                 ->pluck('invoice')->unique('id');
        
        return view('admin.domains.show', compact('domain', 'daysUntilExpiry', 'relatedInvoices'));
    }

    /**
     * Show the form for editing the specified domain.
     */
    public function edit(Domain $domain)
    {
        $customers = Customer::where('is_active', true)->orderBy('name')->get();
        return view('admin.domains.edit', compact('domain', 'customers'));
    }

    /**
     * Update the specified domain in storage.
     */
    public function update(Request $request, Domain $domain)
    {
        $validated = $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'fqdn' => ['required', 'string', 'max:255'],
            'registrar' => ['nullable', 'string', 'max:255'],
            'registered_at' => ['nullable', 'date'],
            'expires_at' => ['required', 'date'],
            'term_years' => ['required', 'integer', 'min:1', 'max:10'],
            'price' => ['required', 'numeric', 'min:0'],
            'currency' => ['required', 'string', 'max:3'],
            'status' => ['required', 'in:active,expired,grace,redemption,transfer-pending'],
            'auto_renew' => ['boolean'],
            'service_notes' => ['nullable', 'string'],
        ]);

        // Check for duplicate domain for the same customer (excluding current domain)
        $existingDomain = Domain::where('customer_id', $validated['customer_id'])
                               ->where('fqdn', $validated['fqdn'])
                               ->where('id', '!=', $domain->id)
                               ->first();
                               
        if ($existingDomain) {
            return back()->withErrors(['fqdn' => 'This domain is already registered for this customer.'])
                         ->withInput();
        }

        $domain->update($validated);

        return redirect()->route('admin.domains.show', $domain)
                         ->with('success', 'Domain updated successfully.');
    }

    /**
     * Remove the specified domain from storage.
     */
    public function destroy(Domain $domain)
    {
        $domain->delete();

        return redirect()->route('admin.domains.index')
                         ->with('success', 'Domain deleted successfully.');
    }

    /**
     * Update domain status.
     */
    public function updateStatus(Request $request, Domain $domain)
    {
        $request->validate([
            'status' => ['required', 'in:active,expired,grace,redemption,transfer-pending'],
        ]);

        $domain->update(['status' => $request->status]);

        return redirect()->back()
                         ->with('success', 'Domain status updated successfully.');
    }

    /**
     * Bulk update domain statuses.
     */
    public function bulkUpdateStatus(Request $request)
    {
        $request->validate([
            'domain_ids' => ['required', 'array'],
            'domain_ids.*' => ['exists:domains,id'],
            'status' => ['required', 'in:active,expired,grace,redemption,transfer-pending'],
        ]);

        Domain::whereIn('id', $request->domain_ids)
              ->update(['status' => $request->status]);

        $count = count($request->domain_ids);
        return redirect()->back()
                         ->with('success', "Updated status for {$count} domain(s) successfully.");
    }

    /**
     * Export domains to CSV.
     */
    public function export()
    {
        $domains = Domain::with('customer')->get();
        
        $csv = Writer::createFromString('');
        
        // Add header
        $csv->insertOne([
            'Customer Name', 'Customer Email', 'Domain', 'Registrar', 'Registered Date',
            'Expiry Date', 'Term Years', 'Price', 'Currency', 'Status', 'Auto Renew', 'Notes'
        ]);
        
        // Add data
        foreach ($domains as $domain) {
            $csv->insertOne([
                $domain->customer->name,
                $domain->customer->email,
                $domain->fqdn,
                $domain->registrar,
                $domain->registered_at ? $domain->registered_at->format('Y-m-d') : '',
                $domain->expires_at->format('Y-m-d'),
                $domain->term_years,
                $domain->price,
                $domain->currency,
                $domain->status,
                $domain->auto_renew ? 'Yes' : 'No',
                $domain->service_notes,
            ]);
        }
        
        $filename = 'domains_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        return new Response($csv->toString(), 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    /**
     * Show import form.
     */
    public function importForm()
    {
        return view('admin.domains.import');
    }

    /**
     * Import domains from CSV.
     */
    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => ['required', 'file', 'mimes:csv,txt', 'max:2048'],
        ]);

        $csv = Reader::createFromPath($request->file('csv_file')->getPathname(), 'r');
        $csv->setHeaderOffset(0);
        
        $errors = [];
        $imported = 0;
        
        foreach ($csv as $index => $record) {
            try {
                // Validate required fields
                if (empty($record['Customer Email']) || empty($record['Domain']) || empty($record['Expiry Date'])) {
                    $errors[] = "Row " . ($index + 2) . ": Customer Email, Domain, and Expiry Date are required";
                    continue;
                }
                
                // Find customer by email
                $customer = Customer::where('email', $record['Customer Email'])->first();
                if (!$customer) {
                    $errors[] = "Row " . ($index + 2) . ": Customer not found with email " . $record['Customer Email'];
                    continue;
                }
                
                // Check for duplicate domain
                $existingDomain = Domain::where('customer_id', $customer->id)
                                       ->where('fqdn', $record['Domain'])
                                       ->first();
                if ($existingDomain) {
                    $errors[] = "Row " . ($index + 2) . ": Domain already exists for this customer";
                    continue;
                }
                
                // Parse dates
                $registeredAt = !empty($record['Registered Date']) ? 
                    Carbon::createFromFormat('Y-m-d', $record['Registered Date']) : null;
                $expiresAt = Carbon::createFromFormat('Y-m-d', $record['Expiry Date']);
                
                if (!$expiresAt) {
                    $errors[] = "Row " . ($index + 2) . ": Invalid expiry date format";
                    continue;
                }
                
                // Create domain
                Domain::create([
                    'customer_id' => $customer->id,
                    'fqdn' => $record['Domain'],
                    'registrar' => $record['Registrar'] ?? null,
                    'registered_at' => $registeredAt,
                    'expires_at' => $expiresAt,
                    'term_years' => (int) ($record['Term Years'] ?? 1),
                    'price' => (float) ($record['Price'] ?? 0),
                    'currency' => $record['Currency'] ?? $customer->currency,
                    'status' => $record['Status'] ?? 'active',
                    'auto_renew' => ($record['Auto Renew'] ?? 'No') === 'Yes',
                    'service_notes' => $record['Notes'] ?? null,
                ]);
                
                $imported++;
                
            } catch (\Exception $e) {
                $errors[] = "Row " . ($index + 2) . ": " . $e->getMessage();
            }
        }
        
        $message = "Imported {$imported} domains successfully.";
        if (!empty($errors)) {
            $message .= " " . count($errors) . " errors occurred.";
        }
        
        return redirect()->route('admin.domains.index')
                         ->with('success', $message)
                         ->with('import_errors', $errors);
    }

    /**
     * Download sample CSV template.
     */
    public function sampleCsv()
    {
        $csv = Writer::createFromString('');
        
        // Add header
        $csv->insertOne([
            'Customer Email', 'Domain', 'Registrar', 'Registered Date',
            'Expiry Date', 'Term Years', 'Price', 'Currency', 'Status', 'Auto Renew', 'Notes'
        ]);
        
        // Add sample data
        $csv->insertOne([
            'john@example.com',
            'example.com',
            'Safaricom',
            '2024-01-15',
            '2025-01-15',
            '1',
            '1200.00',
            'KES',
            'active',
            'Yes',
            'Primary business domain'
        ]);
        
        $filename = 'domain_import_template.csv';
        
        return new Response($csv->toString(), 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    /**
     * Check for domains requiring renewal reminders.
     */
    public function renewalReminders()
    {
        $domains = Domain::with('customer')
                         ->where('status', 'active')
                         ->where('expires_at', '<=', now()->addDays(30))
                         ->where('expires_at', '>', now())
                         ->orderBy('expires_at')
                         ->get();
        
        return view('admin.domains.renewal-reminders', compact('domains'));
    }

    /**
     * Update expired domain statuses automatically.
     */
    public function updateExpiredStatuses()
    {
        $updated = 0;
        
        // Update active domains that have expired
        $expiredDomains = Domain::where('status', 'active')
                               ->where('expires_at', '<', now())
                               ->get();
        
        foreach ($expiredDomains as $domain) {
            $domain->updateStatusBasedOnExpiry();
            $updated++;
        }
        
        return redirect()->back()
                         ->with('success', "Updated status for {$updated} expired domain(s).");
    }
}
