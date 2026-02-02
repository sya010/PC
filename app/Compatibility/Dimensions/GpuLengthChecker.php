<?php

namespace App\Compatibility\Dimensions;

use App\Compatibility\Contracts\DimensionChecker;

/**
 * Hard constraint: GPU length must fit in case
 */
class GpuLengthChecker implements DimensionChecker
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
        return 'GPU Clearance';
    }

    public function getComponentRoles(): array
    {
        return [
            'consumer' => 'gpu',
            'provider' => 'case'
        ];
    }

    private function parseLength($value): float
    {
        if (is_numeric($value)) return (float) $value;
        return (float) preg_replace('/[^0-9.]/', '', $value);
    }

    public function check(array $components): array
    {
        $gpu = $components['gpu'] ?? null;
        $case = $components['case'] ?? null;

        if (!$gpu || !$case) {
            return [
                'pass' => true,
                'score' => 1.0,
                'message' => 'Waiting for components',
                'details' => ['skipped' => true]
            ];
        }

        $gpuLength = $this->parseLength($gpu['specs']['length'] ?? 0);
        $maxLength = $this->parseLength($case['specs']['max_gpu_length'] ?? 999);

        if ($gpuLength <= 0 || $maxLength <= 0) {
            return [
                'pass' => true,
                'score' => 0.5,
                'message' => 'GPU length information missing',
                'details' => ['warning' => 'Unable to verify GPU clearance']
            ];
        }

        $compatible = $gpuLength <= $maxLength;
        $clearance = $maxLength - $gpuLength;

        return [
            'pass' => $compatible,
            'score' => $compatible ? min(1.0, $clearance / 50 + 0.7) : 0.0, // More clearance = better score
            'message' => $compatible 
                ? "GPU fits: {$gpuLength}mm (clearance: {$clearance}mm)" 
                : "GPU too long: {$gpuLength}mm exceeds case max {$maxLength}mm",
            'details' => [
                'gpu_length' => $gpuLength,
                'case_max' => $maxLength,
                'clearance' => $clearance,
                'compatible' => $compatible
            ]
        ];
    }
}
