<?php

namespace App\Livewire;


use Livewire\Component;
use Livewire\WithPagination;
use App\Traits\ManageCart;
use App\Models\Product;
use App\Services\CompatibilityEngine;

class PcBuilder extends Component
{
    use ManageCart;
    use WithPagination;

    public $selectedComponents = [
        'cpu' => null,
        'motherboard' => null,
        'ram' => null,
        'gpu' => null,
        'storage' => null,
        'psu' => null,
        'case' => null,
        'cooling' => null,
        'monitor' => null,
        'keyboard' => null,
        'mouse' => null,
        'headset' => null,
        'mousepad' => null,
        'microphone' => null,
        'webcam' => null,
        'speakers' => null,
    ];

    // Quantity support for RAM, Storage, and Cooling
    public $componentQuantities = [
        'ram' => 1,
        'storage' => 1,
        'cooling' => 1,
    ];

    // Extra component slots for storage and cooling (multiple different products)
    // Structure: ['storage' => [0 => ['id'=>..., 'name'=>..., ...], 1 => [...]], 'cooling' => [...]]
    public $extraComponents = [
        'storage' => [],
        'cooling' => [],
    ];

    // Quantities for extra component slots
    // Structure: ['storage' => [0 => 1, 1 => 2], 'cooling' => [0 => 1]]
    public $extraQuantities = [
        'storage' => [],
        'cooling' => [],
    ];

    public $search = '';
    public ?string $activeSelection = null;
    public float $totalPrice = 0;
    public array $compatibilityWarnings = [];
    
    // Advanced compatibility
    public int $compatibilityScore = 100;
    public string $compatibilityStatus = 'compatible';
    public string $compatibilityLabel = 'Compatible';
    public string $compatibilityColor = 'green';
    public array $compatibilityReport = [];
    public array $systemAnalysis = [];

    // Reset Modal Properties
    public bool $showResetModal = false;
    public string $resetTarget = 'all'; // 'all', 'core', 'peripherals'

    // Keys mapping for fetching products by category
    protected $categoryMap = [
        'cpu' => 'CPU',
        'motherboard' => 'Motherboard',
        'gpu' => 'GPU',
        'ram' => 'RAM',
        'storage' => 'Storage',
        'psu' => 'PSU',
        'case' => 'Case',
        'cooling' => 'Cooling',
        'monitor' => 'Monitor',
        'keyboard' => 'Keyboard',
        'mouse' => 'Mouse',
        'headset' => 'Headset',
        'mousepad' => 'Mousepad',
        'microphone' => 'Microphone',
        'webcam' => 'Webcam',
        'speakers' => 'Speakers',
    ];

    // Types that support quantity selection
    protected $quantityTypes = ['ram', 'storage', 'cooling'];

    // Types that support multiple different selections (extra slots)
    protected $multiSlotTypes = ['storage', 'cooling'];

    // Maximum number of extra slots per type
    protected $maxExtraSlots = 4;

    public function mount(): void
    {
        // Load build from session
        $this->selectedComponents = array_merge(
            $this->selectedComponents, 
            session()->get('pc_build', [])
        );

        // Load quantities from session
        $this->componentQuantities = array_merge(
            $this->componentQuantities,
            session()->get('pc_build_quantities', [])
        );

        // Load extra components from session
        $sessionExtras = session()->get('pc_build_extras', []);
        $this->extraComponents = array_merge($this->extraComponents, $sessionExtras);

        // Load extra quantities from session
        $sessionExtraQty = session()->get('pc_build_extra_quantities', []);
        $this->extraQuantities = array_merge($this->extraQuantities, $sessionExtraQty);

        $this->checkCompatibility();
        $this->calculateTotal();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }
    
    // openSelector, closeSelector, and selectComponent logic removed/moved to ComponentSelector page logic

    /**
     * Check if a component type supports quantity selection.
     */
    public function isQuantityType(string $type): bool
    {
        return in_array($type, $this->quantityTypes);
    }

    /**
     * Check if a component type supports multiple different selections.
     */
    public function isMultiSlotType(string $type): bool
    {
        return in_array($type, $this->multiSlotTypes);
    }

    /**
     * Get the quantity for a component type.
     */
    public function getQuantity(string $type): int
    {
        return $this->componentQuantities[$type] ?? 1;
    }

    /**
     * Get the quantity for an extra slot.
     */
    public function getExtraQuantity(string $type, int $index): int
    {
        return $this->extraQuantities[$type][$index] ?? 1;
    }

    /**
     * Increment the quantity for a component type.
     */
    public function incrementQuantity(string $type): void
    {
        if (!$this->isQuantityType($type)) return;
        
        $max = 8;
        if (($this->componentQuantities[$type] ?? 1) < $max) {
            $this->componentQuantities[$type] = ($this->componentQuantities[$type] ?? 1) + 1;
            $this->saveQuantities();
            $this->calculateTotal();
        }
    }

