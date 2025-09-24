@extends('layouts.admin')

@section('title', 'Edit Domain')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Edit Domain</h1>
                <div>
                    <a href="{{ route('admin.domains.show', $domain) }}" class="btn btn-outline-primary me-2">
                        <i class="fas fa-eye"></i> View Domain
                    </a>
                    <a href="{{ route('admin.domains.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Domains
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.domains.update', $domain) }}">
                                @csrf
                                @method('PUT')
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="customer_id" class="form-label">Customer <span class="text-danger">*</span></label>
                                            <select class="form-select @error('customer_id') is-invalid @enderror" 
                                                    name="customer_id" id="customer_id" required>
                                                <option value="">Select Customer</option>
                                                @foreach($customers as $customer)
                                                    <option value="{{ $customer->id }}" 
                                                            {{ old('customer_id', $domain->customer_id) == $customer->id ? 'selected' : '' }}>
                                                        {{ $customer->name }} @if($customer->company) - {{ $customer->company }} @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('customer_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="fqdn" class="form-label">Domain Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('fqdn') is-invalid @enderror" 
                                                   name="fqdn" id="fqdn" value="{{ old('fqdn', $domain->fqdn) }}" 
                                                   placeholder="example.com" required>
                                            @error('fqdn')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="registrar" class="form-label">Registrar</label>
                                            <input type="text" class="form-control @error('registrar') is-invalid @enderror" 
                                                   name="registrar" id="registrar" value="{{ old('registrar', $domain->registrar) }}" 
                                                   placeholder="GoDaddy, Namecheap, etc.">
                                            @error('registrar')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                            <select class="form-select @error('status') is-invalid @enderror" 
                                                    name="status" id="status" required>
                                                <option value="active" {{ old('status', $domain->status) == 'active' ? 'selected' : '' }}>Active</option>
                                                <option value="expired" {{ old('status', $domain->status) == 'expired' ? 'selected' : '' }}>Expired</option>
                                                <option value="grace" {{ old('status', $domain->status) == 'grace' ? 'selected' : '' }}>Grace Period</option>
                                                <option value="redemption" {{ old('status', $domain->status) == 'redemption' ? 'selected' : '' }}>Redemption</option>
                                                <option value="transfer-pending" {{ old('status', $domain->status) == 'transfer-pending' ? 'selected' : '' }}>Transfer Pending</option>
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="registered_at" class="form-label">Registration Date <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control @error('registered_at') is-invalid @enderror" 
                                                   name="registered_at" id="registered_at" 
                                                   value="{{ old('registered_at', $domain->registered_at->format('Y-m-d')) }}" required>
                                            @error('registered_at')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="expires_at" class="form-label">Expiry Date <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control @error('expires_at') is-invalid @enderror" 
                                                   name="expires_at" id="expires_at" 
                                                   value="{{ old('expires_at', $domain->expires_at->format('Y-m-d')) }}" required>
                                            @error('expires_at')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="term_years" class="form-label">Term (Years) <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control @error('term_years') is-invalid @enderror" 
                                                   name="term_years" id="term_years" 
                                                   value="{{ old('term_years', $domain->term_years) }}" 
                                                   min="1" max="10" required>
                                            @error('term_years')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="price" class="form-label">Price <span class="text-danger">*</span></label>
                                            <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" 
                                                   name="price" id="price" value="{{ old('price', $domain->price) }}" 
                                                   min="0" required>
                                            @error('price')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="currency" class="form-label">Currency <span class="text-danger">*</span></label>
                                            <select class="form-select @error('currency') is-invalid @enderror" 
                                                    name="currency" id="currency" required>
                                                <option value="KES" {{ old('currency', $domain->currency) == 'KES' ? 'selected' : '' }}>KES</option>
                                                <option value="USD" {{ old('currency', $domain->currency) == 'USD' ? 'selected' : '' }}>USD</option>
                                                <option value="EUR" {{ old('currency', $domain->currency) == 'EUR' ? 'selected' : '' }}>EUR</option>
                                            </select>
                                            @error('currency')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <div class="form-check mt-4">
                                                <input class="form-check-input" type="checkbox" name="auto_renew" 
                                                       id="auto_renew" value="1" 
                                                       {{ old('auto_renew', $domain->auto_renew) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="auto_renew">
                                                    Auto Renew
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="service_notes" class="form-label">Service Notes</label>
                                    <textarea class="form-control @error('service_notes') is-invalid @enderror" 
                                              name="service_notes" id="service_notes" rows="3" 
                                              placeholder="Additional notes about this domain...">{{ old('service_notes', $domain->service_notes) }}</textarea>
                                    @error('service_notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="d-flex justify-content-end">
                                    <a href="{{ route('admin.domains.show', $domain) }}" class="btn btn-secondary me-2">Cancel</a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Update Domain
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Domain Information</h5>
                        </div>
                        <div class="card-body">
                            <p class="text-muted small">
                                <strong>Domain Name:</strong> Enter the full domain name including the extension (e.g., example.com)
                            </p>
                            <p class="text-muted small">
                                <strong>Registrar:</strong> The company where the domain is registered (optional)
                            </p>
                            <p class="text-muted small">
                                <strong>Status:</strong> Current status of the domain registration
                            </p>
                            <p class="text-muted small">
                                <strong>Auto Renew:</strong> Whether the domain should automatically renew
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
