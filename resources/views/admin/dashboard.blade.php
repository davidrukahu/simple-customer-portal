@extends('layouts.admin')

@section('page-title', 'Dashboard')
@section('page-subtitle')
Welcome back, {{ Auth::user()->name }}
@endsection

@section('content')
<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card">
            <div class="stats-icon primary">
                <i class="bi bi-people"></i>
            </div>
            <h3 class="stats-number">{{ $stats['total_customers'] }}</h3>
            <p class="stats-label">Total Customers</p>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card">
            <div class="stats-icon success">
                <i class="bi bi-globe"></i>
            </div>
            <h3 class="stats-number">{{ $stats['active_domains'] }}</h3>
            <p class="stats-label">Active Domains</p>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card">
            <div class="stats-icon warning">
                <i class="bi bi-receipt"></i>
            </div>
            <h3 class="stats-number">{{ $stats['outstanding_invoices'] }}</h3>
            <p class="stats-label">Outstanding Invoices</p>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card">
            <div class="stats-icon danger">
                <i class="bi bi-exclamation-triangle"></i>
            </div>
            <h3 class="stats-number">{{ $stats['overdue_invoices'] }}</h3>
            <p class="stats-label">Overdue Invoices</p>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="quick-actions">
                    <a href="{{ route('admin.customers.create') }}" class="quick-action-btn">
                        <i class="bi bi-person-plus"></i>
                        Add Customer
                    </a>
                    <a href="{{ route('admin.domains.create') }}" class="quick-action-btn">
                        <i class="bi bi-globe"></i>
                        Add Domain
                    </a>
                    <a href="{{ route('admin.services.create') }}" class="quick-action-btn">
                        <i class="bi bi-gear"></i>
                        Add Service
                    </a>
                    <a href="{{ route('admin.invoices.create') }}" class="quick-action-btn">
                        <i class="bi bi-receipt"></i>
                        Create Invoice
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="row">
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Recent Invoices</h5>
            </div>
            <div class="card-body">
                @if($recentInvoices->count() > 0)
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Invoice</th>
                                    <th>Customer</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Due Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentInvoices as $invoice)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.invoices.show', $invoice) }}" class="text-decoration-none fw-medium">
                                                {{ $invoice->number }}
                                            </a>
                                        </td>
                                        <td>{{ $invoice->customer->name }}</td>
                                        <td>{{ $invoice->currency }} {{ number_format($invoice->total, 2) }}</td>
                                        <td>
                                            @php
                                                $statusColors = [
                                                    'draft' => 'secondary',
                                                    'sent' => 'primary',
                                                    'paid' => 'success',
                                                    'overdue' => 'danger',
                                                    'void' => 'dark'
                                                ];
                                            @endphp
                                            <span class="badge bg-{{ $statusColors[$invoice->status] ?? 'secondary' }}">
                                                {{ ucfirst($invoice->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            {{ $invoice->due_date->format('M d, Y') }}
                                            @if($invoice->due_date->isPast() && $invoice->status !== 'paid')
                                                <br><span class="badge bg-danger">Overdue</span>
                                            @elseif($invoice->days_until_due <= 7 && $invoice->status !== 'paid')
                                                <br><span class="badge bg-warning">Due Soon</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ route('admin.invoices.index') }}" class="btn btn-outline-primary">View All Invoices</a>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-receipt text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-2">No invoices found</p>
                        <a href="{{ route('admin.invoices.create') }}" class="btn btn-primary">Create First Invoice</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Expiring Domains</h5>
            </div>
            <div class="card-body">
                @if($expiringDomains->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($expiringDomains as $domain)
                            <div class="list-group-item px-0 border-0">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">{{ $domain->fqdn }}</h6>
                                        <p class="mb-1 text-muted">{{ $domain->customer->name }}</p>
                                        <small class="text-muted">{{ $domain->expires_at->format('M d, Y') }}</small>
                                    </div>
                                    <div class="text-end">
                                        @if($domain->expires_at->isPast())
                                            <span class="badge bg-danger">Expired</span>
                                        @elseif($domain->days_until_expiry <= 30)
                                            <span class="badge bg-warning">{{ $domain->days_until_expiry }} days</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ route('admin.domains.index') }}" class="btn btn-outline-primary">View All Domains</a>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-globe text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-2">No expiring domains</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection