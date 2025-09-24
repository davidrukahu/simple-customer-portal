@extends('layouts.customer')

@section('page-title', 'Invoices')
@section('page-subtitle', 'View and manage your invoices')

@section('content')
<!-- Filters -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-4">
                        <label for="search" class="form-label">Search</label>
                        <input type="text" name="search" id="search" class="form-control" placeholder="Search by invoice number..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="">All Statuses</option>
                            <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="sent" {{ request('status') === 'sent' ? 'selected' : '' }}>Sent</option>
                            <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="overdue" {{ request('status') === 'overdue' ? 'selected' : '' }}>Overdue</option>
                            <option value="void" {{ request('status') === 'void' ? 'selected' : '' }}>Void</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="sort" class="form-label">Sort By</label>
                        <select name="sort" id="sort" class="form-select">
                            <option value="created_at" {{ request('sort') === 'created_at' ? 'selected' : '' }}>Date Created</option>
                            <option value="due_date" {{ request('sort') === 'due_date' ? 'selected' : '' }}>Due Date</option>
                            <option value="total" {{ request('sort') === 'total' ? 'selected' : '' }}>Amount</option>
                            <option value="number" {{ request('sort') === 'number' ? 'selected' : '' }}>Invoice Number</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="direction" class="form-label">Direction</label>
                        <select name="direction" id="direction" class="form-select">
                            <option value="desc" {{ request('direction') === 'desc' ? 'selected' : '' }}>Descending</option>
                            <option value="asc" {{ request('direction') === 'asc' ? 'selected' : '' }}>Ascending</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search me-2"></i>
                            Filter
                        </button>
                        <a href="{{ route('customer.invoices.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-clockwise me-2"></i>
                            Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Invoices -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Your Invoices ({{ $invoices->total() }})</h5>
            </div>
            <div class="card-body">
                @if($invoices->count() > 0)
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Invoice #</th>
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
                                            <div class="fw-medium">{{ $invoice->number }}</div>
                                            @if($invoice->notes)
                                                <small class="text-muted">{{ Str::limit($invoice->notes, 50) }}</small>
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
                                        <td>
                                            <div class="fw-medium">{{ $invoice->currency }} {{ number_format($invoice->total, 2) }}</div>
                                            @if($invoice->status === 'paid')
                                                <small class="text-success">Paid</small>
                                            @elseif($invoice->status === 'overdue')
                                                <small class="text-danger">Overdue</small>
                                            @endif
                                        </td>
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
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('customer.invoices.show', $invoice) }}" class="btn btn-outline-primary btn-sm">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('customer.invoices.download', $invoice) }}" class="btn btn-outline-success btn-sm">
                                                    <i class="bi bi-download"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div class="text-muted">
                            Showing {{ $invoices->firstItem() }} to {{ $invoices->lastItem() }} of {{ $invoices->total() }} results
                        </div>
                        <div>
                            {{ $invoices->links() }}
                        </div>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-receipt text-muted" style="font-size: 4rem;"></i>
                        <h4 class="text-muted mt-3">No invoices found</h4>
                        <p class="text-muted">You don't have any invoices yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection