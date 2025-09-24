@extends('layouts.admin')

@section('page-title', 'Edit Invoice')
@section('page-subtitle', 'Modify invoice details and items')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Invoice {{ $invoice->number }}</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.invoices.update', $invoice) }}">
                    @csrf
                    @method('PUT')
                    
                    <!-- Customer Selection -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="customer_id" class="form-label">Customer <span class="text-danger">*</span></label>
                            <select class="form-select @error('customer_id') is-invalid @enderror" id="customer_id" name="customer_id" required>
                                <option value="">Select a customer</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ old('customer_id', $invoice->customer_id) == $customer->id ? 'selected' : '' }}>
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
                                <option value="draft" {{ old('status', $invoice->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="sent" {{ old('status', $invoice->status) == 'sent' ? 'selected' : '' }}>Sent</option>
                                <option value="paid" {{ old('status', $invoice->status) == 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="overdue" {{ old('status', $invoice->status) == 'overdue' ? 'selected' : '' }}>Overdue</option>
                                <option value="void" {{ old('status', $invoice->status) == 'void' ? 'selected' : '' }}>Void</option>
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
                                   id="issue_date" name="issue_date" value="{{ old('issue_date', $invoice->issue_date->format('Y-m-d')) }}" required>
                            @error('issue_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="due_date" class="form-label">Due Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('due_date') is-invalid @enderror" 
                                   id="due_date" name="due_date" value="{{ old('due_date', $invoice->due_date->format('Y-m-d')) }}" required>
                            @error('due_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Currency -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="currency" class="form-label">Currency <span class="text-danger">*</span></label>
                            <select class="form-select @error('currency') is-invalid @enderror" id="currency" name="currency" required>
                                <option value="KES" {{ old('currency', $invoice->currency) == 'KES' ? 'selected' : '' }}>KES</option>
                                <option value="USD" {{ old('currency', $invoice->currency) == 'USD' ? 'selected' : '' }}>USD</option>
                                <option value="EUR" {{ old('currency', $invoice->currency) == 'EUR' ? 'selected' : '' }}>EUR</option>
                            </select>
                            @error('currency')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Totals -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="subtotal" class="form-label">Subtotal <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('subtotal') is-invalid @enderror" 
                                   id="subtotal" name="subtotal" step="0.01" min="0" value="{{ old('subtotal', $invoice->subtotal) }}" required>
                            @error('subtotal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="tax_total" class="form-label">Tax Total</label>
                            <input type="number" class="form-control @error('tax_total') is-invalid @enderror" 
                                   id="tax_total" name="tax_total" step="0.01" min="0" value="{{ old('tax_total', $invoice->tax_total) }}">
                            @error('tax_total')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="total" class="form-label">Total <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('total') is-invalid @enderror" 
                                   id="total" name="total" step="0.01" min="0" value="{{ old('total', $invoice->total) }}" required>
                            @error('total')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" 
                                  id="notes" name="notes" rows="3">{{ old('notes', $invoice->notes) }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Actions -->
                    <div class="d-flex justify-content-between">
                        <div>
                            <a href="{{ route('admin.invoices.show', $invoice) }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i>Cancel
                            </a>
                            <a href="{{ route('admin.invoices.index') }}" class="btn btn-outline-secondary ms-2">
                                <i class="bi bi-list me-1"></i>Back to List
                            </a>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i>Update Invoice
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Invoice Items (Read-only for now) -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Invoice Items</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Description</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Unit Price</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($invoice->items as $item)
                            <tr>
                                <td>{{ $item->description }}</td>
                                <td class="text-center">{{ number_format($item->qty, 2) }}</td>
                                <td class="text-end">{{ $invoice->currency }} {{ number_format($item->unit_price, 2) }}</td>
                                <td class="text-end">{{ $invoice->currency }} {{ number_format($item->line_total, 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">No items found</td>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3">Subtotal:</th>
                                <th class="text-end">{{ $invoice->currency }} {{ number_format($invoice->subtotal, 2) }}</th>
                            </tr>
                            @if($invoice->tax_total > 0)
                            <tr>
                                <th colspan="3">Tax:</th>
                                <th class="text-end">{{ $invoice->currency }} {{ number_format($invoice->tax_total, 2) }}</th>
                            </tr>
                            @endif
                            <tr class="table-primary">
                                <th colspan="3">Total:</th>
                                <th class="text-end">{{ $invoice->currency }} {{ number_format($invoice->total, 2) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Note:</strong> Invoice items cannot be edited here. To modify items, you would need to delete and recreate the invoice.
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Invoice Summary -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Invoice Summary</h5>
            </div>
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-6"><strong>Invoice Number:</strong></div>
                    <div class="col-6">{{ $invoice->number }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-6"><strong>Status:</strong></div>
                    <div class="col-6">
                        @switch($invoice->status)
                            @case('draft')
                                <span class="badge bg-secondary">Draft</span>
                                @break
                            @case('sent')
                                <span class="badge bg-primary">Sent</span>
                                @break
                            @case('paid')
                                <span class="badge bg-success">Paid</span>
                                @break
                            @case('overdue')
                                <span class="badge bg-danger">Overdue</span>
                                @break
                            @case('void')
                                <span class="badge bg-dark">Void</span>
                                @break
                        @endswitch
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-6"><strong>Customer:</strong></div>
                    <div class="col-6">{{ $invoice->customer->name }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-6"><strong>Issue Date:</strong></div>
                    <div class="col-6">{{ $invoice->issue_date->format('M d, Y') }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-6"><strong>Due Date:</strong></div>
                    <div class="col-6">{{ $invoice->due_date->format('M d, Y') }}</div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-6"><strong>Total:</strong></div>
                    <div class="col-6"><strong>{{ $invoice->currency }} {{ number_format($invoice->total, 2) }}</strong></div>
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
                    <a href="{{ route('admin.invoices.show', $invoice) }}" class="btn btn-outline-primary">
                        <i class="bi bi-eye me-2"></i>View Invoice
                    </a>
                    <a href="{{ route('admin.invoices.download', $invoice) }}" class="btn btn-outline-secondary">
                        <i class="bi bi-download me-2"></i>Download PDF
                    </a>
                    @if($invoice->status !== 'paid')
                        <form method="POST" action="{{ route('admin.invoices.mark-paid', $invoice) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success w-100">
                                <i class="bi bi-check-circle me-2"></i>Mark as Paid
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-calculate total when tax changes
    document.getElementById('tax_total').addEventListener('input', function() {
        const subtotal = parseFloat(document.getElementById('subtotal').value) || 0;
        const taxTotal = parseFloat(this.value) || 0;
        const total = subtotal + taxTotal;
        document.getElementById('total').value = total.toFixed(2);
    });
    
    // Auto-calculate total when subtotal changes
    document.getElementById('subtotal').addEventListener('input', function() {
        const subtotal = parseFloat(this.value) || 0;
        const taxTotal = parseFloat(document.getElementById('tax_total').value) || 0;
        const total = subtotal + taxTotal;
        document.getElementById('total').value = total.toFixed(2);
    });
});
</script>
@endpush
@endsection
