@extends('layouts.admin')

@section('page-title', 'Invoice Details')
@section('page-subtitle', 'View and manage invoice information')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <!-- Invoice Details -->
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Invoice {{ $invoice->number }}</h5>
                    <div class="d-flex gap-2">
                        @if($invoice->status !== 'paid')
                            <form method="POST" action="{{ route('admin.invoices.mark-paid', $invoice) }}" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="bi bi-check-circle me-1"></i>Mark as Paid
                                </button>
                            </form>
                        @endif
                        <a href="{{ route('admin.invoices.download', $invoice) }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-download me-1"></i>Download PDF
                        </a>
                        <a href="{{ route('admin.invoices.edit', $invoice) }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-pencil me-1"></i>Edit
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Invoice Status -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Status:</strong>
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
                    <div class="col-md-6">
                        <strong>Total Amount:</strong>
                        <span class="h5 text-primary">{{ $invoice->currency }} {{ number_format($invoice->total, 2) }}</span>
                    </div>
                </div>

                <!-- Invoice Dates -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Issue Date:</strong> {{ $invoice->issue_date->format('M d, Y') }}
                    </div>
                    <div class="col-md-6">
                        <strong>Due Date:</strong> 
                        {{ $invoice->due_date->format('M d, Y') }}
                        @if($invoice->status === 'overdue')
                            <span class="text-danger">({{ $invoice->days_overdue }} days overdue)</span>
                        @elseif($invoice->status !== 'paid' && $invoice->days_until_due <= 7)
                            <span class="text-warning">(Due in {{ $invoice->days_until_due }} days)</span>
                        @endif
                    </div>
                </div>

                <!-- Customer Information -->
                <div class="row mb-3">
                    <div class="col-12">
                        <strong>Customer:</strong>
                        <a href="{{ route('admin.customers.show', $invoice->customer) }}" class="text-decoration-none">
                            {{ $invoice->customer->name }}
                            @if($invoice->customer->company)
                                ({{ $invoice->customer->company }})
                            @endif
                        </a>
                        <br>
                        <small class="text-muted">{{ $invoice->customer->email }}</small>
                    </div>
                </div>

                <!-- Notes -->
                @if($invoice->notes)
                <div class="row mb-3">
                    <div class="col-12">
                        <strong>Notes:</strong>
                        <p class="text-muted">{{ $invoice->notes }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Invoice Items -->
        <div class="card mb-4">
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
            </div>
        </div>

        <!-- Payments -->
        @if($invoice->payments->count() > 0)
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Payments</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Reference</th>
                                <th>Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($invoice->payments as $payment)
                            <tr>
                                <td>{{ $payment->paid_on->format('M d, Y') }}</td>
                                <td>{{ $payment->currency }} {{ number_format($payment->amount, 2) }}</td>
                                <td>
                                    <span class="badge bg-info">{{ ucfirst($payment->method) }}</span>
                                </td>
                                <td>{{ $payment->reference ?? '-' }}</td>
                                <td>{{ $payment->notes ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="col-lg-4">
        <!-- Quick Actions -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.invoices.edit', $invoice) }}" class="btn btn-outline-primary">
                        <i class="bi bi-pencil me-2"></i>Edit Invoice
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
                    <a href="{{ route('admin.invoices.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Back to Invoices
                    </a>
                </div>
            </div>
        </div>

        <!-- Invoice Summary -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Summary</h5>
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
                    <div class="col-6"><strong>Issue Date:</strong></div>
                    <div class="col-6">{{ $invoice->issue_date->format('M d, Y') }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-6"><strong>Due Date:</strong></div>
                    <div class="col-6">{{ $invoice->due_date->format('M d, Y') }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-6"><strong>Subtotal:</strong></div>
                    <div class="col-6">{{ $invoice->currency }} {{ number_format($invoice->subtotal, 2) }}</div>
                </div>
                @if($invoice->tax_total > 0)
                <div class="row mb-2">
                    <div class="col-6"><strong>Tax:</strong></div>
                    <div class="col-6">{{ $invoice->currency }} {{ number_format($invoice->tax_total, 2) }}</div>
                </div>
                @endif
                <hr>
                <div class="row">
                    <div class="col-6"><strong>Total:</strong></div>
                    <div class="col-6"><strong>{{ $invoice->currency }} {{ number_format($invoice->total, 2) }}</strong></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
