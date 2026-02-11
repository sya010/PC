<?php

declare(strict_types=1);

namespace App\Services\Chatbot;

use App\Models\Product;
use Illuminate\Support\Collection;

/**
 * Recommendation Engine - Ranks and filters products intelligently
 */
class RecommendationEngine
{
    public function searchProducts(string $category, array $specs = [], ?int $budget = null): Collection
    {
        $categoryMap = [
            'cpu' => 'CPU', 'gpu' => 'GPU', 'ram' => 'RAM',
            'motherboard' => 'Motherboard', 'storage' => 'Storage',
            'psu' => 'PSU', 'case' => 'Case', 'cooling' => 'Cooling',
        ];

        $dbCategory = $categoryMap[strtolower($category)] ?? ucfirst($category);

        $query = Product::where('is_active', true)
            ->where('category', $dbCategory);

        if ($budget) {
            $query->where('price', '<=', $budget);
        }

        // Apply spec filters
        foreach ($specs as $key => $value) {
            if ($value) {
                $query->where("specs->{$key}", 'like', "%{$value}%");
            }
        }

        $products = $query->orderBy('price', 'asc')->limit(20)->get();

        return $this->rankProducts($products, $specs, $budget);
    }

    private function rankProducts(Collection $products, array $specs, ?int $budget): Collection
    {
        return $products->map(function ($product) use ($specs, $budget) {
            $score = 50; // Base score
            
            // Budget fit bonus
            if ($budget && $product->price <= $budget * 0.9) {
                $score += 20;
            }
            
            // Spec match bonus
            $productSpecs = $product->specs ?? [];
            foreach ($specs as $key => $value) {
                if (isset($productSpecs[$key]) && stripos($productSpecs[$key], $value) !== false) {
                    $score += 10;
                }
            }
            
            $product->recommendation_score = $score;
            return $product;
        })->sortByDesc('recommendation_score');
    }

    public function getAlternatives(Product $product, int $limit = 3): Collection
    {
        return Product::where('category', $product->category)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->whereBetween('price', [$product->price * 0.7, $product->price * 1.3])
            ->limit($limit)
            ->get();
    }
}
