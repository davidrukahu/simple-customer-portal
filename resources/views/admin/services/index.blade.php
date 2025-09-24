@extends('layouts.admin')

@section('page-title', 'Services')
@section('page-subtitle', 'Manage customer services and billing')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <a href="{{ route('admin.services.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus"></i> Add New Service
                </a>
            </div>

            <!-- Filters -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.services.index') }}" class="row g-3">
                        <div class="col-md-4">
                            <input type="text" class="form-control" name="search" 
                                   value="{{ request('search') }}" placeholder="Search services or customers...">
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" name="status">
                                <option value="">All Statuses</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="paused" {{ request('status') == 'paused' ? 'selected' : '' }}>Paused</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" name="sort">
                                <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Created Date</option>
                                <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Service Name</option>
                                <option value="status" {{ request('sort') == 'status' ? 'selected' : '' }}>Status</option>
                                <option value="next_invoice_on" {{ request('sort') == 'next_invoice_on' ? 'selected' : '' }}>Next Invoice</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-outline-primary w-100">Filter</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Services Table -->
            <div class="card">
                <div class="card-body">
                    @if($services->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Service</th>
                                        <th>Customer</th>
                                        <th>Billing Cycle</th>
                                        <th>Price</th>
                                        <th>Next Invoice</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($services as $service)
                                        <tr>
                                            <td>
                                                <strong>{{ $service->name }}</strong>
                                                @if($service->description)
                                                    <br><small class="text-muted">{{ Str::limit($service->description, 50) }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.customers.show', $service->customer) }}">
                                                    {{ $service->customer->name }}
                                                </a>
                                                @if($service->customer->company)
                                                    <br><small class="text-muted">{{ $service->customer->company }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    $cycleColors = [
                                                        'one_time' => 'secondary',
                                                        'monthly' => 'primary',
                                                        'yearly' => 'success'
                                                    ];
                                                @endphp
                                                <span class="badge bg-{{ $cycleColors[$service->billing_cycle] ?? 'secondary' }}">
                                                    {{ ucfirst(str_replace('_', ' ', $service->billing_cycle)) }}
                                                </span>
                                            </td>
                                            <td>{{ $service->currency }} {{ number_format($service->price, 2) }}</td>
                                            <td>
                                                @if($service->next_invoice_on)
                                                    {{ $service->next_invoice_on->format('M d, Y') }}
                                                    @if($service->next_invoice_on->isPast())
                                                        <br><span class="badge bg-danger">Overdue</span>
                                                    @elseif($service->days_until_next_invoice <= 7)
                                                        <br><span class="badge bg-warning">Due Soon</span>
                                                    @endif
                                                @else
                                                    <span class="text-muted">Not scheduled</span>
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    $statusColors = [
                                                        'active' => 'success',
                                                        'paused' => 'warning',
                                                        'cancelled' => 'danger'
                                                    ];
                                                @endphp
                                                <span class="badge bg-{{ $statusColors[$service->status] ?? 'secondary' }}">
                                                    {{ ucfirst($service->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.services.show', $service) }}" class="btn btn-sm btn-outline-primary">View</a>
                                                    <a href="{{ route('admin.services.edit', $service) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                                    <form method="POST" action="{{ route('admin.services.destroy', $service) }}" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                                onclick="return confirm('Are you sure you want to delete this service?')">
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
                            {{ $services->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-cogs fa-3x text-muted mb-3"></i>
                            <h4>No services found</h4>
                            <p class="text-muted">Start by adding your first service.</p>
                            <a href="{{ route('admin.services.create') }}" class="btn btn-primary">Add First Service</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
