<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Settings;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * Display the settings page.
     */
    public function index()
    {
        $settings = Settings::first();
        
        if (!$settings) {
            $settings = Settings::create([
                'business_name' => 'OneChamber LTD',
                'email_from' => 'noreply@onechamber.com',
                'support_email' => 'support@onechamber.com',
                'phone' => '+254 700 000 000',
                'billing_instructions_md' => 'Please make payment to the account details provided below.',
                'address_json' => [
                    'street' => 'Worldwide Printing Center, 4th Floor, Mushebi Road',
                    'city' => 'Parklands',
                    'state' => 'Nairobi',
                    'postal_code' => '00100',
                    'country' => 'Kenya'
                ],
                'default_currency' => 'KES',
                'timezone' => 'Africa/Nairobi',
            ]);
        }

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update the settings.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'business_name' => ['required', 'string', 'max:255'],
            'email_from' => ['required', 'email', 'max:255'],
            'support_email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'billing_instructions_md' => ['nullable', 'string'],
            'address_street' => ['nullable', 'string', 'max:255'],
            'address_city' => ['nullable', 'string', 'max:255'],
            'address_state' => ['nullable', 'string', 'max:255'],
            'address_postal_code' => ['nullable', 'string', 'max:20'],
            'address_country' => ['nullable', 'string', 'max:255'],
            'default_currency' => ['required', 'string', 'max:3'],
            'timezone' => ['required', 'string', 'max:50'],
        ]);

        $settings = Settings::first();
        
        $settings->update([
            'business_name' => $validated['business_name'],
            'email_from' => $validated['email_from'],
            'support_email' => $validated['support_email'],
            'phone' => $validated['phone'],
            'billing_instructions_md' => $validated['billing_instructions_md'],
            'address_json' => [
                'street' => $validated['address_street'],
                'city' => $validated['address_city'],
                'state' => $validated['address_state'],
                'postal_code' => $validated['address_postal_code'],
                'country' => $validated['address_country'],
            ],
            'default_currency' => $validated['default_currency'],
            'timezone' => $validated['timezone'],
        ]);

        return redirect()->route('admin.settings.index')->with('success', 'Settings updated successfully.');
    }
}
