<?php

declare(strict_types=1);

namespace App\Services;

use App\Compatibility\Contracts\DimensionChecker;
use App\Compatibility\Dimensions\{
    SocketChecker,
    RamTypeChecker,
    GpuLengthChecker,
    FormFactorChecker,
    PsuCapacityChecker,
    RamSpeedChecker,
    PsuHeadroomChecker,
    CoolerSocketChecker,
    BottleneckChecker,
    StorageChecker
};

/**
 * Main Compatibility Engine
 * 
 * Processes all dimension checkers and produces:
 * - Overall compatibility score (0-100%)
 * - Detailed results per dimension
 * - System-level analysis
 */
class CompatibilityEngine
{
    /** @var DimensionChecker[] */
    private array $checkers;

    public function __construct()
    {
        // Register all dimension checkers
        $this->checkers = [
            // Hard constraints (must pass)
            new SocketChecker(),
            new RamTypeChecker(),
            new GpuLengthChecker(),
            new FormFactorChecker(),
            new PsuCapacityChecker(),
            new StorageChecker(),
            
            // Soft constraints (affect score)
            new RamSpeedChecker(),
            new PsuHeadroomChecker(),
            new CoolerSocketChecker(),
            
            // Advisory (recommendations)
            new BottleneckChecker(),
        ];
    }

    /**
     * Evaluate compatibility of all selected components
     * 
     * @param array $components ['cpu' => [...], 'motherboard' => [...], ...]
     * @return array Complete compatibility report
     */
    public function evaluate(array $components): array
    {
        $results = [
            'overall_score' => 100,
            'status' => 'compatible',
            'status_label' => 'Compatible',
            'status_color' => 'green',
            'hard_constraints' => [],
            'soft_constraints' => [],
            'advisory' => [],
            'all_hard_passed' => true,
            'warnings' => [],
            'errors' => [],
            'recommendations' => [],
            'system_analysis' => $this->analyzeSystem($components),
        ];

        $softScores = [];
        $softWeights = [];

        foreach ($this->checkers as $checker) {
            $result = $checker->check($components);
            
            // Skip if components not selected yet
            if ($result['details']['skipped'] ?? false) {
                continue;
            }

            $dimensionResult = [
                'name' => $checker->getName(),
                'tier' => $checker->getTier(),
                'pass' => $result['pass'],
                'score' => $result['score'],
                'message' => $result['message'],
                'details' => $result['details'],
                'roles' => $checker->getComponentRoles(),
            ];

            switch ($checker->getTier()) {
                case 'hard':
                    $results['hard_constraints'][] = $dimensionResult;
                    if (!$result['pass']) {
                        $results['all_hard_passed'] = false;
                        $results['errors'][] = $result['message'];
                    }
                    break;
                    
                case 'soft':
                    $results['soft_constraints'][] = $dimensionResult;
                    $softScores[] = $result['score'];
                    $softWeights[] = $checker->getWeight();
                    if ($result['score'] < 0.7) {
                        $results['warnings'][] = $result['message'];
                    }
                    break;
                    
                case 'advisory':
                    $results['advisory'][] = $dimensionResult;
                    if ($result['score'] < 0.85) {
                        $results['recommendations'][] = $result['message'];
                    }
                    break;
            }
        }

        // Calculate final score
        if (!$results['all_hard_passed']) {
            $results['overall_score'] = 0;
            $results['status'] = 'incompatible';
            $results['status_label'] = 'Incompatible';
            $results['status_color'] = 'red';
        } else {
            // Calculate weighted soft score
            $softScore = $this->calculateWeightedScore($softScores, $softWeights);
            $results['overall_score'] = round($softScore * 100);
            
            // Determine status
            if ($results['overall_score'] >= 85) {
                $results['status'] = 'excellent';
                $results['status_label'] = 'Excellent';
                $results['status_color'] = 'green';
            } elseif ($results['overall_score'] >= 70) {
                $results['status'] = 'good';
                $results['status_label'] = 'Good';
                $results['status_color'] = 'green';
            } elseif ($results['overall_score'] >= 50) {
                $results['status'] = 'acceptable';
                $results['status_label'] = 'Acceptable';
                $results['status_color'] = 'yellow';
            } else {
                $results['status'] = 'poor';
                $results['status_label'] = 'Not Ideal';
                $results['status_color'] = 'orange';
            }
        }

        return $results;
    }

    /**
     * Calculate weighted average score
     */
    private function calculateWeightedScore(array $scores, array $weights): float
    {
        if (empty($scores)) {
            return 1.0;
        }

        $totalWeight = array_sum($weights);
        if ($totalWeight <= 0) {
            return array_sum($scores) / count($scores);
        }

        $weightedSum = 0;
        foreach ($scores as $i => $score) {
            $weightedSum += $score * ($weights[$i] ?? 0);
        }

        return $weightedSum / $totalWeight;
    }

    /**
     * Analyze system-level metrics
     */
    private function analyzeSystem(array $components): array
    {
        return [
            'power' => $this->analyzePower($components),
            'thermal' => $this->analyzeThermal($components),
            'balance' => $this->analyzeBalance($components),
        ];
    }