    /**
     * Decrement the quantity for a component type.
     */
    public function decrementQuantity(string $type): void
    {
        if (!$this->isQuantityType($type)) return;
        
        if (($this->componentQuantities[$type] ?? 1) > 1) {
            $this->componentQuantities[$type] = ($this->componentQuantities[$type] ?? 1) - 1;
            $this->saveQuantities();
            $this->calculateTotal();
        }
    }

    /**
     * Increment the quantity for an extra slot.
     */
    public function incrementExtraQuantity(string $type, int $index): void
    {
        $max = 8;
        $current = $this->extraQuantities[$type][$index] ?? 1;
        if ($current < $max) {
            $this->extraQuantities[$type][$index] = $current + 1;
            $this->saveExtraQuantities();
            $this->calculateTotal();
        }
    }

    /**
     * Decrement the quantity for an extra slot.
     */
    public function decrementExtraQuantity(string $type, int $index): void
    {
        $current = $this->extraQuantities[$type][$index] ?? 1;
        if ($current > 1) {
            $this->extraQuantities[$type][$index] = $current - 1;
            $this->saveExtraQuantities();
            $this->calculateTotal();
        }
    }

    /**
     * Remove an extra component slot.
     */
    public function removeExtraComponent(string $type, int $index): void
    {
        if (isset($this->extraComponents[$type][$index])) {
            // Remove the component and its quantity
            array_splice($this->extraComponents[$type], $index, 1);
            
            if (isset($this->extraQuantities[$type][$index])) {
                array_splice($this->extraQuantities[$type], $index, 1);
            }
            
            // Re-index arrays
            $this->extraComponents[$type] = array_values($this->extraComponents[$type]);
            $this->extraQuantities[$type] = array_values($this->extraQuantities[$type]);
            
            $this->saveExtras();
            $this->saveExtraQuantities();
            $this->calculateTotal();
        }
    }

    /**
     * Check how many extra slots are used for a type.
     */
    public function getExtraCount(string $type): int
    {
        return count($this->extraComponents[$type] ?? []);
    }

    /**
     * Check if more extra slots can be added for a type.
     */
    public function canAddExtra(string $type): bool
    {
        return $this->isMultiSlotType($type) 
            && ($this->selectedComponents[$type] !== null) 
            && ($this->getExtraCount($type) < $this->maxExtraSlots);
    }

    /**
     * Save quantities to session.
     */
    protected function saveQuantities(): void
    {
        session()->put('pc_build_quantities', $this->componentQuantities);
    }

    /**
     * Save extra components to session.
     */
    protected function saveExtras(): void
    {
        session()->put('pc_build_extras', $this->extraComponents);
    }

    /**
     * Save extra quantities to session.
     */
    protected function saveExtraQuantities(): void
    {
        session()->put('pc_build_extra_quantities', $this->extraQuantities);
    }
    
    public function removeComponent(string $type)
    {
        $this->selectedComponents[$type] = null;
        
        // Reset quantity when removing
        if ($this->isQuantityType($type)) {
            $this->componentQuantities[$type] = 1;
            $this->saveQuantities();
        }

        // Also remove all extra slots for this type
        if ($this->isMultiSlotType($type)) {
            $this->extraComponents[$type] = [];
            $this->extraQuantities[$type] = [];
            $this->saveExtras();
            $this->saveExtraQuantities();
        }

        // Update session
        $build = session()->get('pc_build', []);
        unset($build[$type]);
        session()->put('pc_build', $build);

        $this->checkCompatibility();
        $this->calculateTotal();
    }

    public function confirmReset(string $target = 'all')
    {
        $this->resetTarget = $target;
        $this->showResetModal = true;
    }

    public function cancelReset()
    {
        $this->showResetModal = false;
        $this->resetTarget = 'all';
    }

