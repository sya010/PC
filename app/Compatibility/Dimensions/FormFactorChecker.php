<?php

namespace App\Compatibility\Dimensions;

use App\Compatibility\Contracts\DimensionChecker;

/**
 * Hard constraint: Motherboard form factor must fit in case
 */
class FormFactorChecker implements DimensionChecker
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
        return 'Form Factor';
    }

    public function getComponentRoles(): array
    {
        return [
            'consumer' => 'motherboard',
            'provider' => 'case'
        ];
    }

    public function check(array $components): array
    {
        $motherboard = $components['motherboard'] ?? null;
        $case = $components['case'] ?? null;

        if (!$motherboard || !$case) {
            return [
                'pass' => true,
                'score' => 1.0,
                'message' => 'Waiting for components',
                'details' => ['skipped' => true]
            ];
        }

        $mbFormFactor = strtoupper($motherboard['specs']['form_factor'] ?? 'ATX');
        $caseSupport = strtoupper($case['specs']['motherboard_support'] ?? 'ATX');

        // Normalize form factors
        $mbFormFactor = str_replace(['MICRO-ATX', 'MINI-ITX'], ['MATX', 'ITX'], $mbFormFactor);
        
        // Check if case supports the motherboard form factor
        $compatible = stripos($caseSupport, $mbFormFactor) !== false;

        return [
            'pass' => $compatible,
            'score' => $compatible ? 1.0 : 0.0,
            'message' => $compatible 
                ? "Form factor compatible: {$mbFormFactor}" 
                : "Form factor mismatch: {$mbFormFactor} won't fit (Case supports: {$caseSupport})",
            'details' => [
                'mb_form_factor' => $mbFormFactor,
                'case_support' => $caseSupport,
                'compatible' => $compatible
            ]
        ];
    }
}
