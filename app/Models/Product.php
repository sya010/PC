<?php

namespace App\Models;

use App\Services\ImageService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'image',
        'image_path',
        'images',
        'category',
        'stock',
        'stock_quantity',
        'is_active',
        'specs',
        'specifications',
        'has_specs',
    ];

    protected $casts = [
        'images' => 'array',
        'specs' => 'array',
        'specifications' => 'array',
        'is_active' => 'boolean',
        'has_specs' => 'boolean',
        'price' => 'decimal:2',
        'stock' => 'integer',
        'stock_quantity' => 'integer',
    ];

    /**
     * Get the PC component details associated with this product.
     */
    public function component(): HasOne
    {
        return $this->hasOne(Component::class);
    }

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

            // Delete PC component relation
            $product->component()->delete();
        });

        static::saving(function (Product $product) {
            // Sync specifications and specs
            if ($product->isDirty('specs') && !$product->isDirty('specifications')) {
                $product->specifications = $product->specs;
            } elseif ($product->isDirty('specifications') && !$product->isDirty('specs')) {
                $product->specs = $product->specifications;
            }

            // Sync stock and stock_quantity
            if ($product->isDirty('stock') && !$product->isDirty('stock_quantity')) {
                $product->stock_quantity = $product->stock;
            } elseif ($product->isDirty('stock_quantity') && !$product->isDirty('stock')) {
                $product->stock = $product->stock_quantity;
            }

            // Sync image and image_path
            if ($product->isDirty('image') && !$product->isDirty('image_path')) {
                $product->image_path = $product->image;
            } elseif ($product->isDirty('image_path') && !$product->isDirty('image')) {
                $product->image = $product->image_path;
            }
        });

        static::saved(function (Product $product) {
            $pcCategories = ['cpu', 'motherboard', 'ram', 'storage', 'gpu', 'psu', 'case'];
            $catLower = strtolower($product->category ?? '');
            
            if (in_array($catLower, $pcCategories)) {
                $specs = $product->specs ?? [];
                
                $socketType = null;
                $ramType = null;
                $formFactor = null;
                $wattage = null;
                
                // Extract values from nested or legacy formats
                if ($catLower === 'cpu') {
                    $socketType = $specs['socket'] ?? $specs['facts']['socket'] ?? $specs['needs']['socket'] ?? null;
                    $ramType = $specs['needs']['supported_memory_type'] ?? $specs['supported_memory_type'] ?? null;
                } elseif ($catLower === 'motherboard') {
                    $socketType = $specs['socket'] ?? $specs['facts']['socket'] ?? $specs['needs']['cpu_socket'] ?? null;
                    $ramType = $specs['memory_type'] ?? $specs['facts']['memory_type'] ?? $specs['needs']['ram_type'] ?? null;
                    $formFactor = $specs['form_factor'] ?? $specs['facts']['form_factor'] ?? null;
                } elseif ($catLower === 'ram') {
                    $ramType = $specs['type'] ?? $specs['facts']['type'] ?? null;
                } elseif ($catLower === 'storage') {
                    $formFactor = $specs['form_factor'] ?? $specs['facts']['form_factor'] ?? null;
                } elseif ($catLower === 'psu') {
                    $wattage = $specs['wattage'] ?? $specs['facts']['wattage'] ?? null;
                    if ($wattage) {
                        $wattage = (int) preg_replace('/[^0-9]/', '', (string)$wattage);
                    }
                } elseif ($catLower === 'case') {
                    $formFactor = $specs['form_factor'] ?? $specs['facts']['form_factor'] ?? null;
                }

                $product->component()->updateOrCreate([], [
                    'component_type' => $catLower,
                    'socket_type' => $socketType,
                    'ram_type' => $ramType,
                    'form_factor' => $formFactor,
                    'wattage' => $wattage,
                    'compatibility_rules' => $specs['limits'] ?? $specs['needs'] ?? null,
                ]);
            } else {
                $product->component()->delete();
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
