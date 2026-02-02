<?php

namespace App\Compatibility\Dimensions;

use App\Compatibility\Contracts\DimensionChecker;

/**
 * Hard constraint: RAM type must match Motherboard memory type
 */
class RamTypeChecker implements DimensionChecker
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
        return 'Memory Type';
    }

    public function getComponentRoles(): array
    {
        return [
            'consumer' => 'ram',
            'provider' => 'motherboard'
        ];
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

        $ramType = strtoupper($ram['specs']['type'] ?? '');
        $mbType = strtoupper($motherboard['specs']['memory_type'] ?? '');

        if (!$ramType || !$mbType) {
            return [
                'pass' => true,
                'score' => 0.5,
                'message' => 'Memory type information missing',
                'details' => ['warning' => 'Unable to verify memory compatibility']
            ];
        }

        $compatible = $ramType === $mbType;

        return [
            'pass' => $compatible,
            'score' => $compatible ? 1.0 : 0.0,
            'message' => $compatible 
                ? "Memory type match: {$ramType}" 
                : "Memory type mismatch: RAM is {$ramType}, Motherboard supports {$mbType}",
            'details' => [
                'ram_type' => $ramType,
                'mb_type' => $mbType,
                'compatible' => $compatible
            ]
        ];
    }
}
