@extends('layouts.admin')

@section('title', $customer->name)

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3">{{ $customer->name }}</h1>
            <p class="text-muted mb-0">
                Customer since {{ $customer->created_at->format('M d, Y') }}
                @if($customer->is_active)
                    <span class="badge bg-success ms-2">Active</span>
                @else
                    <span class="badge bg-secondary ms-2">Inactive</span>
                @endif
            </p>
        </div>
        <div>
            <a href="{{ route('admin.customers.edit', $customer) }}" class="btn btn-primary me-2">
                <i class="fas fa-edit"></i> Edit Customer
            </a>
            <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back to Customers
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $stats['total_domains'] }}</h4>
                            <p class="mb-0">Total Domains</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-globe fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $stats['active_domains'] }}</h4>
                            <p class="mb-0">Active Domains</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $customer->currency }} {{ number_format($stats['outstanding_amount'], 2) }}</h4>
                            <p class="mb-0">Outstanding</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-exclamation-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $stats['overdue_count'] }}</h4>
                            <p class="mb-0">Overdue Invoices</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-clock fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Customer Information -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">Customer Information</h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td class="fw-bold">Name:</td>
                            <td>{{ $customer->name }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Email:</td>
                            <td>{{ $customer->email }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Company:</td>
                            <td>{{ $customer->company ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Phone:</td>
                            <td>{{ $customer->phone ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Currency:</td>
                            <td>{{ $customer->currency }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Status:</td>
                            <td>
                                @if($customer->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Billing Address -->
            @if($customer->billing_address_json)
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Billing Address</h6>
                    </div>
                    <div class="card-body">
                        @php $address = $customer->billing_address_json; @endphp
                        <address class="mb-0">
                            @if(!empty($address['street']))
                                {{ $address['street'] }}<br>
                            @endif
                            @if(!empty($address['city']) || !empty($address['state']))
                                {{ $address['city'] }}{{ !empty($address['city']) && !empty($address['state']) ? ', ' : '' }}{{ $address['state'] }}<br>
                            @endif
                            @if(!empty($address['postal_code']))
                                {{ $address['postal_code'] }}<br>
                            @endif
                            @if(!empty($address['country']))
                                {{ $address['country'] }}
                            @endif
                        </address>
                    </div>
                </div>
            @endif

            <!-- Notes -->
            @if($customer->notes)
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Notes</h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">{{ $customer->notes }}</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Domains and Invoices -->
        <div class="col-md-8">
            <!-- Recent Domains -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="card-title mb-0">Domains ({{ $customer->domains->count() }})</h6>
                    <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body">
                    @if($customer->domains->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Domain</th>
                                        <th>Expires</th>
                                        <th>Status</th>
                                        <th>Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($customer->domains->take(5) as $domain)
                                        <tr>
                                            <td>{{ $domain->fqdn }}</td>
                                            <td>{{ $domain->expires_at->format('M d, Y') }}</td>
                                            <td>
                                                <span class="badge bg-{{ $domain->status === 'active' ? 'success' : 'warning' }}">
                                                    {{ ucfirst($domain->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $domain->currency }} {{ number_format($domain->price, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-globe fa-2x text-muted mb-3"></i>
                            <p class="text-muted">No domains registered yet.</p>
                            <a href="#" class="btn btn-primary btn-sm">Add Domain</a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent Invoices -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="card-title mb-0">Recent Invoices ({{ $customer->invoices->count() }})</h6>
                    <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body">
                    @if($customer->invoices->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Invoice #</th>
                                        <th>Date</th>
                                        <th>Due Date</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($customer->invoices->take(5) as $invoice)
                                        <tr>
                                            <td>{{ $invoice->number }}</td>
                                            <td>{{ $invoice->issue_date->format('M d, Y') }}</td>
                                            <td>{{ $invoice->due_date->format('M d, Y') }}</td>
                                            <td>{{ $invoice->currency }} {{ number_format($invoice->total, 2) }}</td>
                                            <td>
                                                <span class="badge bg-{{ 
                                                    $invoice->status === 'paid' ? 'success' : 
                                                    ($invoice->status === 'overdue' ? 'danger' : 'warning') 
                                                }}">
                                                    {{ ucfirst($invoice->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-file-invoice fa-2x text-muted mb-3"></i>
                            <p class="text-muted">No invoices created yet.</p>
                            <a href="#" class="btn btn-primary btn-sm">Create Invoice</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
