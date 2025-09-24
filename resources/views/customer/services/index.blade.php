@extends('layouts.customer')

@section('page-title', 'Services')
@section('page-subtitle', 'View your active services and subscriptions')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="text-muted">
                    <i class="bi bi-gear me-1"></i>{{ $services->total() }} services
                </div>
            </div>

            <!-- Filters -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('customer.services.index') }}" class="row g-3">
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="search" 
                                   value="{{ request('search') }}" placeholder="Search services...">
                        </div>
                        <div class="col-md-4">
                            <select class="form-select" name="status">
                                <option value="">All Statuses</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="paused" {{ request('status') == 'paused' ? 'selected' : '' }}>Paused</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
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
                                                <a href="{{ route('customer.services.show', $service) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i> View Details
                                                </a>
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
                            <p class="text-muted">You don't have any services yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
