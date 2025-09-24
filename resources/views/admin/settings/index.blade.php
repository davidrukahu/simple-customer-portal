@extends('layouts.admin')

@section('page-title', 'Settings')
@section('page-subtitle', 'Configure system preferences and business information')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <button type="button" class="btn btn-outline-secondary" onclick="resetToDefaults()">
                        <i class="bi bi-arrow-clockwise"></i> Reset to Defaults
                    </button>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.settings.update') }}">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- Business Information -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Business Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="business_name" class="form-label">Business Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('business_name') is-invalid @enderror" 
                                           name="business_name" id="business_name" 
                                           value="{{ old('business_name', $settings->business_name) }}" required>
                                    @error('business_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="email_from" class="form-label">From Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email_from') is-invalid @enderror" 
                                           name="email_from" id="email_from" 
                                           value="{{ old('email_from', $settings->email_from) }}" required>
                                    @error('email_from')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="support_email" class="form-label">Support Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('support_email') is-invalid @enderror" 
                                           name="support_email" id="support_email" 
                                           value="{{ old('support_email', $settings->support_email) }}" required>
                                    @error('support_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                           name="phone" id="phone" 
                                           value="{{ old('phone', $settings->phone) }}" 
                                           placeholder="+254 700 000 000">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Address Information -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Business Address</h5>
                            </div>
                            <div class="card-body">
                                @php
                                    $address = $settings->address_json ?? [];
                                @endphp
                                
                                <div class="mb-3">
                                    <label for="address_street" class="form-label">Street Address</label>
                                    <input type="text" class="form-control @error('address_street') is-invalid @enderror" 
                                           name="address_street" id="address_street" 
                                           value="{{ old('address_street', $address['street'] ?? '') }}" 
                                           placeholder="Worldwide Printing Center, 4th Floor, Mushebi Road">
                                    @error('address_street')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="address_city" class="form-label">City</label>
                                            <input type="text" class="form-control @error('address_city') is-invalid @enderror" 
                                                   name="address_city" id="address_city" 
                                                   value="{{ old('address_city', $address['city'] ?? '') }}" 
                                                   placeholder="Parklands">
                                            @error('address_city')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="address_state" class="form-label">State/County</label>
                                            <input type="text" class="form-control @error('address_state') is-invalid @enderror" 
                                                   name="address_state" id="address_state" 
                                                   value="{{ old('address_state', $address['state'] ?? '') }}" 
                                                   placeholder="Nairobi">
                                            @error('address_state')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="address_postal_code" class="form-label">Postal Code</label>
                                            <input type="text" class="form-control @error('address_postal_code') is-invalid @enderror" 
                                                   name="address_postal_code" id="address_postal_code" 
                                                   value="{{ old('address_postal_code', $address['postal_code'] ?? '') }}" 
                                                   placeholder="00100">
                                            @error('address_postal_code')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="address_country" class="form-label">Country</label>
                                            <input type="text" class="form-control @error('address_country') is-invalid @enderror" 
                                                   name="address_country" id="address_country" 
                                                   value="{{ old('address_country', $address['country'] ?? '') }}" 
                                                   placeholder="Kenya">
                                            @error('address_country')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <!-- System Settings -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">System Settings</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="default_currency" class="form-label">Default Currency <span class="text-danger">*</span></label>
                                    <select class="form-select @error('default_currency') is-invalid @enderror" 
                                            name="default_currency" id="default_currency" required>
                                        <option value="KES" {{ old('default_currency', $settings->default_currency) == 'KES' ? 'selected' : '' }}>KES - Kenyan Shilling</option>
                                        <option value="USD" {{ old('default_currency', $settings->default_currency) == 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
                                        <option value="EUR" {{ old('default_currency', $settings->default_currency) == 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                                    </select>
                                    @error('default_currency')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="timezone" class="form-label">Timezone <span class="text-danger">*</span></label>
                                    <select class="form-select @error('timezone') is-invalid @enderror" 
                                            name="timezone" id="timezone" required>
                                        <option value="Africa/Nairobi" {{ old('timezone', $settings->timezone) == 'Africa/Nairobi' ? 'selected' : '' }}>Africa/Nairobi (GMT+3)</option>
                                        <option value="UTC" {{ old('timezone', $settings->timezone) == 'UTC' ? 'selected' : '' }}>UTC (GMT+0)</option>
                                        <option value="America/New_York" {{ old('timezone', $settings->timezone) == 'America/New_York' ? 'selected' : '' }}>America/New_York (GMT-5)</option>
                                        <option value="Europe/London" {{ old('timezone', $settings->timezone) == 'Europe/London' ? 'selected' : '' }}>Europe/London (GMT+0)</option>
                                    </select>
                                    @error('timezone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Billing Instructions -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Billing Instructions</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="billing_instructions_md" class="form-label">Payment Instructions</label>
                                    <textarea class="form-control @error('billing_instructions_md') is-invalid @enderror" 
                                              name="billing_instructions_md" id="billing_instructions_md" 
                                              rows="8" placeholder="Enter payment instructions that will appear on invoices...">{{ old('billing_instructions_md', $settings->billing_instructions_md) }}</textarea>
                                    <div class="form-text">This text will appear on all invoices. You can use Markdown formatting.</div>
                                    @error('billing_instructions_md')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left"></i> Back to Dashboard
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Save Settings
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function resetToDefaults() {
    if (confirm('Are you sure you want to reset all settings to defaults? This will overwrite your current settings.')) {
        document.getElementById('business_name').value = 'OneChamber LTD';
        document.getElementById('email_from').value = 'noreply@onechamber.com';
        document.getElementById('support_email').value = 'support@onechamber.com';
        document.getElementById('phone').value = '+254 700 000 000';
        document.getElementById('address_street').value = 'Worldwide Printing Center, 4th Floor, Mushebi Road';
        document.getElementById('address_city').value = 'Parklands';
        document.getElementById('address_state').value = 'Nairobi';
        document.getElementById('address_postal_code').value = '00100';
        document.getElementById('address_country').value = 'Kenya';
        document.getElementById('default_currency').value = 'KES';
        document.getElementById('timezone').value = 'Africa/Nairobi';
        document.getElementById('billing_instructions_md').value = 'Please make payment to the account details provided below.';
    }
}
</script>
@endsection
