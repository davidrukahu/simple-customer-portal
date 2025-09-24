@extends('layouts.admin')

@section('page-title', 'Customers')
@section('page-subtitle', 'Manage your customer accounts')

@section('content')
<!-- Actions Bar -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.customers.create') }}" class="btn btn-primary">
                            <i class="bi bi-person-plus me-2"></i>
                            Add Customer
                        </a>
                        <a href="{{ route('admin.customers.import-form') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-upload me-2"></i>
                            Import CSV
                        </a>
                        <a href="{{ route('admin.customers.export') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-download me-2"></i>
                            Export CSV
                        </a>
                    </div>
                    
                    <!-- Search -->
                    <form method="GET" class="d-flex">
                        <div class="input-group" style="width: 300px;">
                            <input type="text" name="search" class="form-control" placeholder="Search customers..." value="{{ request('search') }}">
                            <button class="btn btn-outline-secondary" type="submit">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Customers Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">All Customers ({{ $customers->total() }})</h5>
            </div>
            <div class="card-body">
                @if($customers->count() > 0)
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Company</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($customers as $customer)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                                    {{ strtoupper(substr($customer->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="fw-medium">{{ $customer->name }}</div>
                                                    <small class="text-muted">ID: {{ $customer->id }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $customer->company ?: 'N/A' }}</td>
                                        <td>
                                            <a href="mailto:{{ $customer->email }}" class="text-decoration-none">
                                                {{ $customer->email }}
                                            </a>
                                        </td>
                                        <td>{{ $customer->phone ?: 'N/A' }}</td>
                                        <td>
                                            @if($customer->is_active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                        </td>
                                        <td>{{ $customer->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                    Actions
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('admin.customers.show', $customer) }}">
                                                            <i class="bi bi-eye me-2"></i>View
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('admin.customers.edit', $customer) }}">
                                                            <i class="bi bi-pencil me-2"></i>Edit
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <form method="POST" action="{{ route('admin.customers.toggle-status', $customer) }}" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="dropdown-item">
                                                                @if($customer->is_active)
                                                                    <i class="bi bi-pause me-2"></i>Deactivate
                                                                @else
                                                                    <i class="bi bi-play me-2"></i>Activate
                                                                @endif
                                                            </button>
                                                        </form>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <form method="POST" action="{{ route('admin.customers.destroy', $customer) }}" 
                                                              onsubmit="return confirm('Are you sure you want to delete this customer?')" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger">
                                                                <i class="bi bi-trash me-2"></i>Delete
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
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
                            Showing {{ $customers->firstItem() }} to {{ $customers->lastItem() }} of {{ $customers->total() }} results
                        </div>
                        <div>
                            {{ $customers->links() }}
                        </div>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-people text-muted" style="font-size: 4rem;"></i>
                        <h4 class="text-muted mt-3">No customers found</h4>
                        <p class="text-muted">Get started by adding your first customer.</p>
                        <a href="{{ route('admin.customers.create') }}" class="btn btn-primary">
                            <i class="bi bi-person-plus me-2"></i>
                            Add First Customer
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection