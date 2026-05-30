<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'full_name',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'zip_code',
        'total_amount',
        'status',
        'payment_method',
        'payment_status',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::updated(function (Order $order) {
            // Restore stock if the order status transitions to 'cancelled'
            if ($order->isDirty('status') && $order->status === 'cancelled' && $order->getOriginal('status') !== 'cancelled') {
                foreach ($order->items()->with('product')->get() as $item) {
                    if ($item->product) {
                        $item->product->increment('stock', $item->quantity);
                    }
                }
            }

            // Re-deduct stock if a cancelled order is reinstated back to a non-cancelled status
            if ($order->isDirty('status') && $order->getOriginal('status') === 'cancelled' && $order->status !== 'cancelled') {
                foreach ($order->items()->with('product')->get() as $item) {
                    if ($item->product) {
                        $item->product->decrement('stock', $item->quantity);
                    }
                }
            }
        });

        static::deleting(function (Order $order) {
            // Restore stock if a non-cancelled order is deleted
            if ($order->status !== 'cancelled') {
                foreach ($order->items()->with('product')->get() as $item) {
                    if ($item->product) {
                        $item->product->increment('stock', $item->quantity);
                    }
                }
            }
        });
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
