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

    public function mount(): void
    {
        // Load build from session
        $this->selectedComponents = array_merge(
            $this->selectedComponents, 
            session()->get('pc_build', [])
        );

        $this->checkCompatibility();
        $this->calculateTotal();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }
    
    // openSelector, closeSelector, and selectComponent logic removed/moved to ComponentSelector page logic
    
    public function removeComponent(string $type)
    {
        $this->selectedComponents[$type] = null;
        
        // Update session
        $build = session()->get('pc_build', []);
        unset($build[$type]);
        session()->put('pc_build', $build);

        $this->checkCompatibility();
        $this->calculateTotal();
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
        $this->totalPrice = collect($this->selectedComponents)
            ->whereNotNull()
            ->sum('price');
    }

    public function getSelectedCount(): int
    {
        return collect($this->selectedComponents)
            ->whereNotNull()
            ->count();
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

        foreach ($this->selectedComponents as $type => $component) {
            if ($component) {
                $this->addToCart(
                    $component['id'],
                    $component['name'],
                    $component['price'],
                    $component['image'],
                    'Custom PC ' . ucfirst($type)
                );
            }
        }

        $this->dispatch('notify', message: 'Full PC Build added to cart!');
        // Optional: clear selection or redirect
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
                    'image' => (string) $product->image, // Ensure string for easy handling
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
