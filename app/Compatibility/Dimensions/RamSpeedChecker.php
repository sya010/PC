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
        $extraRam = $components['extra_ram'] ?? [];

        $ramItems = [];
        if ($ram) {
            $ramItems[] = $ram;
        }
        foreach ($extraRam as $item) {
            if ($item) {
                $ramItems[] = $item;
            }
        }

        if (empty($ramItems) || !$motherboard) {
            return [
                'pass' => true,
                'score' => 1.0,
                'message' => 'Waiting for components',
                'details' => ['skipped' => true]
            ];
        }

        $maxSpeed = $this->parseSpeed($motherboard['specs']['max_memory_speed'] ?? 9999);

        $speeds = [];
        foreach ($ramItems as $item) {
            $speed = $this->parseSpeed($item['specs']['speed'] ?? 0);
            if ($speed > 0) {
                $speeds[] = [
                    'name' => $item['name'],
                    'speed' => $speed
                ];
            }
        }

        if (empty($speeds)) {
            return [
                'pass' => true,
                'score' => 0.8,
                'message' => 'RAM speed not specified',
                'details' => ['warning' => 'Unable to verify speed compatibility']
            ];
        }

        // Find min and max speed
        $minSpeed = min(array_column($speeds, 'speed'));
        $maxRamSpeed = max(array_column($speeds, 'speed'));

        // Check if mixing different speeds
        $isMixed = false;
        if (count(array_unique(array_column($speeds, 'speed'))) > 1) {
            $isMixed = true;
        }

        // Downclock due to motherboard limit
        $downclocksToMb = $minSpeed > $maxSpeed;
        $effectiveSpeed = min($minSpeed, $maxSpeed);

        if ($isMixed) {
            $score = 0.6; // lower score for mixing
            $message = "RAM speeds differ: Modules will run at the slowest speed ({$effectiveSpeed}MHz) and may cause instability";
            if ($downclocksToMb) {
                $message .= " (downclocked further due to motherboard limit of {$maxSpeed}MHz)";
            }
            return [
                'pass' => true,
                'score' => $score,
                'message' => $message,
                'details' => [
                    'speeds' => $speeds,
                    'mb_max_speed' => $maxSpeed,
                    'effective_speed' => $effectiveSpeed,
                    'mixed' => true
                ]
            ];
        }

        if ($downclocksToMb) {
            $score = min(1.0, $maxSpeed / $minSpeed);
            return [
                'pass' => true,
                'score' => $score,
                'message' => "RAM will downclock to {$maxSpeed}MHz (rated: {$minSpeed}MHz)",
                'details' => [
                    'ram_speed' => $minSpeed,
                    'mb_max_speed' => $maxSpeed,
                    'effective_speed' => $effectiveSpeed,
                    'efficiency' => round($score * 100, 1) . '%'
                ]
            ];
        }

        return [
            'pass' => true,
            'score' => 1.0,
            'message' => "RAM speed optimal: {$minSpeed}MHz",
            'details' => [
                'ram_speed' => $minSpeed,
                'mb_max_speed' => $maxSpeed,
                'effective_speed' => $effectiveSpeed
            ]
        ];
    }
}
