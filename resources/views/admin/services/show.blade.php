@extends('layouts.admin')

@section('page-title', 'Service Details')
@section('page-subtitle', 'View and manage service information')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <!-- Service Details -->
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ $service->name }}</h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.services.edit', $service) }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-pencil me-1"></i>Edit
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Service Status -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Status:</strong>
                        @switch($service->status)
                            @case('active')
                                <span class="badge bg-success">Active</span>
                                @break
                            @case('paused')
                                <span class="badge bg-warning">Paused</span>
                                @break
                            @case('cancelled')
                                <span class="badge bg-danger">Cancelled</span>
                                @break
                        @endswitch
                    </div>
                    <div class="col-md-6">
                        <strong>Price:</strong>
                        <span class="h5 text-primary">{{ $service->currency }} {{ number_format($service->price, 2) }}</span>
                    </div>
                </div>

                <!-- Service Details -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Billing Cycle:</strong>
                        <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $service->billing_cycle)) }}</span>
                    </div>
                    <div class="col-md-6">
                        <strong>Next Invoice:</strong>
                        @if($service->next_invoice_on)
                            {{ $service->next_invoice_on->format('M d, Y') }}
                            @if($service->status === 'active')
                                @if($service->days_until_next_invoice <= 7)
                                    <br><span class="badge bg-warning">Due Soon</span>
                                @elseif($service->days_since_invoice_due > 0)
                                    <br><span class="badge bg-danger">Overdue</span>
                                @endif
                            @endif
                        @else
                            <span class="text-muted">Not scheduled</span>
                        @endif
                    </div>
                </div>

                <!-- Customer Information -->
                <div class="row mb-3">
                    <div class="col-12">
                        <strong>Customer:</strong>
                        <a href="{{ route('admin.customers.show', $service->customer) }}" class="text-decoration-none">
                            {{ $service->customer->name }}
                            @if($service->customer->company)
                                ({{ $service->customer->company }})
                            @endif
                        </a>
                        <br>
                        <small class="text-muted">{{ $service->customer->email }}</small>
                    </div>
                </div>

                <!-- Description -->
                @if($service->description)
                <div class="row mb-3">
                    <div class="col-12">
                        <strong>Description:</strong>
                        <p class="text-muted">{{ $service->description }}</p>
                    </div>
                </div>
                @endif

                <!-- Service Dates -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Created:</strong> {{ $service->created_at->format('M d, Y') }}
                    </div>
                    <div class="col-md-6">
                        <strong>Last Updated:</strong> {{ $service->updated_at->format('M d, Y') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Service History (if we had invoices) -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Service Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Billing Information</h6>
                        <ul class="list-unstyled">
                            <li><strong>Cycle:</strong> {{ ucfirst(str_replace('_', ' ', $service->billing_cycle)) }}</li>
                            <li><strong>Price:</strong> {{ $service->currency }} {{ number_format($service->price, 2) }}</li>
                            <li><strong>Status:</strong> 
                                @switch($service->status)
                                    @case('active')
                                        <span class="badge bg-success">Active</span>
                                        @break
                                    @case('paused')
                                        <span class="badge bg-warning">Paused</span>
                                        @break
                                    @case('cancelled')
                                        <span class="badge bg-danger">Cancelled</span>
                                        @break
                                @endswitch
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6>Next Steps</h6>
                        <ul class="list-unstyled">
                            @if($service->next_invoice_on)
                                <li><strong>Next Invoice:</strong> {{ $service->next_invoice_on->format('M d, Y') }}</li>
                                @if($service->status === 'active')
                                    @if($service->days_until_next_invoice <= 7)
                                        <li><span class="text-warning">⚠️ Invoice due soon</span></li>
                                    @elseif($service->days_since_invoice_due > 0)
                                        <li><span class="text-danger">🚨 Invoice overdue</span></li>
                                    @else
                                        <li><span class="text-success">✅ On track</span></li>
                                    @endif
                                @endif
                            @else
                                <li><span class="text-muted">No invoice scheduled</span></li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Quick Actions -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.services.edit', $service) }}" class="btn btn-outline-primary">
                        <i class="bi bi-pencil me-2"></i>Edit Service
                    </a>
                    <a href="{{ route('admin.customers.show', $service->customer) }}" class="btn btn-outline-secondary">
                        <i class="bi bi-person me-2"></i>View Customer
                    </a>
                    <a href="{{ route('admin.services.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Back to Services
                    </a>
                </div>
            </div>
        </div>

        <!-- Service Summary -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Summary</h5>
            </div>
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-6"><strong>Service:</strong></div>
                    <div class="col-6">{{ $service->name }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-6"><strong>Status:</strong></div>
                    <div class="col-6">
                        @switch($service->status)
                            @case('active')
                                <span class="badge bg-success">Active</span>
                                @break
                            @case('paused')
                                <span class="badge bg-warning">Paused</span>
                                @break
                            @case('cancelled')
                                <span class="badge bg-danger">Cancelled</span>
                                @break
                        @endswitch
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-6"><strong>Customer:</strong></div>
                    <div class="col-6">{{ $service->customer->name }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-6"><strong>Billing:</strong></div>
                    <div class="col-6">{{ ucfirst(str_replace('_', ' ', $service->billing_cycle)) }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-6"><strong>Price:</strong></div>
                    <div class="col-6">{{ $service->currency }} {{ number_format($service->price, 2) }}</div>
                </div>
                @if($service->next_invoice_on)
                <div class="row mb-2">
                    <div class="col-6"><strong>Next Invoice:</strong></div>
                    <div class="col-6">{{ $service->next_invoice_on->format('M d, Y') }}</div>
                </div>
                @endif
                <hr>
                <div class="row">
                    <div class="col-6"><strong>Created:</strong></div>
                    <div class="col-6">{{ $service->created_at->format('M d, Y') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
