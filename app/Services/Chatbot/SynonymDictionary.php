<?php

declare(strict_types=1);

namespace App\Services\Chatbot;

/**
 * Synonym Dictionary - Maps hardware terms to canonical forms
 */
class SynonymDictionary
{
    private static array $categoryAliases = [
        'processor' => 'cpu', 'chip' => 'cpu', 'processor unit' => 'cpu',
        'graphics card' => 'gpu', 'video card' => 'gpu', 'graphics' => 'gpu',
        'memory' => 'ram', 'dimm' => 'ram',
        'mobo' => 'motherboard', 'mainboard' => 'motherboard', 'board' => 'motherboard',
        'ssd' => 'storage', 'hdd' => 'storage', 'hard drive' => 'storage', 'nvme' => 'storage',
        'power supply' => 'psu', 'power unit' => 'psu',
        'tower' => 'case', 'cabinet' => 'case', 'chassis' => 'case',
        'cooler' => 'cooling', 'fan' => 'cooling', 'heatsink' => 'cooling', 'aio' => 'cooling',
    ];

    private static array $categoryLabels = [
        'cpu' => 'Processors', 'gpu' => 'Graphics Cards', 'ram' => 'Memory',
        'motherboard' => 'Motherboards', 'storage' => 'Storage Drives',
        'psu' => 'Power Supplies', 'case' => 'Cases', 'cooling' => 'Cooling Solutions',
        'monitor' => 'Monitors', 'keyboard' => 'Keyboards', 'mouse' => 'Mice',
    ];

    public static function resolve(string $term): string
    {
        $term = strtolower(trim($term));
        return self::$categoryAliases[$term] ?? $term;
    }

    public static function getCategoryLabel(string $category): string
    {
        return self::$categoryLabels[strtolower($category)] ?? ucfirst($category);
    }

    public static function getAllAliases(string $category): array
    {
        $category = strtolower($category);
        $aliases = [$category];
        
        foreach (self::$categoryAliases as $alias => $target) {
            if ($target === $category) {
                $aliases[] = $alias;
            }
        }
        
        return $aliases;
    }
}
