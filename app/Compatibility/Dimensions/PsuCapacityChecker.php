<?php

namespace App\Compatibility\Dimensions;

use App\Compatibility\Contracts\DimensionChecker;

/**
 * Hard constraint: PSU must provide enough power for the system
 */
class PsuCapacityChecker implements DimensionChecker
{
    public function getTier(): string
    {
        return 'hard';
    }

    public function getWeight(): float
    {
        return 1.0;
    }

    public function getName(): string
    {
        return 'PSU Capacity';
    }

    public function getComponentRoles(): array
    {
        return [
            'consumer' => 'system',
            'provider' => 'psu'
        ];
    }

    private function parseWattage($value): float
    {
        if (is_numeric($value)) return (float) $value;
        return (float) preg_replace('/[^0-9.]/', '', $value);
    }

    public function check(array $components): array
    {
        $psu = $components['psu'] ?? null;

        if (!$psu) {
            return [
                'pass' => true,
                'score' => 1.0,
                'message' => 'Waiting for PSU',
                'details' => ['skipped' => true]
            ];
        }

        $psuWattage = $this->parseWattage($psu['specs']['wattage'] ?? 0);

        // Calculate system power draw
        $basePower = 75; // Motherboard, fans, etc.
        $cpuPower = 0;
        $gpuPower = 0;
        $otherPower = 20; // Storage, RAM

        if (isset($components['cpu'])) {
            $cpuPower = $this->parseWattage($components['cpu']['specs']['tdp'] ?? 65);
        }

        if (isset($components['gpu'])) {
            $gpuPower = $this->parseWattage($components['gpu']['specs']['tdp'] ?? 0);
        }

        $totalDraw = $basePower + $cpuPower + $gpuPower + $otherPower;
        $peakDraw = $totalDraw * 1.2; // 20% for transients

        $compatible = $psuWattage >= $totalDraw;
        $headroom = $psuWattage - $totalDraw;
        $headroomPercent = $psuWattage > 0 ? ($headroom / $psuWattage) * 100 : 0;

        return [
            'pass' => $compatible,
            'score' => $compatible ? min(1.0, $headroomPercent / 30) : 0.0,
            'message' => $compatible 
                ? "PSU adequate: {$psuWattage}W for {$totalDraw}W load ({$headroomPercent}% headroom)" 
                : "PSU too weak: {$psuWattage}W cannot power {$totalDraw}W system",
            'details' => [
                'psu_wattage' => $psuWattage,
                'total_draw' => $totalDraw,
                'peak_draw' => $peakDraw,
                'headroom' => $headroom,
                'headroom_percent' => round($headroomPercent, 1),
                'breakdown' => [
                    'base' => $basePower,
                    'cpu' => $cpuPower,
                    'gpu' => $gpuPower,
                    'other' => $otherPower
                ],
                'compatible' => $compatible
            ]
        ];
    }
}
