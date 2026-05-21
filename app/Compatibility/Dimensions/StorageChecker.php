<?php

namespace App\Compatibility\Dimensions;

use App\Compatibility\Contracts\DimensionChecker;

/**
 * Hard and Soft constraints for M.2 Slots and PCIe Generation checking
 */
class StorageChecker implements DimensionChecker
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
        return 'Storage Compatibility';
    }

    public function getComponentRoles(): array
    {
        return [
            'consumer' => 'storage',
            'provider' => 'motherboard'
        ];
    }

    private function parseGen(string $str): int
    {
        $str = strtoupper($str);
        if (str_contains($str, 'GEN5') || str_contains($str, '5.0')) return 5;
        if (str_contains($str, 'GEN4') || str_contains($str, '4.0')) return 4;
        if (str_contains($str, 'GEN3') || str_contains($str, '3.0')) return 3;
        return 4; // default fallback
    }

    public function check(array $components): array
    {
        $storage = $components['storage'] ?? null;
        $motherboard = $components['motherboard'] ?? null;
        $extraStorage = $components['extra_storage'] ?? [];

        $storageItems = [];
        if ($storage) {
            $storageItems[] = $storage;
        }
        foreach ($extraStorage as $item) {
            if ($item) {
                $storageItems[] = $item;
            }
        }

        if (empty($storageItems) || !$motherboard) {
            return [
                'pass' => true,
                'score' => 1.0,
                'message' => 'Waiting for components',
                'details' => ['skipped' => true]
            ];
        }

        // 1. Determine motherboard chipset & M.2 limit
        $chipset = strtoupper($motherboard['specs']['chipset'] ?? $motherboard['specs']['facts']['chipset'] ?? '');
        
        $maxM2Slots = 3; // Default
        if (preg_match('/(X670|Z790|Z690|X570|B650E|X870)/i', $chipset)) {
            $maxM2Slots = 4;
        } elseif (preg_match('/(B650|B760|B550|H770)/i', $chipset)) {
            $maxM2Slots = 3;
        } elseif (preg_match('/(H610|A620|A520|H510)/i', $chipset)) {
            $maxM2Slots = 2;
        }

        // 2. Count selected M.2 drives and check PCIe versions
        $m2Count = 0;
        $warnings = [];
        $mbPcie = intval($motherboard['specs']['pcie_version'] ?? $motherboard['specs']['facts']['pcie_version'] ?? 4);

        foreach ($storageItems as $item) {
            $interface = strtoupper($item['specs']['interface'] ?? $item['specs']['facts']['interface'] ?? '');
            $isM2 = str_contains($interface, 'NVME') || str_contains($interface, 'M.2') || ($item['specs']['needs']['m2_slot'] ?? 0) > 0;

            if ($isM2) {
                $m2Count++;
                
                // PCIe Gen Check
                $driveGen = $this->parseGen($interface);
                if ($driveGen > $mbPcie) {
                    $warnings[] = "{$item['name']} (PCIe Gen{$driveGen}) will downclock to Gen{$mbPcie} speeds on this motherboard";
                }
            }
        }

        // Check if M.2 count exceeds limit
        if ($m2Count > $maxM2Slots) {
            return [
                'pass' => false,
                'score' => 0.0,
                'message' => "Too many M.2 SSDs: Selected {$m2Count} M.2 drives, but Motherboard chipset ({$chipset}) only supports up to {$maxM2Slots} M.2 slots.",
                'details' => [
                    'm2_count' => $m2Count,
                    'max_slots' => $maxM2Slots,
                    'chipset' => $chipset,
                    'compatible' => false
                ]
            ];
        }

        if (!empty($warnings)) {
            return [
                'pass' => true,
                'score' => 0.85, // minor deduction for PCIe downclocking warning
                'message' => implode('; ', $warnings),
                'details' => [
                    'm2_count' => $m2Count,
                    'max_slots' => $maxM2Slots,
                    'warning' => true,
                    'compatible' => true
                ]
            ];
        }

        return [
            'pass' => true,
            'score' => 1.0,
            'message' => "Storage compatible: {$m2Count}/{$maxM2Slots} M.2 slots used",
            'details' => [
                'm2_count' => $m2Count,
                'max_slots' => $maxM2Slots,
                'compatible' => true
            ]
        ];
    }
}
