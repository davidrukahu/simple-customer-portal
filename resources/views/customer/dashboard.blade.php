@extends('layouts.customer')

@section('page-title', 'Dashboard')
@section('page-subtitle')
Welcome back, {{ Auth::user()->name }}
@endsection

@section('content')
<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <a href="{{ route('customer.domains.index') }}" class="stats-card">
            <div class="stats-icon primary">
                <i class="bi bi-globe"></i>
            </div>
            <h3 class="stats-number">{{ $stats['total_domains'] }}</h3>
            <p class="stats-label">Total Domains</p>
        </a>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <a href="{{ route('customer.domains.index') }}" class="stats-card">
            <div class="stats-icon success">
                <i class="bi bi-check-circle"></i>
            </div>
            <h3 class="stats-number">{{ $stats['active_domains'] }}</h3>
            <p class="stats-label">Active Domains</p>
        </a>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <a href="{{ route('customer.invoices.index') }}" class="stats-card">
            <div class="stats-icon warning">
                <i class="bi bi-receipt"></i>
            </div>
            <h3 class="stats-number">{{ $stats['total_invoices'] }}</h3>
            <p class="stats-label">Total Invoices</p>
        </a>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <a href="{{ route('customer.invoices.index') }}?status=overdue" class="stats-card">
            <div class="stats-icon danger">
                <i class="bi bi-exclamation-triangle"></i>
            </div>
            <h3 class="stats-number">{{ $stats['overdue_count'] }}</h3>
            <p class="stats-label">Overdue Invoices</p>
        </a>
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
                    <a href="{{ route('customer.domains.index') }}" class="quick-action-btn">
                        <i class="bi bi-globe"></i>
                        View Domains
                    </a>
                    <a href="{{ route('customer.services.index') }}" class="quick-action-btn">
                        <i class="bi bi-gear"></i>
                        View Services
                    </a>
                    <a href="{{ route('customer.invoices.index') }}" class="quick-action-btn">
                        <i class="bi bi-receipt"></i>
                        View Invoices
                    </a>
                    <a href="{{ route('customer.invoices.index') }}?status=overdue" class="quick-action-btn">
                        <i class="bi bi-exclamation-triangle"></i>
                        Overdue Invoices
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
                <h5 class="mb-0">Recent Domains</h5>
            </div>
            <div class="card-body">
                @if($recentDomains->count() > 0)
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Domain</th>
                                    <th>Registrar</th>
                                    <th>Expires</th>
                                    <th>Status</th>
                                    <th>Auto Renew</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentDomains as $domain)
                                    <tr>
                                        <td>
                                            <a href="{{ route('customer.domains.show', $domain) }}" class="text-decoration-none fw-medium">
                                                {{ $domain->fqdn }}
                                            </a>
                                        </td>
                                        <td>{{ $domain->registrar ?: 'N/A' }}</td>
                                        <td>
                                            {{ $domain->expires_at->format('M d, Y') }}
                                            @if($domain->expires_at->isPast())
                                                <br><span class="badge bg-danger">Expired</span>
                                            @elseif($domain->days_until_expiry <= 30)
                                                <br><span class="badge bg-warning">{{ $domain->days_until_expiry }} days</span>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $statusColors = [
                                                    'active' => 'success',
                                                    'expired' => 'danger',
                                                    'grace' => 'warning',
                                                    'redemption' => 'danger',
                                                    'transfer-pending' => 'info'
                                                ];
                                            @endphp
                                            <span class="badge bg-{{ $statusColors[$domain->status] ?? 'secondary' }}">
                                                {{ ucfirst(str_replace('-', ' ', $domain->status)) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($domain->auto_renew)
                                                <span class="badge bg-success">Yes</span>
                                            @else
                                                <span class="badge bg-secondary">No</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ route('customer.domains.index') }}" class="btn btn-outline-primary">View All Domains</a>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-globe text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-2">No domains found</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Recent Invoices</h5>
            </div>
            <div class="card-body">
                @if($recentInvoices->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($recentInvoices as $invoice)
                            <div class="list-group-item px-0 border-0">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">
                                            <a href="{{ route('customer.invoices.show', $invoice) }}" class="text-decoration-none">
                                                {{ $invoice->number }}
                                            </a>
                                        </h6>
                                        <p class="mb-1 text-muted">{{ $invoice->currency }} {{ number_format($invoice->total, 2) }}</p>
                                        <small class="text-muted">{{ $invoice->due_date->format('M d, Y') }}</small>
                                    </div>
                                    <div class="text-end">
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
                                        @if($invoice->due_date->isPast() && $invoice->status !== 'paid')
                                            <br><span class="badge bg-danger mt-1">Overdue</span>
                                        @elseif($invoice->days_until_due <= 7 && $invoice->status !== 'paid')
                                            <br><span class="badge bg-warning mt-1">Due Soon</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ route('customer.invoices.index') }}" class="btn btn-outline-primary">View All Invoices</a>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-receipt text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-2">No invoices found</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection