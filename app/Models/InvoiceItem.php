<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'item_type',
        'item_id',
        'description',
        'qty',
        'unit_price',
        'line_total',
    ];

    protected $casts = [
        'qty' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'line_total' => 'decimal:2',
    ];

    /**
     * Get the invoice that owns this item
     */
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * Get the item (domain or service) that this invoice item represents
     */
    public function item()
    {
        return $this->morphTo();
    }

    /**
     * Calculate line total
     */
    public function calculateLineTotal(): float
    {
        return $this->qty * $this->unit_price;
    }

    /**
     * Update line total
     */
    public function updateLineTotal()
    {
        $this->update(['line_total' => $this->calculateLineTotal()]);
    }

    /**
     * Get item type options
     */
    public static function getItemTypeOptions(): array
    {
        return [
            'domain' => 'Domain',
            'service' => 'Service',
            'manual' => 'Manual Entry',
        ];
    }
}
