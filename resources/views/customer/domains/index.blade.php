@extends('layouts.customer')

@section('page-title', 'Domains')
@section('page-subtitle', 'Manage and monitor your domain registrations')

@section('content')
<!-- Domain Stats -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="stats-card">
            <div class="stats-icon primary">
                <i class="bi bi-globe"></i>
            </div>
            <h3 class="stats-number">{{ $domains->where('status', 'active')->count() }}</h3>
            <p class="stats-label">Active Domains</p>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stats-card">
            <div class="stats-icon warning">
                <i class="bi bi-exclamation-triangle"></i>
            </div>
            <h3 class="stats-number">{{ $domains->where('expires_at', '<=', now()->addDays(30))->where('expires_at', '>', now())->count() }}</h3>
            <p class="stats-label">Expiring Soon</p>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stats-card">
            <div class="stats-icon success">
                <i class="bi bi-check-circle"></i>
            </div>
            <h3 class="stats-number">{{ $domains->where('auto_renew', true)->count() }}</h3>
            <p class="stats-label">Auto Renewal</p>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stats-card">
            <div class="stats-icon danger">
                <i class="bi bi-x-circle"></i>
            </div>
            <h3 class="stats-number">{{ $domains->where('status', 'expired')->count() }}</h3>
            <p class="stats-label">Expired</p>
        </div>
    </div>
</div>

<!-- Search and Filters -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-4">
                        <label for="search" class="form-label">Search</label>
                        <input type="text" name="search" id="search" class="form-control" placeholder="Search domains..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="">All Statuses</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired</option>
                            <option value="grace" {{ request('status') === 'grace' ? 'selected' : '' }}>Grace</option>
                            <option value="redemption" {{ request('status') === 'redemption' ? 'selected' : '' }}>Redemption</option>
                            <option value="transfer-pending" {{ request('status') === 'transfer-pending' ? 'selected' : '' }}>Transfer Pending</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="sort" class="form-label">Sort By</label>
                        <select name="sort" id="sort" class="form-select">
                            <option value="created_at" {{ request('sort') === 'created_at' ? 'selected' : '' }}>Date Added</option>
                            <option value="expires_at" {{ request('sort') === 'expires_at' ? 'selected' : '' }}>Expiry Date</option>
                            <option value="fqdn" {{ request('sort') === 'fqdn' ? 'selected' : '' }}>Domain Name</option>
                            <option value="status" {{ request('sort') === 'status' ? 'selected' : '' }}>Status</option>
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
                        <a href="{{ route('customer.domains.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-clockwise me-2"></i>
                            Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Domains Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Your Domains ({{ $domains->total() }})</h5>
            </div>
            <div class="card-body">
                @if($domains->count() > 0)
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Domain</th>
                                    <th>Registrar</th>
                                    <th>Registered</th>
                                    <th>Expires</th>
                                    <th>Term</th>
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
                                        <td>{{ $domain->term_years }} year(s)</td>
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
                                            <a href="{{ route('customer.domains.show', $domain) }}" class="btn btn-outline-primary btn-sm">
                                                <i class="bi bi-eye"></i>
                                            </a>
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
                        <p class="text-muted">You don't have any domains registered yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection