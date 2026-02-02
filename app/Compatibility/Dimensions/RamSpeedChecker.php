<?php

namespace App\Compatibility\Dimensions;

use App\Compatibility\Contracts\DimensionChecker;

/**
 * Soft constraint: RAM speed vs Motherboard max speed
 */
class RamSpeedChecker implements DimensionChecker
{
    public function getTier(): string
    {
        return 'soft';
    }

    public function getWeight(): float
    {
        return 0.15; // 15% weight
    }

    public function getName(): string
    {
        return 'Memory Speed';
    }

    public function getComponentRoles(): array
    {
        return [
            'consumer' => 'ram',
            'provider' => 'motherboard'
        ];
    }

    private function parseSpeed($value): float
    {
        if (is_numeric($value)) return (float) $value;
        return (float) preg_replace('/[^0-9.]/', '', $value);
    }

    public function check(array $components): array
    {
        $ram = $components['ram'] ?? null;
        $motherboard = $components['motherboard'] ?? null;

        if (!$ram || !$motherboard) {
            return [
                'pass' => true,
                'score' => 1.0,
                'message' => 'Waiting for components',
                'details' => ['skipped' => true]
            ];
        }

        $ramSpeed = $this->parseSpeed($ram['specs']['speed'] ?? 0);
        $maxSpeed = $this->parseSpeed($motherboard['specs']['max_memory_speed'] ?? 9999);

        if ($ramSpeed <= 0) {
            return [
                'pass' => true,
                'score' => 0.8,
                'message' => 'RAM speed not specified',
                'details' => ['warning' => 'Unable to verify speed compatibility']
            ];
        }

        // If max speed not defined, assume motherboard supports the RAM
        if ($maxSpeed >= 9999) {
            return [
                'pass' => true,
                'score' => 1.0,
                'message' => "RAM speed: {$ramSpeed}MHz",
                'details' => ['ram_speed' => $ramSpeed, 'mb_max_speed' => 'not specified']
            ];
        }

        $score = min(1.0, $maxSpeed / $ramSpeed);
        $effectiveSpeed = min($ramSpeed, $maxSpeed);

        return [
            'pass' => true, // Soft constraint always passes
            'score' => $score,
            'message' => $ramSpeed <= $maxSpeed 
                ? "RAM speed optimal: {$ramSpeed}MHz" 
                : "RAM will downclock to {$maxSpeed}MHz (rated: {$ramSpeed}MHz)",
            'details' => [
                'ram_speed' => $ramSpeed,
                'mb_max_speed' => $maxSpeed,
                'effective_speed' => $effectiveSpeed,
                'efficiency' => round($score * 100, 1) . '%'
            ]
        ];
    }
}
