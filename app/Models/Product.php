<?php

namespace App\Models;

use App\Services\ImageService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'has_specs',
    ];

    protected $casts = [
        'images' => 'array',
        'specs' => 'array',
        'is_active' => 'boolean',
        'has_specs' => 'boolean',
        'price' => 'decimal:2',
        'stock' => 'integer',
    ];

    /**
     * Auto-delete images from storage when a product is deleted.
     */
    protected static function booted(): void
    {
        static::deleting(function (Product $product) {
            // Delete main image
            ImageService::delete($product->image);

            // Delete additional images
            if ($product->images) {
                foreach ($product->images as $imagePath) {
                    ImageService::delete($imagePath);
                }
            }
        });
    }

    /**
     * Get the order items associated with this product.
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the full URL for the product's main image.
     * Handles both local storage paths and external URLs.
     */
    public function getImageUrlAttribute(): string
    {
        if (!$this->image) {
            return 'https://placehold.co/600x400/e2e8f0/94a3b8?text=No+Image';
        }

        // If it's already a full URL, return as-is (backward compatibility)
        if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
            return $this->image;
        }

        // Local storage path
        return asset('storage/' . $this->image);
    }

    /**
     * Get the thumbnail URL for the product's main image.
     * Uses the thumbs/ path convention.
     */
    public function getThumbnailUrlAttribute(): string
    {
        if (!$this->image) {
            return 'https://placehold.co/400x300/e2e8f0/94a3b8?text=No+Image';
        }

        // External URLs don't have thumbnails
        if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
            return $this->image;
        }

        // Derive thumbnail path from main image path
        $dir = dirname($this->image);
        $file = basename($this->image);
        $thumbPath = ($dir !== '.' ? $dir . '/' : '') . 'thumbs/' . $file;

        return asset('storage/' . $thumbPath);
    }
}
