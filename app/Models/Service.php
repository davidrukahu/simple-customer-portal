<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'customer_id',
        'name',
        'description',
        'billing_cycle',
        'price',
        'currency',
        'next_invoice_on',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'next_invoice_on' => 'datetime',
    ];

    /**
     * Get the customer that owns the service
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get customer services for this service
     */
    public function customerServices()
    {
        return $this->hasMany(CustomerService::class);
    }

    /**
     * Get invoice items for this service
     */
    public function invoiceItems()
    {
        return $this->morphMany(InvoiceItem::class, 'item');
    }

    /**
     * Scope for active services
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Get days until next invoice (positive number)
     */
    public function getDaysUntilNextInvoiceAttribute(): int
    {
        if (!$this->next_invoice_on) {
            return 0;
        }
        if ($this->next_invoice_on->isPast()) {
            return 0;
        }
        return (int) now()->diffInDays($this->next_invoice_on, false);
    }

    /**
     * Get days since invoice due (positive number)
     */
    public function getDaysSinceInvoiceDueAttribute(): int
    {
        if (!$this->next_invoice_on) {
            return 0;
        }
        return max(0, $this->next_invoice_on->diffInDays(now()));
    }
}
