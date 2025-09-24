@extends('layouts.admin')

@section('page-title', 'Reports')
@section('page-subtitle', 'Business analytics and insights')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <button class="btn btn-outline-primary" onclick="window.print()">
                        <i class="bi bi-printer"></i> Print Report
                    </button>
                </div>
            </div>

            <!-- Summary Statistics -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h3 class="text-primary">{{ number_format($stats['outstanding_count']) }}</h3>
                            <p class="text-muted mb-0">Outstanding Invoices</p>
                            <small class="text-success">KSh {{ number_format($stats['total_outstanding'], 2) }}</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h3 class="text-danger">{{ number_format($stats['overdue_count']) }}</h3>
                            <p class="text-muted mb-0">Overdue Invoices</p>
                            <small class="text-danger">KSh {{ number_format($stats['total_overdue'], 2) }}</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h3 class="text-warning">{{ number_format($stats['expiring_30_count']) }}</h3>
                            <p class="text-muted mb-0">Domains Expiring (30 days)</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h3 class="text-success">KSh {{ number_format($stats['total_revenue_12m'], 2) }}</h3>
                            <p class="text-muted mb-0">Revenue (12 months)</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Accounts Receivable -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Accounts Receivable</h5>
                        </div>
                        <div class="card-body">
                            @if($outstandingInvoices->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Invoice</th>
                                                <th>Customer</th>
                                                <th>Amount</th>
                                                <th>Due Date</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($outstandingInvoices->take(10) as $invoice)
                                                <tr>
                                                    <td>{{ $invoice->number }}</td>
                                                    <td>{{ $invoice->customer->name }}</td>
                                                    <td>{{ $invoice->currency }} {{ number_format($invoice->total, 2) }}</td>
                                                    <td>{{ $invoice->due_date->format('M d, Y') }}</td>
                                                    <td>
                                                        <span class="badge bg-{{ $invoice->status === 'overdue' ? 'danger' : 'warning' }}">
                                                            {{ ucfirst($invoice->status) }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @if($outstandingInvoices->count() > 10)
                                    <p class="text-muted small">Showing first 10 of {{ $outstandingInvoices->count() }} outstanding invoices</p>
                                @endif
                            @else
                                <p class="text-muted">No outstanding invoices</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Expiring Domains -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Expiring Domains (Next 30 Days)</h5>
                        </div>
                        <div class="card-body">
                            @if($expiringDomains30->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Domain</th>
                                                <th>Customer</th>
                                                <th>Expires</th>
                                                <th>Days Left</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($expiringDomains30->take(10) as $domain)
                                                <tr>
                                                    <td>{{ $domain->fqdn }}</td>
                                                    <td>{{ $domain->customer->name }}</td>
                                                    <td>{{ $domain->expires_at->format('M d, Y') }}</td>
                                                    <td>
                                                        <span class="badge bg-warning">{{ $domain->days_until_expiry }} days</span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @if($expiringDomains30->count() > 10)
                                    <p class="text-muted small">Showing first 10 of {{ $expiringDomains30->count() }} expiring domains</p>
                                @endif
                            @else
                                <p class="text-muted">No domains expiring in the next 30 days</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Revenue Chart -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Revenue Trend (Last 12 Months)</h5>
                        </div>
                        <div class="card-body">
                            @if($revenueByMonth->count() > 0)
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Month</th>
                                                <th>Revenue</th>
                                                <th>Progress</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($revenueByMonth as $month)
                                                @php
                                                    $maxRevenue = $revenueByMonth->max('total');
                                                    $percentage = $maxRevenue > 0 ? ($month->total / $maxRevenue) * 100 : 0;
                                                @endphp
                                                <tr>
                                                    <td>{{ \Carbon\Carbon::createFromFormat('Y-m', $month->month)->format('M Y') }}</td>
                                                    <td>KSh {{ number_format($month->total, 2) }}</td>
                                                    <td>
                                                        <div class="progress" style="height: 20px;">
                                                            <div class="progress-bar" role="progressbar" 
                                                                 style="width: {{ $percentage }}%"
                                                                 aria-valuenow="{{ $percentage }}" 
                                                                 aria-valuemin="0" aria-valuemax="100">
                                                                {{ number_format($percentage, 1) }}%
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted">No revenue data available</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Reports -->
            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Domains Expiring (60-90 Days)</h5>
                        </div>
                        <div class="card-body">
                            <h4 class="text-info">{{ $stats['expiring_60_count'] + $stats['expiring_90_count'] }}</h4>
                            <p class="text-muted mb-0">Domains expiring in 60-90 days</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Overdue Invoices</h5>
                        </div>
                        <div class="card-body">
                            @if($overdueInvoices->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Invoice</th>
                                                <th>Customer</th>
                                                <th>Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($overdueInvoices->take(5) as $invoice)
                                                <tr>
                                                    <td>{{ $invoice->number }}</td>
                                                    <td>{{ $invoice->customer->name }}</td>
                                                    <td>{{ $invoice->currency }} {{ number_format($invoice->total, 2) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted">No overdue invoices</p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Quick Actions</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('admin.invoices.create') }}" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-file-invoice"></i> Create Invoice
                                </a>
                                <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-users"></i> Manage Customers
                                </a>
                                <a href="{{ route('admin.domains.index') }}" class="btn btn-outline-info btn-sm">
                                    <i class="fas fa-globe"></i> Manage Domains
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