    /**
     * Analyze power budget
     */
    private function analyzePower(array $components): array
    {
        $breakdown = [
            'base' => 75,
            'cpu' => 0,
            'gpu' => 0,
            'ram' => 5,
            'storage' => 10,
            'cooling' => 10,
        ];

        if (isset($components['cpu'])) {
            $breakdown['cpu'] = $this->parseWattage($components['cpu']['specs']['tdp'] ?? 65);
        }
        if (isset($components['gpu'])) {
            $breakdown['gpu'] = $this->parseWattage($components['gpu']['specs']['tdp'] ?? 0);
        }

        $total = array_sum($breakdown);
        $peak = $total * 1.2;

        $psuWattage = 0;
        if (isset($components['psu'])) {
            $psuWattage = $this->parseWattage($components['psu']['specs']['wattage'] ?? 0);
        }

        $headroom = $psuWattage > 0 ? (($psuWattage - $total) / $psuWattage) * 100 : 0;

        return [
            'breakdown' => $breakdown,
            'total_draw' => $total,
            'peak_draw' => round($peak),
            'psu_capacity' => $psuWattage,
            'headroom_percent' => round($headroom, 1),
            'status' => $headroom >= 20 ? 'good' : ($headroom >= 0 ? 'tight' : 'insufficient'),
        ];
    }

    /**
     * Analyze thermal requirements
     */
    private function analyzeThermal(array $components): array
    {
        $heatLoad = 0;
        $coolerCapacity = 0;

        if (isset($components['cpu'])) {
            $heatLoad += $this->parseWattage($components['cpu']['specs']['tdp'] ?? 65);
        }
        if (isset($components['gpu'])) {
            $heatLoad += $this->parseWattage($components['gpu']['specs']['tdp'] ?? 0);
        }

        if (isset($components['cooling'])) {
            $coolerType = $components['cooling']['specs']['type'] ?? '';
            $radiatorSize = $this->parseWattage($components['cooling']['specs']['radiator_size'] ?? 0);
            
            // Estimate cooler capacity based on type/size
            if (stripos($coolerType, '360') !== false || $radiatorSize >= 360) {
                $coolerCapacity = 280;
            } elseif (stripos($coolerType, '280') !== false || $radiatorSize >= 280) {
                $coolerCapacity = 220;
            } elseif (stripos($coolerType, '240') !== false || $radiatorSize >= 240) {
                $coolerCapacity = 180;
            } elseif (stripos($coolerType, 'Air') !== false) {
                $coolerCapacity = 150;
            } else {
                $coolerCapacity = 120;
            }
        }

        $cpuTdp = isset($components['cpu']) ? $this->parseWattage($components['cpu']['specs']['tdp'] ?? 65) : 0;

        return [
            'total_heat_load' => $heatLoad,
            'cpu_tdp' => $cpuTdp,
            'cooler_capacity' => $coolerCapacity,
            'adequate' => $coolerCapacity >= $cpuTdp,
            'status' => $coolerCapacity >= $cpuTdp * 1.2 ? 'excellent' : ($coolerCapacity >= $cpuTdp ? 'adequate' : 'insufficient'),
        ];
    }

    /**
     * Analyze performance balance
     */
    private function analyzeBalance(array $components): array
    {
        $cpuTier = 70;
        $gpuTier = 70;

        if (isset($components['cpu'])) {
            $cpuTier = $this->estimatePerformanceTier($components['cpu']['name'] ?? '', 'cpu');
        }
        if (isset($components['gpu'])) {
            $gpuTier = $this->estimatePerformanceTier($components['gpu']['name'] ?? '', 'gpu');
        }

        $ratio = min($cpuTier, $gpuTier) / max($cpuTier, $gpuTier);

        return [
            'cpu_tier' => $cpuTier,
            'gpu_tier' => $gpuTier,
            'ratio' => round($ratio * 100),
            'bottleneck' => $ratio >= 0.85 ? 'none' : ($cpuTier < $gpuTier ? 'cpu' : 'gpu'),
            'status' => $ratio >= 0.85 ? 'balanced' : 'imbalanced',
        ];
    }

    private function parseWattage($value): float
    {
        if (is_numeric($value)) return (float) $value;
        return (float) preg_replace('/[^0-9.]/', '', $value);
    }

    private function estimatePerformanceTier(string $name, string $type): int
    {
        $tiers = $type === 'cpu' ? [
            'i9-14900' => 95, 'i9-13900' => 93, 'i7-14700' => 88, 'i7-13700' => 85,
            '7950X3D' => 96, '7950X' => 94, '7900X3D' => 93, '7900X' => 90,
            '7800X3D' => 92, '7700X' => 82, '7600X' => 75, '7600' => 72,
        ] : [
            '4090' => 100, '4080 Super' => 92, '4080' => 90, '4070 Super' => 78,
            '7900 XTX' => 88, '7900 XT' => 82, '7800 XT' => 70,
        ];

        foreach ($tiers as $key => $tier) {
            if (stripos($name, (string) $key) !== false) return $tier;
        }

        return 70;
    }
}
