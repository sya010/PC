<?php

namespace App\Compatibility\Dimensions;

use App\Compatibility\Contracts\DimensionChecker;

/**
 * Advisory: CPU/GPU performance balance (bottleneck detection)
 */
class BottleneckChecker implements DimensionChecker
{
    // Performance tier mapping (rough estimates)
    private array $cpuTiers = [
        'i9-14900' => 95, 'i9-13900' => 93, 'i7-14700' => 88, 'i7-13700' => 85,
        'i5-14600' => 78, 'i5-13600' => 75, 'i5-14400' => 70,
        '7950X3D' => 96, '7950X' => 94, '7900X3D' => 93, '7900X' => 90,
        '7800X3D' => 92, '7700X' => 82, '7600X' => 75, '7600' => 72,
        '5800X3D' => 85, '5900X' => 80, '5800X' => 75, '5600X' => 68,
    ];

    private array $gpuTiers = [
        '4090' => 100, '4080 Super' => 92, '4080' => 90, '4070 Ti Super' => 82,
        '4070 Super' => 78, '4070 Ti' => 80, '4070' => 72, '4060 Ti' => 62, '4060' => 55,
        '7900 XTX' => 88, '7900 XT' => 82, '7800 XT' => 70, '7700 XT' => 62, '7600' => 52,
        '3090 Ti' => 78, '3090' => 75, '3080 Ti' => 72, '3080' => 68, '3070 Ti' => 60,
    ];

    public function getTier(): string
    {
        return 'advisory';
    }

    public function getWeight(): float
    {
        return 0.10; // 10% weight (advisory)
    }

    public function getName(): string
    {
        return 'Performance Balance';
    }

    public function getComponentRoles(): array
    {
        return [
            'consumer' => 'gpu',
            'provider' => 'cpu'
        ];
    }

    private function getCpuTier(?string $name): int
    {
        if (!$name) return 70; // Default mid-tier
        foreach ($this->cpuTiers as $key => $tier) {
            if (stripos($name, $key) !== false) return $tier;
        }
        return 70;
    }

    private function getGpuTier(?string $name): int
    {
        if (!$name) return 70;
        foreach ($this->gpuTiers as $key => $tier) {
            if (stripos($name, $key) !== false) return $tier;
        }
        return 70;
    }

    public function check(array $components): array
    {
        $cpu = $components['cpu'] ?? null;
        $gpu = $components['gpu'] ?? null;

        if (!$cpu || !$gpu) {
            return [
                'pass' => true,
                'score' => 1.0,
                'message' => 'Waiting for CPU and GPU',
                'details' => ['skipped' => true]
            ];
        }

        $cpuTier = $this->getCpuTier($cpu['name'] ?? null);
        $gpuTier = $this->getGpuTier($gpu['name'] ?? null);

        $ratio = min($cpuTier, $gpuTier) / max($cpuTier, $gpuTier);
        $balancePercent = round($ratio * 100);

        $bottleneck = 'none';
        $advice = 'Well balanced build';
        
        if ($ratio < 0.70) {
            $bottleneck = $cpuTier < $gpuTier ? 'cpu' : 'gpu';
            $advice = $bottleneck === 'cpu' 
                ? 'CPU may limit GPU performance - consider upgrading CPU'
                : 'GPU may be underutilized - consider upgrading GPU';
        } elseif ($ratio < 0.85) {
            $bottleneck = $cpuTier < $gpuTier ? 'minor_cpu' : 'minor_gpu';
            $advice = 'Minor imbalance but acceptable';
        }

        return [
            'pass' => true,
            'score' => $ratio,
            'message' => "Balance: {$balancePercent}% - {$advice}",
            'details' => [
                'cpu_tier' => $cpuTier,
                'gpu_tier' => $gpuTier,
                'balance_ratio' => $ratio,
                'balance_percent' => $balancePercent,
                'bottleneck' => $bottleneck,
                'advice' => $advice
            ]
        ];
    }
}
