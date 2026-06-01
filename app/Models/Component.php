<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Component extends Model
{
    use HasFactory;

    protected $table = 'pc_components';

    protected $fillable = [
        'product_id',
        'component_type',
        'socket_type',
        'ram_type',
        'form_factor',
        'wattage',
        'compatibility_rules',
    ];

    protected $casts = [
        'compatibility_rules' => 'array',
        'wattage' => 'integer',
    ];

    /**
     * Get the product that owns this component.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
