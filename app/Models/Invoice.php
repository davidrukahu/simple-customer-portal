<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'customer_id',
        'number',
        'status',
        'issue_date',
        'due_date',
        'subtotal',
        'tax_total',
        'total',
        'currency',
        'notes',
    ];

    protected $casts = [
        'issue_date' => 'datetime',
        'due_date' => 'datetime',
        'subtotal' => 'decimal:2',
        'tax_total' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    /**
     * Get the customer that owns this invoice
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get invoice items for this invoice
     */
    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    /**
     * Get payments for this invoice
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Check if invoice is overdue
     */
    public function isOverdue(): bool
    {
        return $this->due_date < now() && 
               in_array($this->status, ['sent', 'draft']) &&
               $this->total > $this->getTotalPaidAmount();
    }

    /**
     * Check if invoice is paid
     */
    public function isPaid(): bool
    {
        return $this->status === 'paid' || 
               $this->getTotalPaidAmount() >= $this->total;
    }

    /**
     * Get total paid amount
     */
    public function getTotalPaidAmount(): float
    {
        return $this->payments()->sum('amount');
    }

    /**
     * Get remaining balance
     */
    public function getRemainingBalanceAttribute(): float
    {
        return max(0, $this->total - $this->getTotalPaidAmount());
    }

    /**
     * Get days until due (positive number)
     */
    public function getDaysUntilDueAttribute(): int
    {
        if ($this->due_date->isPast()) {
            return 0;
        }
        return (int) now()->diffInDays($this->due_date, false);
    }

    /**
     * Get days overdue (positive number)
     */
    public function getDaysOverdueAttribute(): int
    {
        return max(0, $this->due_date->diffInDays(now()));
    }

    /**
     * Generate invoice number
     */
    public static function generateInvoiceNumber(): string
    {
        $year = now()->year;
        $prefix = config('app.invoice_prefix', 'INV');
        
        $lastInvoice = static::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();
        
        $sequence = $lastInvoice ? 
            (int) substr($lastInvoice->number, -4) + 1 : 
            config('app.invoice_start_number', 1);
        
        return sprintf('%s-%d-%04d', $prefix, $year, $sequence);
    }

    /**
     * Mark invoice as paid
     */
    public function markAsPaid($amount = null, $method = 'manual', $reference = null)
    {
        $amount = $amount ?? $this->remaining_balance;
        
        $this->payments()->create([
            'amount' => $amount,
            'currency' => $this->currency,
            'method' => $method,
            'paid_on' => now(),
            'reference' => $reference,
        ]);

        if ($this->isPaid()) {
            $this->update(['status' => 'paid']);
        }
    }

    /**
     * Update invoice status based on due date and payments
     */
    public function updateStatus()
    {
        if ($this->isPaid()) {
            $this->update(['status' => 'paid']);
        } elseif ($this->isOverdue()) {
            $this->update(['status' => 'overdue']);
        } elseif ($this->status === 'draft' && $this->issue_date <= now()) {
            $this->update(['status' => 'sent']);
        }
    }

    /**
     * Scope for overdue invoices
     */
    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
                    ->whereIn('status', ['sent', 'draft'])
                    ->whereRaw('total > COALESCE((SELECT SUM(amount) FROM payments WHERE invoice_id = invoices.id), 0)');
    }

    /**
     * Scope for paid invoices
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    /**
     * Scope for outstanding invoices
     */
    public function scopeOutstanding($query)
    {
        return $query->whereIn('status', ['sent', 'overdue']);
    }
}
