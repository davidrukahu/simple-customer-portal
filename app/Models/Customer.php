<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'company',
        'email',
        'phone',
        'billing_address_json',
        'currency',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'billing_address_json' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get the user associated with this customer
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all domains for this customer
     */
    public function domains()
    {
        return $this->hasMany(Domain::class);
    }

    /**
     * Get all services for this customer
     */
    public function services()
    {
        return $this->hasMany(Service::class);
    }

    /**
     * Get all invoices for this customer
     */
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Get active domains for this customer
     */
    public function activeDomains()
    {
        return $this->domains()->where('status', 'active');
    }

    /**
     * Get domains expiring soon
     */
    public function domainsExpiringSoon($days = 30)
    {
        return $this->domains()
            ->where('status', 'active')
            ->where('expires_at', '<=', now()->addDays($days))
            ->where('expires_at', '>', now());
    }

    /**
     * Get total outstanding invoices amount
     */
    public function getOutstandingInvoicesTotalAttribute()
    {
        return $this->invoices()
            ->whereIn('status', ['sent', 'overdue'])
            ->sum('total');
    }

    /**
     * Get overdue invoices count
     */
    public function getOverdueInvoicesCountAttribute()
    {
        return $this->invoices()
            ->where('status', 'overdue')
            ->count();
    }
}
