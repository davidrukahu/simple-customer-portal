@extends('layouts.admin')

@section('page-title', 'Invoices')
@section('page-subtitle', 'Manage invoices and payments')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <a href="{{ route('admin.invoices.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus"></i> Create Invoice
                </a>
            </div>

            <!-- Filters -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.invoices.index') }}" class="row g-3">
                        <div class="col-md-4">
                            <input type="text" class="form-control" name="search" 
                                   value="{{ request('search') }}" placeholder="Search invoices or customers...">
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" name="status">
                                <option value="">All Statuses</option>
                                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>Sent</option>
                                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                                <option value="void" {{ request('status') == 'void' ? 'selected' : '' }}>Void</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" name="sort">
                                <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Created Date</option>
                                <option value="number" {{ request('sort') == 'number' ? 'selected' : '' }}>Invoice Number</option>
                                <option value="due_date" {{ request('sort') == 'due_date' ? 'selected' : '' }}>Due Date</option>
                                <option value="total" {{ request('sort') == 'total' ? 'selected' : '' }}>Amount</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-outline-primary w-100">Filter</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Invoices Table -->
            <div class="card">
                <div class="card-body">
                    @if($invoices->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Invoice #</th>
                                        <th>Customer</th>
                                        <th>Issue Date</th>
                                        <th>Due Date</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($invoices as $invoice)
                                        <tr>
                                            <td>
                                                <strong>{{ $invoice->number }}</strong>
                                                @if($invoice->notes)
                                                    <br><small class="text-muted">{{ Str::limit($invoice->notes, 30) }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.customers.show', $invoice->customer) }}">
                                                    {{ $invoice->customer->name }}
                                                </a>
                                                @if($invoice->customer->company)
                                                    <br><small class="text-muted">{{ $invoice->customer->company }}</small>
                                                @endif
                                            </td>
                                            <td>{{ $invoice->issue_date->format('M d, Y') }}</td>
                                            <td>
                                                {{ $invoice->due_date->format('M d, Y') }}
                                                @if($invoice->due_date->isPast() && $invoice->status !== 'paid')
                                                    <br><span class="badge bg-danger">Overdue</span>
                                                @elseif($invoice->days_until_due <= 7 && $invoice->status !== 'paid')
                                                    <br><span class="badge bg-warning">Due Soon</span>
                                                @endif
                                            </td>
                                            <td>{{ $invoice->currency }} {{ number_format($invoice->total, 2) }}</td>
                                            <td>
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
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.invoices.show', $invoice) }}" class="btn btn-sm btn-outline-primary">View</a>
                                                    <a href="{{ route('admin.invoices.edit', $invoice) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                                    @if($invoice->status !== 'paid')
                                                        <form method="POST" action="{{ route('admin.invoices.mark-paid', $invoice) }}" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-outline-success" 
                                                                    onclick="return confirm('Mark this invoice as paid?')">
                                                                Mark Paid
                                                            </button>
                                                        </form>
                                                    @endif
                                                    <form method="POST" action="{{ route('admin.invoices.destroy', $invoice) }}" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                                onclick="return confirm('Are you sure you want to delete this invoice?')">
                                                            Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">
                            {{ $invoices->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-file-invoice fa-3x text-muted mb-3"></i>
                            <h4>No invoices found</h4>
                            <p class="text-muted">Start by creating your first invoice.</p>
                            <a href="{{ route('admin.invoices.create') }}" class="btn btn-primary">Create First Invoice</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