    public function executeReset()
    {
        $coreKeys = ['cpu', 'motherboard', 'gpu', 'ram', 'storage', 'cooling', 'psu', 'case'];
        $peripheralKeys = ['monitor', 'keyboard', 'mouse', 'headset', 'mousepad', 'microphone', 'webcam', 'speakers'];

        if ($this->resetTarget === 'all' || $this->resetTarget === 'core') {
            foreach ($coreKeys as $key) {
                $this->selectedComponents[$key] = null;
            }
            $this->componentQuantities['ram'] = 1;
            $this->componentQuantities['storage'] = 1;
            $this->componentQuantities['cooling'] = 1;
            $this->extraComponents['storage'] = [];
            $this->extraComponents['cooling'] = [];
            $this->extraQuantities['storage'] = [];
            $this->extraQuantities['cooling'] = [];
        }

        if ($this->resetTarget === 'all' || $this->resetTarget === 'peripherals') {
            foreach ($peripheralKeys as $key) {
                $this->selectedComponents[$key] = null;
            }
        }

        // Update session
        session()->put('pc_build', array_filter($this->selectedComponents));
        session()->put('pc_build_quantities', $this->componentQuantities);
        session()->put('pc_build_extras', $this->extraComponents);
        session()->put('pc_build_extra_quantities', $this->extraQuantities);

        $this->checkCompatibility();
        $this->calculateTotal();
        
        $this->showResetModal = false;
        
        $message = 'PC Build reset successfully.';
        if ($this->resetTarget === 'core') $message = 'Core System reset successfully.';
        if ($this->resetTarget === 'peripherals') $message = 'Peripherals reset successfully.';
        
        $this->dispatch('notify', message: $message);
    }

    public function checkCompatibility()
    {
        $engine = new CompatibilityEngine();
        $report = $engine->evaluate($this->selectedComponents);

        $this->compatibilityScore = $report['overall_score'];
        $this->compatibilityStatus = $report['status'];
        $this->compatibilityLabel = $report['status_label'];
        $this->compatibilityColor = $report['status_color'];
        $this->compatibilityWarnings = array_merge($report['errors'], $report['warnings'], $report['recommendations']);
        
        // Transform warnings to match view expectation structure if needed
        // The view expects: ['type' => 'error|warning|info', 'message' => '...', 'components' => [...]]
        // But CompatibilityEngine returns arrays of strings or detailed objects. 
        // Let's normalize based on CompatibilityEngine output which returns detailed objects in *_constraints arrays
        
        $flattenedWarnings = [];
        
        foreach ($report['hard_constraints'] as $c) {
            if (!$c['pass']) {
                $flattenedWarnings[] = [
                    'type' => 'error',
                    'message' => $c['message'],
                    'components' => $c['roles']
                ];
            }
        }
        
        foreach ($report['soft_constraints'] as $c) {
            if ($c['score'] < 0.7) {
                $flattenedWarnings[] = [
                    'type' => 'warning',
                    'message' => $c['message'],
                    'components' => $c['roles']
                ];
            }
        }

         foreach ($report['advisory'] as $c) {
            if ($c['score'] < 0.85) {
                $flattenedWarnings[] = [
                    'type' => 'info',
                    'message' => $c['message'],
                    'components' => $c['roles']
                ];
            }
        }

        $this->compatibilityWarnings = $flattenedWarnings;
        $this->systemAnalysis = $report['system_analysis'];
    }

    public function calculateTotal()
    {
        $this->totalPrice = 0;
        
        // Primary components
        foreach ($this->selectedComponents as $type => $component) {
            if ($component !== null) {
                $quantity = $this->isQuantityType($type) ? ($this->componentQuantities[$type] ?? 1) : 1;
                $this->totalPrice += $component['price'] * $quantity;
            }
        }

        // Extra components (storage, cooling)
        foreach ($this->extraComponents as $type => $extras) {
            foreach ($extras as $index => $extra) {
                $quantity = $this->extraQuantities[$type][$index] ?? 1;
                $this->totalPrice += $extra['price'] * $quantity;
            }
        }
    }

    public function getSelectedCount(): int
    {
        $count = collect($this->selectedComponents)
            ->whereNotNull()
            ->count();

        // Count extras too
        foreach ($this->extraComponents as $extras) {
            $count += count($extras);
        }

        return $count;
    }

    public function isValidBuild(): bool
    {
        // Only require at least one component to be selected
        $hasAnyComponent = collect($this->selectedComponents)
            ->whereNotNull()
            ->isNotEmpty();
        
        if (!$hasAnyComponent) {
            return false;
        }
        
        // Allow build even with compatibility warnings, but not hard incompatibilities
        return $this->compatibilityStatus !== 'incompatible';
    }

    public function addBuildToCart()
    {
        if (!auth()->check()) {
            return $this->redirect(route('login'), navigate: true);
        }

        if (!$this->isValidBuild()) {
            return;
        }

        // Add primary components
        foreach ($this->selectedComponents as $type => $component) {
            if ($component) {
                $quantity = $this->isQuantityType($type) ? ($this->componentQuantities[$type] ?? 1) : 1;
                
                for ($i = 0; $i < $quantity; $i++) {
                    $this->addToCart(
                        $component['id'],
                        $component['name'],
                        $component['price'],
                        $component['image'],
                        'Custom PC ' . ucfirst($type)
                    );
                }
            }
        }

        // Add extra components
        foreach ($this->extraComponents as $type => $extras) {
            foreach ($extras as $index => $extra) {
                $quantity = $this->extraQuantities[$type][$index] ?? 1;
                
                for ($i = 0; $i < $quantity; $i++) {
                    $this->addToCart(
                        $extra['id'],
                        $extra['name'],
                        $extra['price'],
                        $extra['image'],
                        'Custom PC ' . ucfirst($type)
                    );
                }
            }
        }

        $this->dispatch('notify', message: 'Full PC Build added to cart!');
    }

