<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'quantity',
        'price',
        'unit_price',
        'subtotal',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'quantity' => 'integer',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::saving(function (OrderItem $item) {
            // Sync unit_price and price
            if ($item->isDirty('price') && !$item->isDirty('unit_price')) {
                $item->unit_price = $item->price;
            } elseif ($item->isDirty('unit_price') && !$item->isDirty('price')) {
                $item->price = $item->unit_price;
            }

            // Auto-calculate subtotal
            $item->subtotal = $item->price * $item->quantity;
        });
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
