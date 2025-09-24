@extends('layouts.admin')

@section('title', 'Domain Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Domain Details</h1>
                <div>
                    <a href="{{ route('admin.domains.edit', $domain) }}" class="btn btn-outline-primary me-2">
                        <i class="fas fa-edit"></i> Edit Domain
                    </a>
                    <a href="{{ route('admin.domains.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Domains
                    </a>
                </div>
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
                                    <p class="text-muted">{{ $domain->fqdn }}</p>
                                    
                                    <h6>Customer</h6>
                                    <p>
                                        <a href="{{ route('admin.customers.show', $domain->customer) }}">
                                            {{ $domain->customer->name }}
                                        </a>
                                        @if($domain->customer->company)
                                            <br><small class="text-muted">{{ $domain->customer->company }}</small>
                                        @endif
                                    </p>
                                    
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
                                    
                                    <h6>Price</h6>
                                    <p class="text-muted">{{ $domain->currency }} {{ number_format($domain->price, 2) }}</p>
                                    
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
                                <p class="text-muted">{{ $domain->service_notes }}</p>
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
                                <a href="{{ route('admin.domains.edit', $domain) }}" class="btn btn-primary">
                                    <i class="fas fa-edit"></i> Edit Domain
                                </a>
                                <a href="{{ route('admin.invoices.create') }}?domain={{ $domain->id }}" class="btn btn-outline-primary">
                                    <i class="fas fa-file-invoice"></i> Create Invoice
                                </a>
                                <form method="POST" action="{{ route('admin.domains.destroy', $domain) }}" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger w-100" 
                                            onclick="return confirm('Are you sure you want to delete this domain?')">
                                        <i class="fas fa-trash"></i> Delete Domain
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card mt-3">
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
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
