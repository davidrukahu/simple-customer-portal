@extends('layouts.customer')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-end align-items-center mb-4">
                <a href="{{ route('customer.domains.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Domains
                </a>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Domain Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Domain Name</h6>
                                    <p class="text-primary h4">{{ $domain->fqdn }}</p>
                                    
                                    <h6>Registrar</h6>
                                    <p class="text-muted">{{ $domain->registrar ?: 'Not specified' }}</p>
                                    
                                    <h6>Status</h6>
                                    <p>
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
                                    </p>
                                </div>
                                
                                <div class="col-md-6">
                                    <h6>Registration Date</h6>
                                    <p class="text-muted">{{ $domain->registered_at->format('M d, Y') }}</p>
                                    
                                    <h6>Expiry Date</h6>
                                    <p class="text-muted">
                                        {{ $domain->expires_at->format('M d, Y') }}
                                        @if($domain->expires_at->isPast())
                                            <br><span class="badge bg-danger">Expired {{ $domain->expires_at->diffForHumans() }}</span>
                                        @elseif($domain->days_until_expiry <= 30)
                                            <br><span class="badge bg-warning">Expires in {{ $domain->days_until_expiry }} days</span>
                                        @else
                                            <br><span class="text-success">Expires in {{ $domain->days_until_expiry }} days</span>
                                        @endif
                                    </p>
                                    
                                    <h6>Term</h6>
                                    <p class="text-muted">{{ $domain->term_years }} year(s)</p>
                                    
                                    <h6>Auto Renew</h6>
                                    <p>
                                        @if($domain->auto_renew)
                                            <span class="badge bg-success">Yes</span>
                                        @else
                                            <span class="badge bg-secondary">No</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            
                            @if($domain->service_notes)
                                <hr>
                                <h6>Service Notes</h6>
                                <div class="bg-light p-3 rounded">
                                    <p class="text-muted mb-0">{{ $domain->service_notes }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Domain Statistics</h5>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-6">
                                    <h4 class="text-primary">{{ $domain->term_years }}</h4>
                                    <small class="text-muted">Years</small>
                                </div>
                                <div class="col-6">
                                    <h4 class="text-success">{{ $domain->currency }} {{ number_format($domain->price, 0) }}</h4>
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
                                <a href="{{ route('customer.domains.index') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-list"></i> All Domains
                                </a>
                                <a href="{{ route('customer.dashboard') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-home"></i> Dashboard
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    @if($domain->days_until_expiry <= 30 && $domain->status === 'active')
                        <div class="card mt-3">
                            <div class="card-header">
                                <h5 class="mb-0">Renewal Notice</h5>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <strong>Renewal Due Soon</strong><br>
                                    This domain expires in {{ $domain->days_until_expiry }} days.
                                    @if($domain->auto_renew)
                                        <br><small>Auto-renewal is enabled.</small>
                                    @else
                                        <br><small>Please contact support to renew.</small>
                                    @endif
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
