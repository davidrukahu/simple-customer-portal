@extends('layouts.admin')

@section('title', 'Edit Customer')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3">Edit Customer: {{ $customer->name }}</h1>
                <a href="{{ route('admin.customers.show', $customer) }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Customer
                </a>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Customer Information</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.customers.update', $customer) }}">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $customer->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email', $customer->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="company" class="form-label">Company</label>
                                    <input type="text" class="form-control @error('company') is-invalid @enderror" 
                                           id="company" name="company" value="{{ old('company', $customer->company) }}">
                                    @error('company')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" name="phone" value="{{ old('phone', $customer->phone) }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="currency" class="form-label">Currency <span class="text-danger">*</span></label>
                                    <select class="form-select @error('currency') is-invalid @enderror" id="currency" name="currency" required>
                                        <option value="">Select Currency</option>
                                        <option value="KES" {{ old('currency', $customer->currency) === 'KES' ? 'selected' : '' }}>KES - Kenyan Shilling</option>
                                        <option value="USD" {{ old('currency', $customer->currency) === 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
                                        <option value="EUR" {{ old('currency', $customer->currency) === 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                                        <option value="GBP" {{ old('currency', $customer->currency) === 'GBP' ? 'selected' : '' }}>GBP - British Pound</option>
                                    </select>
                                    @error('currency')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="is_active" class="form-label">Status</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                               value="1" {{ old('is_active', $customer->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Active Customer
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <h6 class="mb-3">Billing Address</h6>
                        @php $address = old('billing_address', $customer->billing_address_json ?? []); @endphp
                        
                        <div class="mb-3">
                            <label for="billing_address_street" class="form-label">Street Address</label>
                            <input type="text" class="form-control" 
                                   id="billing_address_street" name="billing_address[street]" 
                                   value="{{ $address['street'] ?? '' }}">
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="billing_address_city" class="form-label">City</label>
                                    <input type="text" class="form-control" 
                                           id="billing_address_city" name="billing_address[city]" 
                                           value="{{ $address['city'] ?? '' }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="billing_address_state" class="form-label">State/Province</label>
                                    <input type="text" class="form-control" 
                                           id="billing_address_state" name="billing_address[state]" 
                                           value="{{ $address['state'] ?? '' }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="billing_address_postal_code" class="form-label">Postal Code</label>
                                    <input type="text" class="form-control" 
                                           id="billing_address_postal_code" name="billing_address[postal_code]" 
                                           value="{{ $address['postal_code'] ?? '' }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="billing_address_country" class="form-label">Country</label>
                                    <input type="text" class="form-control" 
                                           id="billing_address_country" name="billing_address[country]" 
                                           value="{{ $address['country'] ?? '' }}">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes', $customer->notes) }}</textarea>
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('admin.customers.show', $customer) }}" class="btn btn-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Customer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">Customer Statistics</h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-sm">
                        <tr>
                            <td>Total Domains:</td>
                            <td class="fw-bold">{{ $customer->domains()->count() }}</td>
                        </tr>
                        <tr>
                            <td>Active Domains:</td>
                            <td class="fw-bold">{{ $customer->domains()->where('status', 'active')->count() }}</td>
                        </tr>
                        <tr>
                            <td>Total Invoices:</td>
                            <td class="fw-bold">{{ $customer->invoices()->count() }}</td>
                        </tr>
                        <tr>
                            <td>Outstanding:</td>
                            <td class="fw-bold">{{ $customer->currency }} {{ number_format($customer->invoices()->whereIn('status', ['sent', 'overdue'])->sum('total'), 2) }}</td>
                        </tr>
                        <tr>
                            <td>Created:</td>
                            <td>{{ $customer->created_at->format('M d, Y') }}</td>
                        </tr>
                        <tr>
                            <td>Last Updated:</td>
                            <td>{{ $customer->updated_at->format('M d, Y') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Password Reset</h6>
                </div>
                <div class="card-body">
                    <p class="text-muted">To change the customer's password, they need to use the "Forgot Password" feature on the login page.</p>
                    <a href="{{ route('password.request') }}" target="_blank" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-external-link-alt"></i> Password Reset Page
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
