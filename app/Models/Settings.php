<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    protected $fillable = [
        'business_name',
        'email_from',
        'support_email',
        'phone',
        'billing_instructions_md',
        'address_json',
        'default_currency',
        'timezone',
    ];

    protected $casts = [
        'address_json' => 'array',
    ];

    /**
     * Get a setting value
     */
    public static function get($key, $default = null)
    {
        $settings = static::first();
        return $settings ? $settings->$key ?? $default : $default;
    }

    /**
     * Set a setting value
     */
    public static function set($key, $value)
    {
        $settings = static::first() ?? new static();
        $settings->$key = $value;
        $settings->save();
        return $settings;
    }

    /**
     * Get all settings as array
     */
    public static function getAll(): array
    {
        $settings = static::first();
        return $settings ? $settings->toArray() : [];
    }

    /**
     * Get business address formatted
     */
    public function getFormattedAddressAttribute(): string
    {
        $address = $this->address_json ?? [];
        return implode(', ', array_filter([
            $address['street'] ?? '',
            $address['city'] ?? '',
            $address['country'] ?? '',
        ]));
    }

    /**
     * Get default settings
     */
    public static function getDefaults(): array
    {
        return [
            'business_name' => 'OneChamber LTD',
            'email_from' => 'noreply@onechamber.com',
            'support_email' => 'support@onechamber.com',
            'phone' => '',
            'billing_instructions_md' => 'Please make payment within 30 days of invoice date.',
            'address_json' => [
                'street' => 'Worldwide Printing Center, 4th Floor, Mushebi Road, Parklands',
                'city' => 'Nairobi',
                'country' => 'Kenya',
            ],
            'default_currency' => 'KES',
            'timezone' => 'Africa/Nairobi',
        ];
    }
}
