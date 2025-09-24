<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Domain extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'customer_id',
        'fqdn',
        'registrar',
        'registered_at',
        'expires_at',
        'term_years',
        'price',
        'currency',
        'status',
        'auto_renew',
        'service_notes',
    ];

    protected $casts = [
        'registered_at' => 'datetime',
        'expires_at' => 'datetime',
        'auto_renew' => 'boolean',
        'price' => 'decimal:2',
    ];

    /**
     * Get the customer that owns this domain
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get invoice items for this domain
     */
    public function invoiceItems()
    {
        return $this->morphMany(InvoiceItem::class, 'item');
    }

    /**
     * Check if domain is expiring soon
     */
    public function isExpiringSoon($days = 30): bool
    {
        return $this->expires_at <= now()->addDays($days) && 
               $this->expires_at > now() && 
               $this->status === 'active';
    }

    /**
     * Check if domain is expired
     */
    public function isExpired(): bool
    {
        return $this->expires_at < now();
    }

    /**
     * Get days until expiry (positive number)
     */
    public function getDaysUntilExpiryAttribute(): int
    {
        if ($this->expires_at->isPast()) {
            return 0;
        }
        return (int) now()->diffInDays($this->expires_at, false);
    }

    /**
     * Get days since expiry (positive number)
     */
    public function getDaysSinceExpiryAttribute(): int
    {
        return max(0, $this->expires_at->diffInDays(now()));
    }

    /**
     * Get formatted expiry status
     */
    public function getExpiryStatusAttribute(): string
    {
        if ($this->isExpired()) {
            return 'Expired';
        }
        
        $days = $this->days_until_expiry;
        
        if ($days <= 7) {
            return 'Expires in ' . $days . ' days';
        } elseif ($days <= 30) {
            return 'Expires in ' . $days . ' days';
        } else {
            return 'Active';
        }
    }

    /**
     * Scope for active domains
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for expiring domains
     */
    public function scopeExpiringSoon($query, $days = 30)
    {
        return $query->where('status', 'active')
                    ->where('expires_at', '<=', now()->addDays($days))
                    ->where('expires_at', '>', now());
    }

    /**
     * Scope for expired domains
     */
    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<', now());
    }

    /**
     * Update domain status based on expiry date
     */
    public function updateStatusBasedOnExpiry()
    {
        if ($this->isExpired()) {
            if ($this->status === 'active') {
                $this->update(['status' => 'expired']);
            } elseif ($this->status === 'expired' && 
                     $this->expires_at < now()->subDays(config('app.domain_expiry_grace_days', 30))) {
                $this->update(['status' => 'grace']);
            } elseif ($this->status === 'grace' && 
                     $this->expires_at < now()->subDays(config('app.domain_redemption_days', 30))) {
                $this->update(['status' => 'redemption']);
            }
        }
    }
}