    public function hasIssue(string $type): bool
    {
        foreach ($this->compatibilityWarnings as $warning) {
            if (($warning['type'] === 'error') && in_array($type, $warning['components'] ?? [])) {
                return true;
            }
        }
        return false;
    }

    protected function formatSpecsShort(array $specs): string
    {
        $parts = [];

        // CPU
        if (isset($specs['cores'], $specs['socket'])) {
            $parts[] = $specs['cores'] . ' Cores';
            $parts[] = $specs['socket'];
        }
        // GPU
        elseif (isset($specs['vram'])) {
            $parts[] = $specs['vram'];
        }
        // RAM
        elseif (isset($specs['capacity'], $specs['speed'], $specs['type'])) {
            $parts[] = $specs['capacity'];
            $parts[] = $specs['type'];
            $parts[] = $specs['speed'];
        }
        // Motherboard
        elseif (isset($specs['chipset'], $specs['form_factor'])) {
            $parts[] = $specs['chipset'];
            $parts[] = $specs['form_factor'];
        }
        // Storage
        elseif (isset($specs['capacity'], $specs['interface'])) {
            $parts[] = $specs['capacity'];
            $parts[] = $specs['interface'];
        }
        // PSU
        elseif (isset($specs['wattage'], $specs['efficiency'])) {
            $parts[] = $specs['wattage'] . 'W';
            $parts[] = $specs['efficiency'];
        }
        // Case
        elseif (isset($specs['motherboard_support'])) {
            $parts[] = $specs['motherboard_support'];
        }
        // Cooling
        elseif (isset($specs['type'])) {
            $parts[] = $specs['type'];
            if (isset($specs['radiator_size']) && $specs['radiator_size'] > 0) {
                 $parts[] = $specs['radiator_size'] . 'mm';
            }
        }
        // Monitor
        elseif (isset($specs['size'], $specs['resolution'], $specs['refresh_rate'])) {
            $parts[] = $specs['size'];
            $parts[] = $specs['resolution'];
            $parts[] = $specs['refresh_rate'];
        }
        // Keyboard
        elseif (isset($specs['switch_type'], $specs['layout'])) {
            $parts[] = $specs['switch_type'];
            $parts[] = $specs['layout'];
        }
        // Mouse
        elseif (isset($specs['dpi'], $specs['weight'])) {
            $parts[] = $specs['dpi'] . ' DPI';
            $parts[] = $specs['weight'];
        }
        // Headset
        elseif (isset($specs['driver'], $specs['surround'])) {
            $parts[] = $specs['driver'];
            $parts[] = $specs['surround'];
        }
        // Mousepad
        elseif (isset($specs['size'], $specs['surface'])) {
            $parts[] = $specs['size'];
            $parts[] = $specs['surface'];
        }
        // Microphone
        elseif (isset($specs['type'], $specs['pattern'])) {
            $parts[] = $specs['type'];
            $parts[] = $specs['pattern'];
        }
        // Webcam
        elseif (isset($specs['resolution'], $specs['fps'])) {
            $parts[] = $specs['resolution'];
            $parts[] = $specs['fps'];
        }
        // Speakers
        elseif (isset($specs['watts'], $specs['type'])) {
            $parts[] = $specs['watts'];
            $parts[] = $specs['type'];
        }
        
        if (empty($parts)) {
             // Fallback to picking any non-array values
             $flat = array_filter($specs, fn($v) => !is_array($v) && is_scalar($v));
             $parts = array_slice($flat, 0, 3);
        }

        return implode(', ', $parts);
    }

    public function render()
    {
        $availableComponents = [];
        
        if ($this->activeSelection) {
            $category = $this->categoryMap[$this->activeSelection] ?? ucfirst($this->activeSelection);
            
            $query = Product::where('category', $category)
                ->where('is_active', true);
                
            if (!empty($this->search)) {
                $query->where(function($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            }

            $availableComponents = $query->paginate(12)->through(function($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'image' => (string) $product->image_url,
                    'specs' => $this->formatSpecsShort($product->specs),
                    'raw_specs' => $product->specs ?? []
                ];
            });
        }

        return view('livewire.pc-builder', [
            'availableComponents' => $availableComponents
        ])->layout('components.layouts.app');
    }
}
