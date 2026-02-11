<?php

declare(strict_types=1);

namespace App\Services\Chatbot;

/**
 * Hardware Knowledge - PC compatibility rules and recommendations
 */
class HardwareKnowledge
{
    private array $socketCompatibility = [
        'intel' => ['LGA1700' => ['12th', '13th', '14th'], 'LGA1200' => ['10th', '11th']],
        'amd' => ['AM5' => ['7000'], 'AM4' => ['3000', '5000']],
    ];

    private array $ramCompatibility = [
        'DDR4' => ['B450', 'B550', 'X570', 'Z490', 'Z590', 'B560', 'H510'],
        'DDR5' => ['B650', 'X670', 'Z690', 'Z790', 'B760', 'H770'],
    ];

    private array $gpuPowerRequirements = [
        'entry' => ['min' => 450, 'max' => 550, 'examples' => ['GTX 1650', 'RX 6500']],
        'mid' => ['min' => 550, 'max' => 650, 'examples' => ['RTX 3060', 'RX 6700']],
        'high' => ['min' => 650, 'max' => 850, 'examples' => ['RTX 4070', 'RX 7800']],
        'enthusiast' => ['min' => 850, 'max' => 1200, 'examples' => ['RTX 4090', 'RX 7900']],
    ];

    public function checkCompatibility(array $components): array
    {
        if (isset($components['socket']) && isset($components['cpu'])) {
            return $this->checkSocketCompatibility($components['cpu'], $components['socket']);
        }
        
        if (isset($components['cpu']) && isset($components['motherboard'])) {
            return $this->checkCpuMotherboard($components['cpu'], $components['motherboard']);
        }

        return ['compatible' => true, 'message' => 'Unable to determine compatibility with provided info.'];
    }

    private function checkSocketCompatibility(string $cpu, string $socket): array
    {
        $socket = strtoupper($socket);
        $isIntel = preg_match('/i[3579]/i', $cpu);
        $isAmd = preg_match('/ryzen/i', $cpu);
        
        if ($isIntel && str_starts_with($socket, 'AM')) {
            return [
                'compatible' => false,
                'message' => "Intel CPUs are not compatible with AMD {$socket} sockets. Intel uses LGA sockets.",
                'alternatives' => ['LGA1700', 'LGA1200'],
            ];
        }
        
        if ($isAmd && str_starts_with($socket, 'LGA')) {
            return [
                'compatible' => false,
                'message' => "AMD Ryzen CPUs are not compatible with Intel {$socket} sockets. AMD uses AM4/AM5.",
                'alternatives' => ['AM5', 'AM4'],
            ];
        }

        return ['compatible' => true, 'message' => "The CPU and socket appear to be from the same platform."];
    }

    private function checkCpuMotherboard(string $cpu, string $motherboard): array
    {
        $isIntel = preg_match('/i[3579]/i', $cpu);
        $isAmd = preg_match('/ryzen/i', $cpu);
        $isAmdBoard = preg_match('/b[456][05]0|x[56]70/i', $motherboard);
        $isIntelBoard = preg_match('/z[67]90|b[567]60|h[567]10/i', $motherboard);

        if ($isIntel && $isAmdBoard) {
            return [
                'compatible' => false,
                'message' => "Intel CPUs cannot be used with AMD chipset motherboards.",
            ];
        }
        
        if ($isAmd && $isIntelBoard) {
            return [
                'compatible' => false,
                'message' => "AMD Ryzen CPUs cannot be used with Intel chipset motherboards.",
            ];
        }

        return ['compatible' => true, 'message' => "These components appear to be compatible."];
    }

    public function getRecommendedPsu(string $gpuTier): array
    {
        return $this->gpuPowerRequirements[$gpuTier] ?? $this->gpuPowerRequirements['mid'];
    }

    public function getRamTypeForBoard(string $chipset): string
    {
        $chipset = strtoupper($chipset);
        foreach ($this->ramCompatibility as $ramType => $chipsets) {
            if (in_array($chipset, $chipsets)) {
                return $ramType;
            }
        }
        return 'DDR4';
    }
}
