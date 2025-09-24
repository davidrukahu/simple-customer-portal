@extends('layouts.admin')

@section('page-title', 'Edit Service')
@section('page-subtitle', 'Modify service details and billing information')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Edit Service: {{ $service->name }}</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.services.update', $service) }}">
                    @csrf
                    @method('PUT')
                    
                    <!-- Customer Selection -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="customer_id" class="form-label">Customer <span class="text-danger">*</span></label>
                            <select class="form-select @error('customer_id') is-invalid @enderror" id="customer_id" name="customer_id" required>
                                <option value="">Select a customer</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ old('customer_id', $service->customer_id) == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->name }}
                                        @if($customer->company)
                                            ({{ $customer->company }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('customer_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="active" {{ old('status', $service->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="paused" {{ old('status', $service->status) == 'paused' ? 'selected' : '' }}>Paused</option>
                                <option value="cancelled" {{ old('status', $service->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Service Details -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Service Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $service->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="currency" class="form-label">Currency <span class="text-danger">*</span></label>
                            <select class="form-select @error('currency') is-invalid @enderror" id="currency" name="currency" required>
                                <option value="KES" {{ old('currency', $service->currency) == 'KES' ? 'selected' : '' }}>KES</option>
                                <option value="USD" {{ old('currency', $service->currency) == 'USD' ? 'selected' : '' }}>USD</option>
                                <option value="EUR" {{ old('currency', $service->currency) == 'EUR' ? 'selected' : '' }}>EUR</option>
                            </select>
                            @error('currency')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3">{{ old('description', $service->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Billing Information -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="billing_cycle" class="form-label">Billing Cycle <span class="text-danger">*</span></label>
                            <select class="form-select @error('billing_cycle') is-invalid @enderror" id="billing_cycle" name="billing_cycle" required>
                                <option value="one_time" {{ old('billing_cycle', $service->billing_cycle) == 'one_time' ? 'selected' : '' }}>One Time</option>
                                <option value="monthly" {{ old('billing_cycle', $service->billing_cycle) == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                <option value="yearly" {{ old('billing_cycle', $service->billing_cycle) == 'yearly' ? 'selected' : '' }}>Yearly</option>
                            </select>
                            @error('billing_cycle')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="price" class="form-label">Price <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                   id="price" name="price" step="0.01" min="0" value="{{ old('price', $service->price) }}" required>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Next Invoice Date -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="next_invoice_on" class="form-label">Next Invoice Date</label>
                            <input type="date" class="form-control @error('next_invoice_on') is-invalid @enderror" 
                                   id="next_invoice_on" name="next_invoice_on" 
                                   value="{{ old('next_invoice_on', $service->next_invoice_on ? $service->next_invoice_on->format('Y-m-d') : '') }}">
                            @error('next_invoice_on')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                Leave empty for one-time services or if not scheduled yet.
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="d-flex justify-content-between">
                        <div>
                            <a href="{{ route('admin.services.show', $service) }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i>Cancel
                            </a>
                            <a href="{{ route('admin.services.index') }}" class="btn btn-outline-secondary ms-2">
                                <i class="bi bi-list me-1"></i>Back to List
                            </a>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i>Update Service
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Service Summary -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Current Service</h5>
            </div>
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-6"><strong>Name:</strong></div>
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

        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.services.show', $service) }}" class="btn btn-outline-primary">
                        <i class="bi bi-eye me-2"></i>View Service
                    </a>
                    <a href="{{ route('admin.customers.show', $service->customer) }}" class="btn btn-outline-secondary">
                        <i class="bi bi-person me-2"></i>View Customer
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-set next invoice date based on billing cycle
    document.getElementById('billing_cycle').addEventListener('change', function() {
        const billingCycle = this.value;
        const nextInvoiceInput = document.getElementById('next_invoice_on');
        
        if (billingCycle === 'one_time') {
            nextInvoiceInput.value = '';
            nextInvoiceInput.disabled = true;
        } else {
            nextInvoiceInput.disabled = false;
            if (!nextInvoiceInput.value) {
                const today = new Date();
                let nextDate = new Date(today);
                
                if (billingCycle === 'monthly') {
                    nextDate.setMonth(today.getMonth() + 1);
                } else if (billingCycle === 'yearly') {
                    nextDate.setFullYear(today.getFullYear() + 1);
                }
                
                nextInvoiceInput.value = nextDate.toISOString().split('T')[0];
            }
        }
    });
    
    // Initialize on page load
    document.getElementById('billing_cycle').dispatchEvent(new Event('change'));
});
</script>
@endpush
@endsection
