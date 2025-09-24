<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use League\Csv\Reader;
use League\Csv\Writer;
use Illuminate\Http\Response;

class CustomerController extends Controller
{
    /**
     * Display a listing of customers.
     */
    public function index(Request $request)
    {
        $query = Customer::with('user');
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('company', 'like', "%{$search}%");
            });
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }
        
        // Sort
        $sortBy = $request->get('sort', 'created_at');
        $sortDir = $request->get('direction', 'desc');
        $query->orderBy($sortBy, $sortDir);
        
        $customers = $query->paginate(20)->withQueryString();
        
        return view('admin.customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new customer.
     */
    public function create()
    {
        return view('admin.customers.create');
    }

    /**
     * Store a newly created customer in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'company' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'currency' => ['required', 'string', 'max:3'],
            'billing_address' => ['nullable', 'array'],
            'billing_address.street' => ['nullable', 'string', 'max:255'],
            'billing_address.city' => ['nullable', 'string', 'max:255'],
            'billing_address.state' => ['nullable', 'string', 'max:255'],
            'billing_address.postal_code' => ['nullable', 'string', 'max:20'],
            'billing_address.country' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]);

        // Create user account
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'customer',
        ]);

        // Create customer record
        $customer = Customer::create([
            'user_id' => $user->id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'company' => $validated['company'],
            'phone' => $validated['phone'],
            'currency' => $validated['currency'],
            'billing_address_json' => $validated['billing_address'] ?? null,
            'notes' => $validated['notes'],
            'is_active' => true,
        ]);

        return redirect()->route('admin.customers.index')
                         ->with('success', 'Customer created successfully.');
    }

    /**
     * Display the specified customer.
     */
    public function show(Customer $customer)
    {
        $customer->load(['user', 'domains', 'invoices.items', 'services']);
        
        $stats = [
            'total_domains' => $customer->domains->count(),
            'active_domains' => $customer->domains->where('status', 'active')->count(),
            'total_invoices' => $customer->invoices->count(),
            'outstanding_amount' => $customer->invoices->whereIn('status', ['sent', 'overdue'])->sum('total'),
            'overdue_count' => $customer->invoices->where('status', 'overdue')->count(),
        ];
        
        return view('admin.customers.show', compact('customer', 'stats'));
    }

    /**
     * Show the form for editing the specified customer.
     */
    public function edit(Customer $customer)
    {
        $customer->load('user');
        return view('admin.customers.edit', compact('customer'));
    }

    /**
     * Update the specified customer in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,' . $customer->user_id],
            'company' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'currency' => ['required', 'string', 'max:3'],
            'billing_address' => ['nullable', 'array'],
            'billing_address.street' => ['nullable', 'string', 'max:255'],
            'billing_address.city' => ['nullable', 'string', 'max:255'],
            'billing_address.state' => ['nullable', 'string', 'max:255'],
            'billing_address.postal_code' => ['nullable', 'string', 'max:20'],
            'billing_address.country' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ]);

        // Update user record
        $customer->user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        // Update customer record
        $customer->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'company' => $validated['company'],
            'phone' => $validated['phone'],
            'currency' => $validated['currency'],
            'billing_address_json' => $validated['billing_address'] ?? null,
            'notes' => $validated['notes'],
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.customers.show', $customer)
                         ->with('success', 'Customer updated successfully.');
    }

    /**
     * Remove the specified customer from storage.
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();
        $customer->user->delete();

        return redirect()->route('admin.customers.index')
                         ->with('success', 'Customer deleted successfully.');
    }

    /**
     * Restore the specified customer.
     */
    public function restore($id)
    {
        $customer = Customer::withTrashed()->findOrFail($id);
        $customer->restore();

        return redirect()->route('admin.customers.index')
                         ->with('success', 'Customer restored successfully.');
    }

    /**
     * Toggle customer status.
     */
    public function toggleStatus(Customer $customer)
    {
        $customer->update(['is_active' => !$customer->is_active]);
        
        $status = $customer->is_active ? 'activated' : 'deactivated';
        return redirect()->back()
                         ->with('success', "Customer {$status} successfully.");
    }

    /**
     * Export customers to CSV.
     */
    public function export()
    {
        $customers = Customer::with('user')->get();
        
        $csv = Writer::createFromString('');
        
        // Add header
        $csv->insertOne([
            'Name', 'Email', 'Company', 'Phone', 'Currency', 
            'Street', 'City', 'State', 'Postal Code', 'Country',
            'Status', 'Created At', 'Notes'
        ]);
        
        // Add data
        foreach ($customers as $customer) {
            $address = $customer->billing_address_json ?? [];
            $csv->insertOne([
                $customer->name,
                $customer->email,
                $customer->company,
                $customer->phone,
                $customer->currency,
                $address['street'] ?? '',
                $address['city'] ?? '',
                $address['state'] ?? '',
                $address['postal_code'] ?? '',
                $address['country'] ?? '',
                $customer->is_active ? 'Active' : 'Inactive',
                $customer->created_at->format('Y-m-d H:i:s'),
                $customer->notes,
            ]);
        }
        
        $filename = 'customers_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
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
        return view('admin.customers.import');
    }

    /**
     * Import customers from CSV.
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
                if (empty($record['Name']) || empty($record['Email'])) {
                    $errors[] = "Row " . ($index + 2) . ": Name and Email are required";
                    continue;
                }
                
                // Check if email already exists
                if (User::where('email', $record['Email'])->exists()) {
                    $errors[] = "Row " . ($index + 2) . ": Email already exists";
                    continue;
                }
                
                // Create user
                $user = User::create([
                    'name' => $record['Name'],
                    'email' => $record['Email'],
                    'password' => Hash::make('password123'), // Default password
                    'role' => 'customer',
                ]);
                
                // Create customer
                Customer::create([
                    'user_id' => $user->id,
                    'name' => $record['Name'],
                    'email' => $record['Email'],
                    'company' => $record['Company'] ?? null,
                    'phone' => $record['Phone'] ?? null,
                    'currency' => $record['Currency'] ?? 'KES',
                    'billing_address_json' => [
                        'street' => $record['Street'] ?? '',
                        'city' => $record['City'] ?? '',
                        'state' => $record['State'] ?? '',
                        'postal_code' => $record['Postal Code'] ?? '',
                        'country' => $record['Country'] ?? '',
                    ],
                    'notes' => $record['Notes'] ?? null,
                    'is_active' => true,
                ]);
                
                $imported++;
                
            } catch (\Exception $e) {
                $errors[] = "Row " . ($index + 2) . ": " . $e->getMessage();
            }
        }
        
        $message = "Imported {$imported} customers successfully.";
        if (!empty($errors)) {
            $message .= " " . count($errors) . " errors occurred.";
        }
        
        return redirect()->route('admin.customers.index')
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
            'Name', 'Email', 'Company', 'Phone', 'Currency', 
            'Street', 'City', 'State', 'Postal Code', 'Country', 'Notes'
        ]);
        
        // Add sample data
        $csv->insertOne([
            'John Doe',
            'john@example.com',
            'Example Corp',
            '+254712345678',
            'KES',
            '123 Sample Street',
            'Nairobi',
            'Nairobi County',
            '00100',
            'Kenya',
            'Sample customer notes'
        ]);
        
        $filename = 'customer_import_template.csv';
        
        return new Response($csv->toString(), 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}
