<?php

namespace App\Compatibility\Dimensions;

use App\Compatibility\Contracts\DimensionChecker;

/**
 * Soft constraint: PSU headroom (20%+ recommended)
 */
class PsuHeadroomChecker implements DimensionChecker
{
    public function getTier(): string
    {
        return 'soft';
    }

    public function getWeight(): float
    {
        return 0.20; // 20% weight
    }

    public function getName(): string
    {
        return 'PSU Headroom';
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

        // Calculate system power
        $totalDraw = 75; // Base
        if (isset($components['cpu'])) {
            $totalDraw += $this->parseWattage($components['cpu']['specs']['tdp'] ?? 65);
        }
        if (isset($components['gpu'])) {
            $totalDraw += $this->parseWattage($components['gpu']['specs']['tdp'] ?? 0);
        }
        $totalDraw += 20; // Storage, RAM

        $headroom = $psuWattage - $totalDraw;
        $headroomPercent = $psuWattage > 0 ? ($headroom / $psuWattage) * 100 : 0;

        // Score: 0% headroom = 0.5, 20%+ = 1.0
        $score = min(1.0, 0.5 + ($headroomPercent / 40));

        $status = 'tight';
        if ($headroomPercent >= 30) $status = 'excellent';
        elseif ($headroomPercent >= 20) $status = 'good';
        elseif ($headroomPercent >= 10) $status = 'adequate';

        return [
            'pass' => true,
            'score' => $score,
            'message' => ucfirst($status) . " headroom: " . round($headroomPercent) . "%",
            'details' => [
                'psu_wattage' => $psuWattage,
                'system_draw' => $totalDraw,
                'headroom_watts' => $headroom,
                'headroom_percent' => round($headroomPercent, 1),
                'status' => $status
            ]
        ];
    }
}
