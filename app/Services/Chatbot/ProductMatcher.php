<?php

declare(strict_types=1);

namespace App\Services\Chatbot;

use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

/**
 * Product Matcher - Intelligent fuzzy search for products
 */
class ProductMatcher
{
    /**
     * Find products matching a fuzzy search term
     */
    public function findProduct(string $searchTerm, int $limit = 5): Collection
    {
        $normalizedTerm = TextNormalizer::normalize($searchTerm);
        
        // Try exact match first (database query)
        $exactMatches = Product::where('name', 'like', "%{$searchTerm}%")
            ->orWhere('category', 'like', "%{$searchTerm}%")
            ->where('is_active', true)
            ->limit($limit)
            ->get();
            
        if ($exactMatches->count() >= $limit) {
            return $exactMatches;
        }
        
        // If not enough exact matches, perform fuzzy search on all products
        // Cache all products for 1 hour to avoid heavy DB hits
        $allProducts = Cache::remember('all_products_for_search', 3600, function () {
            return Product::select('id', 'name', 'category', 'price', 'image')->where('is_active', true)->get();
        });
        
        $scoredProducts = $allProducts->map(function ($product) use ($normalizedTerm) {
            $score = 0;
            $name = TextNormalizer::normalize($product->name);
            
            // Exact word match bonus
            if (str_contains($name, $normalizedTerm)) {
                $score += 50;
            }
            
            // Check similarity of each word in search term against product name
            $searchWords = explode(' ', $normalizedTerm);
            $nameWords = explode(' ', $name);
            
            foreach ($searchWords as $sWord) {
                if (strlen($sWord) < 3) continue; // Skip short words
                
                foreach ($nameWords as $nWord) {
                    $sim = TextNormalizer::similarity($sWord, $nWord);
                    if ($sim > 0.8) {
                        $score += ($sim * 20); // Add score based on similarity
                    }
                }
            }
            
            $product->match_score = $score;
            return $product;
        });
        
        // Filter out low scores and sort
        $fuzzyMatches = $scoredProducts
            ->filter(fn($p) => $p->match_score > 15)
            ->sortByDesc('match_score')
            ->take($limit - $exactMatches->count());
            
        return $exactMatches->merge($fuzzyMatches);
    }
}
