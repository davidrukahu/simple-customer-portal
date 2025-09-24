@extends('layouts.customer')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-end align-items-center mb-4">
                <a href="{{ route('customer.services.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Services
                </a>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Service Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Service Name</h6>
                                    <p class="text-primary h4">{{ $service->name }}</p>
                                    
                                    <h6>Billing Cycle</h6>
                                    <p>
                                        @php
                                            $cycleColors = [
                                                'one_time' => 'secondary',
                                                'monthly' => 'primary',
                                                'yearly' => 'success'
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $cycleColors[$service->billing_cycle] ?? 'secondary' }}">
                                            {{ ucfirst(str_replace('_', ' ', $service->billing_cycle)) }}
                                        </span>
                                    </p>
                                    
                                    <h6>Status</h6>
                                    <p>
                                        @php
                                            $statusColors = [
                                                'active' => 'success',
                                                'paused' => 'warning',
                                                'cancelled' => 'danger'
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $statusColors[$service->status] ?? 'secondary' }}">
                                            {{ ucfirst($service->status) }}
                                        </span>
                                    </p>
                                </div>
                                
                                <div class="col-md-6">
                                    <h6>Price</h6>
                                    <p class="text-success h4">{{ $service->currency }} {{ number_format($service->price, 2) }}</p>
                                    
                                    <h6>Next Invoice Date</h6>
                                    <p class="text-muted">
                                        @if($service->next_invoice_on)
                                            {{ $service->next_invoice_on->format('M d, Y') }}
                                            @if($service->next_invoice_on->isPast())
                                                <br><span class="badge bg-danger">Overdue</span>
                                            @elseif($service->days_until_next_invoice <= 7)
                                                <br><span class="badge bg-warning">Due Soon</span>
                                            @endif
                                        @else
                                            <span class="text-muted">Not scheduled</span>
                                        @endif
                                    </p>
                                    
                                    <h6>Created</h6>
                                    <p class="text-muted">{{ $service->created_at->format('M d, Y') }}</p>
                                </div>
                            </div>
                            
                            @if($service->description)
                                <hr>
                                <h6>Description</h6>
                                <div class="bg-light p-3 rounded">
                                    <p class="text-muted mb-0">{{ $service->description }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Service Summary</h5>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-6">
                                    <h4 class="text-primary">{{ $service->currency }}</h4>
                                    <small class="text-muted">Currency</small>
                                </div>
                                <div class="col-6">
                                    <h4 class="text-success">{{ number_format($service->price, 0) }}</h4>
                                    <small class="text-muted">Price</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card mt-3">
                        <div class="card-header">
                            <h5 class="mb-0">Quick Actions</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('customer.services.index') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-list"></i> All Services
                                </a>
                                <a href="{{ route('customer.dashboard') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-home"></i> Dashboard
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    @if($service->status === 'active' && $service->next_invoice_on && $service->days_until_next_invoice <= 7)
                        <div class="card mt-3">
                            <div class="card-header">
                                <h5 class="mb-0">Billing Notice</h5>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i>
                                    <strong>Invoice Due Soon</strong><br>
                                    Your next invoice for this service is due on {{ $service->next_invoice_on->format('M d, Y') }}.
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    @if($service->status === 'paused')
                        <div class="card mt-3">
                            <div class="card-header">
                                <h5 class="mb-0">Service Status</h5>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-warning">
                                    <i class="fas fa-pause"></i>
                                    <strong>Service Paused</strong><br>
                                    This service is currently paused. Contact support to reactivate.
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    @if($service->status === 'cancelled')
                        <div class="card mt-3">
                            <div class="card-header">
                                <h5 class="mb-0">Service Status</h5>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-danger">
                                    <i class="fas fa-times"></i>
                                    <strong>Service Cancelled</strong><br>
                                    This service has been cancelled.
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
