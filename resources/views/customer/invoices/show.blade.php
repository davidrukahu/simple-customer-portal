@extends('layouts.customer')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-end align-items-center mb-4">
                <div>
                    <a href="{{ route('customer.invoices.download', $invoice) }}" class="btn btn-outline-success me-2">
                        <i class="fas fa-download"></i> Download PDF
                    </a>
                    <a href="{{ route('customer.invoices.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Invoices
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Invoice Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Invoice Number</h6>
                                    <p class="text-muted">{{ $invoice->number }}</p>
                                    
                                    <h6>Issue Date</h6>
                                    <p class="text-muted">{{ $invoice->issue_date->format('M d, Y') }}</p>
                                    
                                    <h6>Due Date</h6>
                                    <p class="text-muted">
                                        {{ $invoice->due_date->format('M d, Y') }}
                                        @if($invoice->due_date->isPast() && $invoice->status !== 'paid')
                                            <br><span class="badge bg-danger">Overdue</span>
                                        @elseif($invoice->days_until_due <= 7 && $invoice->status !== 'paid')
                                            <br><span class="badge bg-warning">Due Soon</span>
                                        @endif
                                    </p>
                                </div>
                                
                                <div class="col-md-6">
                                    <h6>Status</h6>
                                    <p>
                                        @php
                                            $statusColors = [
                                                'draft' => 'secondary',
                                                'sent' => 'primary',
                                                'paid' => 'success',
                                                'overdue' => 'danger',
                                                'void' => 'dark'
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $statusColors[$invoice->status] ?? 'secondary' }}">
                                            {{ ucfirst($invoice->status) }}
                                        </span>
                                    </p>
                                    
                                    <h6>Total Amount</h6>
                                    <p class="text-primary h4">{{ $invoice->currency }} {{ number_format($invoice->total, 2) }}</p>
                                    
                                    @if($invoice->notes)
                                        <h6>Notes</h6>
                                        <p class="text-muted">{{ $invoice->notes }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Invoice Items -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="mb-0">Invoice Items</h5>
                        </div>
                        <div class="card-body">
                            @if($invoice->items->count() > 0)
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Description</th>
                                                <th>Quantity</th>
                                                <th>Unit Price</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($invoice->items as $item)
                                                <tr>
                                                    <td>{{ $item->description }}</td>
                                                    <td>{{ number_format($item->qty, 2) }}</td>
                                                    <td>{{ $invoice->currency }} {{ number_format($item->unit_price, 2) }}</td>
                                                    <td>{{ $invoice->currency }} {{ number_format($item->line_total, 2) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="3">Subtotal:</th>
                                                <th>{{ $invoice->currency }} {{ number_format($invoice->subtotal, 2) }}</th>
                                            </tr>
                                            @if($invoice->tax_total > 0)
                                                <tr>
                                                    <th colspan="3">Tax:</th>
                                                    <th>{{ $invoice->currency }} {{ number_format($invoice->tax_total, 2) }}</th>
                                                </tr>
                                            @endif
                                            <tr class="table-primary">
                                                <th colspan="3">Total:</th>
                                                <th>{{ $invoice->currency }} {{ number_format($invoice->total, 2) }}</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted">No items found for this invoice.</p>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <!-- Payment Information -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Payment Information</h5>
                        </div>
                        <div class="card-body">
                            @if($invoice->status === 'paid')
                                <div class="alert alert-success">
                                    <i class="fas fa-check-circle"></i>
                                    <strong>Payment Received</strong><br>
                                    This invoice has been paid.
                                </div>
                            @else
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <strong>Payment Pending</strong><br>
                                    This invoice is awaiting payment.
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Payment History -->
                    @if($invoice->payments->count() > 0)
                        <div class="card mt-3">
                            <div class="card-header">
                                <h5 class="mb-0">Payment History</h5>
                            </div>
                            <div class="card-body">
                                @foreach($invoice->payments as $payment)
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div>
                                            <strong>{{ $payment->currency }} {{ number_format($payment->amount, 2) }}</strong><br>
                                            <small class="text-muted">{{ $payment->paid_on->format('M d, Y') }}</small>
                                        </div>
                                        <span class="badge bg-success">{{ ucfirst($payment->method) }}</span>
                                    </div>
                                    @if($payment->reference)
                                        <small class="text-muted">Reference: {{ $payment->reference }}</small>
                                    @endif
                                    @if(!$loop->last)
                                        <hr>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif
                    
                    <!-- Quick Actions -->
                    <div class="card mt-3">
                        <div class="card-header">
                            <h5 class="mb-0">Quick Actions</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('customer.invoices.download', $invoice) }}" class="btn btn-outline-success">
                                    <i class="fas fa-download"></i> Download Invoice
                                </a>
                                <a href="{{ route('customer.invoices.index') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-list"></i> All Invoices
                                </a>
                                <a href="{{ route('customer.dashboard') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-home"></i> Dashboard
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
