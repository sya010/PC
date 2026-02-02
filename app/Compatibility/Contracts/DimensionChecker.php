<?php

namespace App\Compatibility\Contracts;

/**
 * Interface for dimension checkers
 * Each checker validates one compatibility dimension
 */
interface DimensionChecker
{
    /**
     * Get the tier of this checker
     * 'hard' = must pass or total failure
     * 'soft' = affects score proportionally
     * 'advisory' = generates recommendations only
     */
    public function getTier(): string;

    /**
     * Get the weight of this dimension (for soft constraints)
     * Range: 0.0 to 1.0
     */
    public function getWeight(): float;

    /**
     * Get the name of this dimension for display
     */
    public function getName(): string;

    /**
     * Check compatibility between components
     * 
     * @param array $components All selected components ['cpu' => [...], 'motherboard' => [...], ...]
     * @return array ['pass' => bool, 'score' => float 0-1, 'message' => string, 'details' => array]
     */
    public function check(array $components): array;

    /**
     * Get which components this checker needs
     * @return array ['consumer' => 'cpu', 'provider' => 'motherboard']
     */
    public function getComponentRoles(): array;
}
