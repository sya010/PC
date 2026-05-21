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

        $mbType = strtoupper($motherboard['specs']['memory_type'] ?? '');

        // Standardize form factor/memory types if they have extra text
        if (str_contains($mbType, 'DDR5')) $mbType = 'DDR5';
        elseif (str_contains($mbType, 'DDR4')) $mbType = 'DDR4';
        elseif (str_contains($mbType, 'DDR3')) $mbType = 'DDR3';

        if (!$mbType) {
            return [
                'pass' => true,
                'score' => 0.5,
                'message' => 'Memory type information missing',
                'details' => ['warning' => 'Unable to verify memory compatibility']
            ];
        }

        $incompatibleRams = [];
        $mixMatchedRams = false;
        $firstRamType = null;

        foreach ($ramItems as $item) {
            $ramType = strtoupper($item['specs']['type'] ?? '');
            if (str_contains($ramType, 'DDR5')) $ramType = 'DDR5';
            elseif (str_contains($ramType, 'DDR4')) $ramType = 'DDR4';
            elseif (str_contains($ramType, 'DDR3')) $ramType = 'DDR3';

            if (!$ramType) {
                continue;
            }

            if ($ramType !== $mbType) {
                $incompatibleRams[] = $item['name'] . " ({$ramType})";
            }

            if ($firstRamType === null) {
                $firstRamType = $ramType;
            } elseif ($ramType !== $firstRamType) {
                $mixMatchedRams = true;
            }
        }

        if (!empty($incompatibleRams)) {
            $names = implode(', ', $incompatibleRams);
            return [
                'pass' => false,
                'score' => 0.0,
                'message' => "Memory type mismatch: {$names} incompatible with motherboard supporting {$mbType}",
                'details' => [
                    'mb_type' => $mbType,
                    'compatible' => false
                ]
            ];
        }

        if ($mixMatchedRams) {
            return [
                'pass' => false,
                'score' => 0.0,
                'message' => "Memory type mismatch: Cannot mix different memory generations (DDR4/DDR5) in the same build",
                'details' => [
                    'mb_type' => $mbType,
                    'compatible' => false
                ]
            ];
        }

        $ramTypeStr = $firstRamType ?? $mbType;
        return [
            'pass' => true,
            'score' => 1.0,
            'message' => "Memory type match: {$ramTypeStr}",
            'details' => [
                'mb_type' => $mbType,
                'ram_type' => $ramTypeStr,
                'compatible' => true
            ]
        ];
    }
}
