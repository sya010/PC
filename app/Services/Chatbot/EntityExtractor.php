<?php

declare(strict_types=1);

namespace App\Services\Chatbot;

/**
 * Entity Extractor - Extracts structured data from natural language
 * 
 * Identifies categories, specifications, budgets, and other
 * relevant entities from user messages.
 */
class EntityExtractor
{
    /**
     * Category patterns and their canonical forms
     */
    private array $categoryPatterns = [
        'cpu' => ['cpu', 'processor', 'processors', 'ryzen', 'intel core', 'amd ryzen', 'i3', 'i5', 'i7', 'i9'],
        'gpu' => ['gpu', 'graphics card', 'graphics cards', 'video card', 'rtx', 'gtx', 'radeon', 'nvidia', 'geforce'],
        'ram' => ['ram', 'memory', 'memories', 'ddr4', 'ddr5', 'dimm'],
        'motherboard' => ['motherboard', 'motherboards', 'mobo', 'mainboard', 'b550', 'b650', 'x570', 'z690', 'z790', 'b450'],
        'storage' => ['storage', 'ssd', 'hdd', 'nvme', 'hard drive', 'hard disk', 'solid state', 'm.2'],
        'psu' => ['psu', 'power supply', 'power supplies', 'watt', 'power unit'],
        'case' => ['case', 'cases', 'tower', 'cabinet', 'chassis', 'enclosure', 'mid tower', 'full tower'],
        'cooling' => ['cooling', 'cooler', 'coolers', 'fan', 'fans', 'aio', 'liquid cooling', 'heatsink', 'air cooler'],
        'monitor' => ['monitor', 'monitors', 'display', 'screen'],
        'keyboard' => ['keyboard', 'keyboards', 'keeb', 'mechanical keyboard'],
        'mouse' => ['mouse', 'mice', 'gaming mouse'],
    ];

    /**
     * Specification patterns
     */
    private array $specPatterns = [
        // RAM specs
        'ram_size' => '/(\d+)\s*gb\s*(ram|memory|ddr)/i',
        'ram_type' => '/(ddr[45])/i',
        'ram_speed' => '/(\d{4})\s*mhz/i',
        
        // CPU specs
        'cpu_brand' => '/(intel|amd)/i',
        'cpu_model' => '/(i[3579]|ryzen\s*[3579]|ryzen\s*threadripper)/i',
        'cpu_gen' => '/(\d{4,5}[a-z]?x?3?d?)/i',
        
        // GPU specs
        'gpu_brand' => '/(nvidia|amd|radeon|geforce)/i',
        'gpu_model' => '/(rtx\s*\d{4}|gtx\s*\d{4}|rx\s*\d{4})/i',
        'gpu_vram' => '/(\d+)\s*gb\s*(vram|gddr)/i',
        
        // Socket types
        'socket' => '/(am[45]|lga\s*\d{4}|lga1200|lga1700)/i',
        
        // Storage specs
        'storage_size' => '/(\d+)\s*(tb|gb)\s*(ssd|nvme|hdd|storage)/i',
        'storage_type' => '/(nvme|ssd|hdd|m\.2|sata)/i',
        
        // PSU specs
        'psu_wattage' => '/(\d{3,4})\s*w(att)?/i',
        'psu_rating' => '/(80\+?\s*(gold|platinum|titanium|bronze))/i',
        
        // Form factor
        'form_factor' => '/(atx|micro\s*atx|mini\s*itx|e-?atx)/i',
    ];

    /**
     * Budget patterns
     */
    private array $budgetPatterns = [
        '/\$\s*(\d+(?:,\d{3})*(?:\.\d{2})?)/i',
        '/(\d+(?:,\d{3})*)\s*(?:dollars?|usd|\$)/i',
        '/under\s*\$?\s*(\d+(?:,\d{3})*)/i',
        '/budget\s*(?:of|is|:)?\s*\$?\s*(\d+(?:,\d{3})*)/i',
        '/(\d+(?:,\d{3})*)\s*budget/i',
    ];

    /**
     * Use case patterns
     */
    private array $useCasePatterns = [
        'gaming' => ['gaming', 'games', 'game', 'fps', 'esports'],
        'editing' => ['editing', 'video editing', 'premiere', 'davinci', 'after effects'],
        'streaming' => ['streaming', 'stream', 'twitch', 'obs', 'broadcaster'],
        'office' => ['office', 'work', 'productivity', 'business', 'everyday'],
        '3d_cad' => ['3d', 'cad', 'blender', 'maya', 'rendering', 'modeling'],
    ];

    /**
     * Performance level patterns
     */
    private array $performancePatterns = [
        'budget' => ['budget', 'cheap', 'affordable', 'entry', 'basic', 'low cost'],
        'mid-range' => ['mid-range', 'midrange', 'mid tier', 'balanced', 'moderate'],
        'high-end' => ['high-end', 'highend', 'high end', 'high tier', 'powerful', 'performance'],
        'enthusiast' => ['enthusiast', 'extreme', 'ultimate', 'best', 'top', 'max', 'overkill'],
    ];

