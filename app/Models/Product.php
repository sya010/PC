<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'image',
        'images',
        'category',
        'stock',
        'is_active',
        'specs',
    ];

    protected $casts = [
        'images' => 'array',
        'specs' => 'array',
        'is_active' => 'boolean',
    ];
}
