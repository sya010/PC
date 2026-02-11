<?php

declare(strict_types=1);

namespace App\Services\Chatbot;

use App\Models\Product;
use App\Services\CompatibilityEngine;

/**
 * Build Generator - Creates complete PC configurations
 */
class BuildGenerator
{
    private array $budgetAllocations = [
        'gaming' => ['gpu' => 0.35, 'cpu' => 0.25, 'motherboard' => 0.12, 'ram' => 0.10, 'storage' => 0.08, 'psu' => 0.06, 'case' => 0.04],
        'editing' => ['cpu' => 0.30, 'ram' => 0.20, 'gpu' => 0.20, 'motherboard' => 0.12, 'storage' => 0.10, 'psu' => 0.05, 'case' => 0.03],
        'streaming' => ['gpu' => 0.30, 'cpu' => 0.30, 'ram' => 0.15, 'motherboard' => 0.10, 'storage' => 0.07, 'psu' => 0.05, 'case' => 0.03],
        'office' => ['cpu' => 0.30, 'storage' => 0.25, 'ram' => 0.15, 'motherboard' => 0.15, 'psu' => 0.08, 'case' => 0.07],
    ];

    private array $tierLabels = [
        500 => 'Entry-Level', 800 => 'Budget Gaming', 1200 => 'Mid-Range',
        1800 => 'High-End', 10000 => 'Enthusiast',
    ];

    public function generate(int $budget, string $useCase): array
    {
        $allocations = $this->budgetAllocations[$useCase] ?? $this->budgetAllocations['gaming'];
        $components = [];
        $total = 0;

        $categoryMap = ['cpu' => 'CPU', 'gpu' => 'GPU', 'ram' => 'RAM', 'motherboard' => 'Motherboard', 
                       'storage' => 'Storage', 'psu' => 'PSU', 'case' => 'Case'];

        foreach ($allocations as $type => $percentage) {
            $targetPrice = $budget * $percentage;
            $dbCategory = $categoryMap[$type] ?? ucfirst($type);
            
            $product = Product::where('category', $dbCategory)
                ->where('is_active', true)
                ->where('price', '<=', $targetPrice * 1.2)
                ->where('price', '>=', $targetPrice * 0.5)
                ->orderByRaw('ABS(price - ?)', [$targetPrice])
                ->first();

            if ($product) {
                $components[$type] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'image' => $product->image,
                ];
                $total += $product->price;
            }
        }

        return [
            'success' => count($components) >= 4,
            'components' => $components,
            'total' => $total,
            'use_case' => $useCase,
            'tier' => $this->getTierLabel($budget),
        ];
    }

    public function getTierLabel(int $budget): string
    {
        foreach ($this->tierLabels as $threshold => $label) {
            if ($budget <= $threshold) return $label;
        }
        return 'Enthusiast';
    }
}
