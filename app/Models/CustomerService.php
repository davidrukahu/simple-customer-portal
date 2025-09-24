<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerService extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'customer_id',
        'service_id',
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
        'next_invoice_on' => 'date',
    ];

    /**
     * Get the customer that owns this service
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the service definition
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Get invoice items for this customer service
     */
    public function invoiceItems()
    {
        return $this->morphMany(InvoiceItem::class, 'item');
    }

    /**
     * Check if service is due for invoicing
     */
    public function isDueForInvoicing(): bool
    {
        return $this->next_invoice_on <= now() && $this->status === 'active';
    }

    /**
     * Calculate next invoice date based on billing cycle
     */
    public function calculateNextInvoiceDate(): \Carbon\Carbon
    {
        $currentDate = $this->next_invoice_on ?? now();
        
        switch ($this->billing_cycle) {
            case 'monthly':
                return $currentDate->addMonth();
            case 'yearly':
                return $currentDate->addYear();
            default:
                return $currentDate;
        }
    }

    /**
     * Update next invoice date
     */
    public function updateNextInvoiceDate()
    {
        $this->update(['next_invoice_on' => $this->calculateNextInvoiceDate()]);
    }

    /**
     * Scope for active services
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for services due for invoicing
     */
    public function scopeDueForInvoicing($query)
    {
        return $query->where('status', 'active')
                    ->where('next_invoice_on', '<=', now());
    }
}
