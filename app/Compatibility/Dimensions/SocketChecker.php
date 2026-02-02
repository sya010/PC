<?php

namespace App\Compatibility\Dimensions;

use App\Compatibility\Contracts\DimensionChecker;

/**
 * Hard constraint: CPU socket must match Motherboard socket
 */
class SocketChecker implements DimensionChecker
{
    public function getTier(): string
    {
        return 'hard';
    }

    public function getWeight(): float
    {
        return 1.0; // Critical - no weight needed, it's pass/fail
    }

    public function getName(): string
    {
        return 'CPU Socket';
    }

    public function getComponentRoles(): array
    {
        return [
            'consumer' => 'cpu',
            'provider' => 'motherboard'
        ];
    }

    public function check(array $components): array
    {
        $cpu = $components['cpu'] ?? null;
        $motherboard = $components['motherboard'] ?? null;

        // If either component is missing, skip this check
        if (!$cpu || !$motherboard) {
            return [
                'pass' => true,
                'score' => 1.0,
                'message' => 'Waiting for components',
                'details' => ['skipped' => true]
            ];
        }

        $cpuSocket = $cpu['specs']['socket'] ?? null;
        $mbSocket = $motherboard['specs']['socket'] ?? null;

        if (!$cpuSocket || !$mbSocket) {
            return [
                'pass' => true,
                'score' => 0.5,
                'message' => 'Socket information missing',
                'details' => ['warning' => 'Unable to verify socket compatibility']
            ];
        }

        $compatible = strtoupper($cpuSocket) === strtoupper($mbSocket);

        return [
            'pass' => $compatible,
            'score' => $compatible ? 1.0 : 0.0,
            'message' => $compatible 
                ? "Socket match: {$cpuSocket}" 
                : "Socket mismatch: CPU is {$cpuSocket}, Motherboard is {$mbSocket}",
            'details' => [
                'cpu_socket' => $cpuSocket,
                'mb_socket' => $mbSocket,
                'compatible' => $compatible
            ]
        ];
    }
}
