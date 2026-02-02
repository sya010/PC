<?php

namespace App\Compatibility\Dimensions;

use App\Compatibility\Contracts\DimensionChecker;

/**
 * Soft constraint: Cooler socket support
 */
class CoolerSocketChecker implements DimensionChecker
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
        return 'Cooler Compatibility';
    }

    public function getComponentRoles(): array
    {
        return [
            'consumer' => 'cooling',
            'provider' => 'cpu'
        ];
    }

    public function check(array $components): array
    {
        $cooling = $components['cooling'] ?? null;
        $cpu = $components['cpu'] ?? null;

        if (!$cooling || !$cpu) {
            return [
                'pass' => true,
                'score' => 1.0,
                'message' => 'Waiting for components',
                'details' => ['skipped' => true]
            ];
        }

        $supportedSockets = strtoupper($cooling['specs']['socket_support'] ?? '');
        $cpuSocket = strtoupper($cpu['specs']['socket'] ?? '');

        if (!$cpuSocket || !$supportedSockets) {
            return [
                'pass' => true,
                'score' => 0.7,
                'message' => 'Cooler socket support not specified',
                'details' => ['warning' => 'Unable to verify cooler compatibility']
            ];
        }

        $compatible = stripos($supportedSockets, $cpuSocket) !== false;

        return [
            'pass' => true, // Soft constraint
            'score' => $compatible ? 1.0 : 0.3,
            'message' => $compatible 
                ? "Cooler supports {$cpuSocket}" 
                : "Cooler may not support {$cpuSocket} - verify manually",
            'details' => [
                'cpu_socket' => $cpuSocket,
                'cooler_supports' => $supportedSockets,
                'compatible' => $compatible
            ]
        ];
    }
}
