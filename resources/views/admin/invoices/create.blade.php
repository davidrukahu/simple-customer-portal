@extends('layouts.admin')

@section('page-title', 'Create Invoice')
@section('page-subtitle', 'Create a new invoice for a customer')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Invoice Details</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.invoices.store') }}">
                    @csrf
                    
                    <!-- Customer Selection -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="customer_id" class="form-label">Customer <span class="text-danger">*</span></label>
                            <select class="form-select @error('customer_id') is-invalid @enderror" id="customer_id" name="customer_id" required>
                                <option value="">Select a customer</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
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
                        <div class="col-md-3">
                            <label for="currency" class="form-label">Currency <span class="text-danger">*</span></label>
                            <select class="form-select @error('currency') is-invalid @enderror" id="currency" name="currency" required>
                                <option value="KES" {{ old('currency', 'KES') == 'KES' ? 'selected' : '' }}>KES</option>
                                <option value="USD" {{ old('currency') == 'USD' ? 'selected' : '' }}>USD</option>
                                <option value="EUR" {{ old('currency') == 'EUR' ? 'selected' : '' }}>EUR</option>
                            </select>
                            @error('currency')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="draft" {{ old('status', 'sent') == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="sent" {{ old('status', 'sent') == 'sent' ? 'selected' : '' }}>Sent</option>
                                <option value="paid" {{ old('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="overdue" {{ old('status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                                <option value="void" {{ old('status') == 'void' ? 'selected' : '' }}>Void</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Invoice Dates -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="issue_date" class="form-label">Issue Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('issue_date') is-invalid @enderror" 
                                   id="issue_date" name="issue_date" value="{{ old('issue_date', date('Y-m-d')) }}" required>
                            @error('issue_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="due_date" class="form-label">Due Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('due_date') is-invalid @enderror" 
                                   id="due_date" name="due_date" value="{{ old('due_date', date('Y-m-d', strtotime('+30 days'))) }}" required>
                            @error('due_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Invoice Items -->
                    <div class="mb-3">
                        <label class="form-label">Invoice Items <span class="text-danger">*</span></label>
                        <div id="invoice-items">
                            <div class="invoice-item row mb-2">
                                <div class="col-md-5">
                                    <input type="text" class="form-control" name="items[0][description]" placeholder="Description" required>
                                </div>
                                <div class="col-md-2">
                                    <input type="number" class="form-control qty-input" name="items[0][qty]" placeholder="Qty" step="0.01" min="0.01" required>
                                </div>
                                <div class="col-md-2">
                                    <input type="number" class="form-control unit-price-input" name="items[0][unit_price]" placeholder="Unit Price" step="0.01" min="0" required>
                                </div>
                                <div class="col-md-2">
                                    <input type="number" class="form-control line-total-input" name="items[0][line_total]" placeholder="Total" step="0.01" min="0" value="{{ old('items.0.line_total', '0.00') }}" readonly>
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-outline-danger btn-sm remove-item" style="display: none;">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-outline-primary btn-sm" id="add-item">
                            <i class="bi bi-plus me-1"></i>Add Item
                        </button>
                    </div>

                    <!-- Totals -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="subtotal" class="form-label">Subtotal <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('subtotal') is-invalid @enderror" 
                                   id="subtotal" name="subtotal" step="0.01" min="0" value="{{ old('subtotal', '0.00') }}" readonly>
                            @error('subtotal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="tax_total" class="form-label">Tax Total</label>
                            <input type="number" class="form-control @error('tax_total') is-invalid @enderror" 
                                   id="tax_total" name="tax_total" step="0.01" min="0" value="{{ old('tax_total', '0.00') }}">
                            @error('tax_total')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="total" class="form-label">Total <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('total') is-invalid @enderror" 
                                   id="total" name="total" step="0.01" min="0" value="{{ old('total', '0.00') }}" readonly>
                            @error('total')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" 
                                  id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Actions -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.invoices.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i>Create Invoice
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Quick Add from Domains/Services -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Quick Add Items</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Add Domain Renewal</label>
                    <select class="form-select" id="domain-select">
                        <option value="">Select a domain</option>
                        @foreach($domains as $domain)
                            <option value="{{ $domain->id }}" data-price="{{ $domain->price }}" data-description="Domain renewal: {{ $domain->fqdn }}">
                                {{ $domain->fqdn }} - {{ $domain->customer->name }}
                            </option>
                        @endforeach
                    </select>
                    <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="add-domain">
                        <i class="bi bi-plus me-1"></i>Add Domain
                    </button>
                </div>

                <div class="mb-3">
                    <label class="form-label">Add Service</label>
                    <select class="form-select" id="service-select">
                        <option value="">Select a service</option>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}" data-price="{{ $service->price }}" data-description="{{ $service->name }}">
                                {{ $service->name }} - {{ $service->customer->name }}
                            </option>
                        @endforeach
                    </select>
                    <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="add-service">
                        <i class="bi bi-plus me-1"></i>Add Service
                    </button>
                </div>
            </div>
        </div>

        <!-- Help -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Help</h5>
            </div>
            <div class="card-body">
                <h6>Creating Invoices</h6>
                <ul class="small text-muted">
                    <li>Select a customer from the dropdown</li>
                    <li>Set issue and due dates</li>
                    <li>Add items manually or use quick add</li>
                    <li>Tax is optional and will be added to subtotal</li>
                    <li>Invoice number will be generated automatically</li>
                </ul>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let itemIndex = 1;
    
    // Add item functionality
    document.getElementById('add-item').addEventListener('click', function() {
        const itemsContainer = document.getElementById('invoice-items');
        const newItem = document.querySelector('.invoice-item').cloneNode(true);
        
        // Update input names and clear values
        newItem.querySelectorAll('input').forEach(input => {
            const name = input.name.replace('[0]', '[' + itemIndex + ']');
            input.name = name;
            input.value = '';
        });
        
        // Show remove button
        newItem.querySelector('.remove-item').style.display = 'block';
        
        itemsContainer.appendChild(newItem);
        itemIndex++;
        
        // Add remove functionality
        newItem.querySelector('.remove-item').addEventListener('click', function() {
            newItem.remove();
            calculateTotals();
        });
        
        // Add calculation functionality
        addCalculationListeners(newItem);
    });
    
    // Add calculation listeners to existing items
    document.querySelectorAll('.invoice-item').forEach(item => {
        addCalculationListeners(item);
    });
    
    // Tax calculation
    document.getElementById('tax_total').addEventListener('input', calculateTotals);
    
    function addCalculationListeners(item) {
        const qtyInput = item.querySelector('.qty-input');
        const unitPriceInput = item.querySelector('.unit-price-input');
        const lineTotalInput = item.querySelector('.line-total-input');
        
        [qtyInput, unitPriceInput].forEach(input => {
            input.addEventListener('input', function() {
                const qty = parseFloat(qtyInput.value) || 0;
                const unitPrice = parseFloat(unitPriceInput.value) || 0;
                lineTotalInput.value = (qty * unitPrice).toFixed(2);
                calculateTotals();
            });
        });
    }
    
    function calculateTotals() {
        let subtotal = 0;
        document.querySelectorAll('.line-total-input').forEach(input => {
            subtotal += parseFloat(input.value) || 0;
        });
        
        const taxTotal = parseFloat(document.getElementById('tax_total').value) || 0;
        const total = subtotal + taxTotal;
        
        document.getElementById('subtotal').value = subtotal.toFixed(2);
        document.getElementById('total').value = total.toFixed(2);
    }
    
    // Quick add functionality
    document.getElementById('add-domain').addEventListener('click', function() {
        const select = document.getElementById('domain-select');
        const option = select.options[select.selectedIndex];
        if (option.value) {
            addQuickItem(option.dataset.description, option.dataset.price);
            select.selectedIndex = 0;
        }
    });
    
    document.getElementById('add-service').addEventListener('click', function() {
        const select = document.getElementById('service-select');
        const option = select.options[select.selectedIndex];
        if (option.value) {
            addQuickItem(option.dataset.description, option.dataset.price);
            select.selectedIndex = 0;
        }
    });
    
    function addQuickItem(description, price) {
        const itemsContainer = document.getElementById('invoice-items');
        const newItem = document.querySelector('.invoice-item').cloneNode(true);
        
        // Update input names and set values
        newItem.querySelectorAll('input').forEach(input => {
            const name = input.name.replace('[0]', '[' + itemIndex + ']');
            input.name = name;
        });
        
        newItem.querySelector('input[name*="[description]"]').value = description;
        newItem.querySelector('input[name*="[qty]"]').value = '1';
        newItem.querySelector('input[name*="[unit_price]"]').value = price;
        newItem.querySelector('input[name*="[line_total]"]').value = price;
        
        // Show remove button
        newItem.querySelector('.remove-item').style.display = 'block';
        
        itemsContainer.appendChild(newItem);
        itemIndex++;
        
        // Add remove functionality
        newItem.querySelector('.remove-item').addEventListener('click', function() {
            newItem.remove();
            calculateTotals();
        });
        
        // Add calculation functionality
        addCalculationListeners(newItem);
        
        calculateTotals();
    }
    
    // Initial calculation
    calculateTotals();
});
</script>
@endpush
@endsection