    /**
     * Extract all entities from a message
     */
    public function extract(string $message): array
    {
        $message = strtolower(trim($message));
        
        return [
            'category' => $this->extractCategory($message),
            'specs' => $this->extractSpecs($message),
            'budget' => $this->extractBudget($message),
            'use_case' => $this->extractUseCase($message),
            'performance_level' => $this->extractPerformanceLevel($message),
            'components' => $this->extractComponentMentions($message),
            'product_name' => $this->extractProductName($message),
        ];
    }

    /**
     * Extract the primary category being discussed
     */
    public function extractCategory(string $message): ?string
    {
        $message = strtolower($message);
        
        foreach ($this->categoryPatterns as $category => $patterns) {
            foreach ($patterns as $pattern) {
                if (str_contains($message, $pattern)) {
                    return $category;
                }
            }
        }
        
        return null;
    }

    /**
     * Extract specifications from the message
     */
    public function extractSpecs(string $message): array
    {
        $specs = [];
        
        foreach ($this->specPatterns as $specType => $pattern) {
            if (preg_match($pattern, $message, $matches)) {
                $specs[$specType] = $this->normalizeSpec($specType, $matches[1]);
            }
        }
        
        return $specs;
    }

    /**
     * Normalize extracted specification values
     */
    private function normalizeSpec(string $type, string $value): string
    {
        $value = strtoupper(trim($value));
        
        return match ($type) {
            'ram_type' => str_replace(' ', '', $value),
            'socket' => str_replace(' ', '', $value),
            'gpu_model' => str_replace(' ', ' ', $value),
            'form_factor' => strtoupper(str_replace([' ', '-'], '', $value)),
            default => $value,
        };
    }

    /**
     * Extract budget from the message
     */
    public function extractBudget(string $message): ?int
    {
        // Try precise patterns first
        foreach ($this->budgetPatterns as $pattern) {
            if (preg_match($pattern, $message, $matches)) {
                $value = str_replace(',', '', $matches[1]);
                return (int) $value;
            }
        }
        
        // Use intelligent IQD extraction
        $iqdAmount = TextNormalizer::extractIQD($message);
        if ($iqdAmount) {
            // Check if it's in a reasonable range (250,000 IQD minimum for a PC component)
            // or if there's budget context
            if ($iqdAmount > 250000 || preg_match('/(budget|under|max|spend|afford|cost)/i', $message)) {
                return $iqdAmount;
            }
        }
        
        return null;
    }

    /**
     * Extract use case from the message
     */
    public function extractUseCase(string $message): ?string
    {
        $message = strtolower($message);
        
        foreach ($this->useCasePatterns as $useCase => $patterns) {
            foreach ($patterns as $pattern) {
                if (str_contains($message, $pattern)) {
                    return $useCase;
                }
            }
        }
        
        return null;
    }

    /**
     * Extract performance level from the message
     */
    public function extractPerformanceLevel(string $message): ?string
    {
        $message = strtolower($message);
        
        foreach ($this->performancePatterns as $level => $patterns) {
            foreach ($patterns as $pattern) {
                if (str_contains($message, $pattern)) {
                    return $level;
                }
            }
        }
        
        return null;
    }

    /**
     * Extract component type mentions for compatibility checks
     */
    public function extractComponentMentions(string $message): array
    {
        $components = [];
        $message = strtolower($message);
        
        // Extract specific component types mentioned
        if (preg_match('/(i[3579]-?\d{4,5}[a-z]?|ryzen\s*[3579]\s*\d{4}[a-z]*)/i', $message, $matches)) {
            $components['cpu'] = $matches[1];
        }
        
        if (preg_match('/(rtx\s*\d{4}|gtx\s*\d{4}|rx\s*\d{4})/i', $message, $matches)) {
            $components['gpu'] = $matches[1];
        }
        
        if (preg_match('/(b[456][05]0|x[56]70|z[67]90|h[67]10)/i', $message, $matches)) {
            $components['motherboard'] = $matches[1];
        }
        
        if (preg_match('/(am[45]|lga\s*\d{4})/i', $message, $matches)) {
            $components['socket'] = strtoupper(str_replace(' ', '', $matches[1]));
        }
        
        return $components;
    }

    /**
     * Extract potential product name for direct search
     */
    public function extractProductName(string $message): ?string
    {
        // Look for quoted text
        if (preg_match('/"([^"]+)"/', $message, $matches)) {
            return $matches[1];
        }
        
        // Look for specific product patterns
        $productPatterns = [
            '/(rtx\s*\d{4}\s*(?:ti|super)?)/i',
            '/(gtx\s*\d{4}\s*(?:ti|super)?)/i',
            '/(rx\s*\d{4}\s*(?:xt)?)/i',
            '/(ryzen\s*[3579]\s*\d{4}[a-z3d]*)/i',
            '/(i[3579]-?\d{4,5}[a-z]?)/i',
        ];
        
        foreach ($productPatterns as $pattern) {
            if (preg_match($pattern, $message, $matches)) {
                return $matches[1];
            }
        }
        
        return null;
    }
}
