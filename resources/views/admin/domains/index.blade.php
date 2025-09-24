@extends('layouts.admin')

@section('page-title', 'Domains')
@section('page-subtitle', 'Manage domain registrations and renewals')

@section('content')
<!-- Actions Bar -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.domains.create') }}" class="btn btn-primary">
                            <i class="bi bi-globe me-2"></i>
                            Add Domain
                        </a>
                        <a href="{{ route('admin.domains.import-form') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-upload me-2"></i>
                            Import CSV
                        </a>
                        <a href="{{ route('admin.domains.export') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-download me-2"></i>
                            Export CSV
                        </a>
                    </div>
                    
                    <!-- Search and Filters -->
                    <form method="GET" class="d-flex gap-2">
                        <div class="input-group" style="width: 250px;">
                            <input type="text" name="search" class="form-control" placeholder="Search domains..." value="{{ request('search') }}">
                            <button class="btn btn-outline-secondary" type="submit">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                        <select name="status" class="form-select" style="width: 150px;">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired</option>
                            <option value="grace" {{ request('status') === 'grace' ? 'selected' : '' }}>Grace</option>
                            <option value="redemption" {{ request('status') === 'redemption' ? 'selected' : '' }}>Redemption</option>
                            <option value="transfer-pending" {{ request('status') === 'transfer-pending' ? 'selected' : '' }}>Transfer Pending</option>
                        </select>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Domains Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">All Domains ({{ $domains->total() }})</h5>
            </div>
            <div class="card-body">
                @if($domains->count() > 0)
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Domain</th>
                                    <th>Customer</th>
                                    <th>Registrar</th>
                                    <th>Registered</th>
                                    <th>Expires</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                    <th>Auto Renew</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($domains as $domain)
                                    <tr>
                                        <td>
                                            <div class="fw-medium">{{ $domain->fqdn }}</div>
                                            @if($domain->service_notes)
                                                <small class="text-muted">{{ Str::limit($domain->service_notes, 30) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.customers.show', $domain->customer) }}" class="text-decoration-none">
                                                {{ $domain->customer->name }}
                                            </a>
                                        </td>
                                        <td>{{ $domain->registrar ?: 'N/A' }}</td>
                                        <td>{{ $domain->registered_at->format('M d, Y') }}</td>
                                        <td>
                                            {{ $domain->expires_at->format('M d, Y') }}
                                            @if($domain->expires_at->isPast())
                                                <br><span class="badge bg-danger">Expired</span>
                                            @elseif($domain->days_until_expiry <= 30)
                                                <br><span class="badge bg-warning">{{ $domain->days_until_expiry }} days</span>
                                            @endif
                                        </td>
                                        <td>{{ $domain->currency }} {{ number_format($domain->price, 2) }}</td>
                                        <td>
                                            @php
                                                $statusColors = [
                                                    'active' => 'success',
                                                    'expired' => 'danger',
                                                    'grace' => 'warning',
                                                    'redemption' => 'danger',
                                                    'transfer-pending' => 'info'
                                                ];
                                            @endphp
                                            <span class="badge bg-{{ $statusColors[$domain->status] ?? 'secondary' }}">
                                                {{ ucfirst(str_replace('-', ' ', $domain->status)) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($domain->auto_renew)
                                                <span class="badge bg-success">Yes</span>
                                            @else
                                                <span class="badge bg-secondary">No</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                    Actions
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('admin.domains.show', $domain) }}">
                                                            <i class="bi bi-eye me-2"></i>View
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('admin.domains.edit', $domain) }}">
                                                            <i class="bi bi-pencil me-2"></i>Edit
                                                        </a>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <form method="POST" action="{{ route('admin.domains.destroy', $domain) }}" 
                                                              onsubmit="return confirm('Are you sure you want to delete this domain?')" class="d-inline">
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
                            Showing {{ $domains->firstItem() }} to {{ $domains->lastItem() }} of {{ $domains->total() }} results
                        </div>
                        <div>
                            {{ $domains->links() }}
                        </div>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-globe text-muted" style="font-size: 4rem;"></i>
                        <h4 class="text-muted mt-3">No domains found</h4>
                        <p class="text-muted">Get started by adding your first domain.</p>
                        <a href="{{ route('admin.domains.create') }}" class="btn btn-primary">
                            <i class="bi bi-globe me-2"></i>
                            Add First Domain
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection