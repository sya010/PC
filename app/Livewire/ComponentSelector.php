<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;
use App\Services\CompatibilityEngine;

class ComponentSelector extends Component
{
    use WithPagination;

    public string $type;
    public string $search = '';
    public array $filters = [];
    public ?array $currentBuild = [];

    // Whether this is selecting an extra slot (additional storage/cooling)
    public bool $isExtra = false;
    public ?int $editIndex = null;

    // Filter properties
    public $minPrice = 0;
    public $maxPrice = 10000000;
    public $selectedBrands = [];
    public $sort = 'newest';

    // Keys mapping
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

    public function mount(string $type)
    {
        if (!array_key_exists($type, $this->categoryMap)) {
            abort(404);
        }

        $this->type = $type;
        $this->currentBuild = session()->get('pc_build', []);

        // Check if this is an extra slot selection (via query parameter)
        $this->isExtra = request()->query('extra', false) ? true : false;
        
        $editIndex = request()->query('edit_index', null);
        if ($editIndex !== null) {
            $this->editIndex = (int) $editIndex;
        }
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->selectedBrands = [];
        $this->minPrice = 0;
        $this->maxPrice = 10000000;
        $this->sort = 'newest';
        $this->resetPage();
    }

    public function select(int $id)
    {
        $product = Product::find($id);
        
        if ($product) {
            $componentData = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'image' => (string) $product->image_url,
                'specs' => $product->specs,
                'category' => $product->category
            ];

            if ($this->isExtra) {
                // Add or edit an extra slot
                $extras = session()->get('pc_build_extras', []);
                if (!isset($extras[$this->type])) {
                    $extras[$this->type] = [];
                }
                
                if ($this->editIndex !== null && isset($extras[$this->type][$this->editIndex])) {
                    // Replace existing extra slot
                    $extras[$this->type][$this->editIndex] = $componentData;
                    session()->put('pc_build_extras', $extras);
                } else {
                    // Add new extra slot
                    $extras[$this->type][] = $componentData;
                    session()->put('pc_build_extras', $extras);

                    // Initialize quantity for this extra slot
                    $extraQty = session()->get('pc_build_extra_quantities', []);
                    if (!isset($extraQty[$this->type])) {
                        $extraQty[$this->type] = [];
                    }
                    $extraQty[$this->type][] = 1;
                    session()->put('pc_build_extra_quantities', $extraQty);
                }
            } else {
                // Normal primary slot selection
                $build = session()->get('pc_build', []);
                $build[$this->type] = $componentData;
                session()->put('pc_build', $build);
            }
            
            return redirect()->route('build-pc');
        }
    }

    public function render()
    {
        $category = $this->categoryMap[$this->type];
        
        $query = Product::where('category', $category)
            ->where('is_active', true);
            
        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        // Apply filters here
        if ($this->minPrice > 0 || $this->maxPrice < 10000000) {
           $query->whereBetween('price', [$this->minPrice, $this->maxPrice]);
        }
        
        if (!empty($this->selectedBrands)) {
            $query->where(function($q) {
                foreach ($this->selectedBrands as $brand) {
                    $q->orWhere('name', 'like', '%' . $brand . '%')
                      ->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(specs, '$.facts.brand')) = ?", [$brand]);
                }
            });
        }

        if ($this->sort === 'newest') {
            $query->latest();
        } elseif ($this->sort === 'price_low') {
            $query->orderBy('price', 'asc');
        } elseif ($this->sort === 'price_high') {
            $query->orderBy('price', 'desc');
        } elseif ($this->sort === 'name_asc') {
            $query->orderBy('name', 'asc');
        } elseif ($this->sort === 'name_desc') {
            $query->orderBy('name', 'desc');
        }

        $products = $query->paginate(24);

        $title = $this->getComponentTitle($this->type);
        if ($this->isExtra) {
            $title = $this->type === 'storage'
                ? __('messages.component_selector.add_another_storage')
                : __('messages.component_selector.add_another_cooling');
        }

        return view('livewire.component-selector', [
            'products' => $products,
            'title' => $title,
            'componentLabel' => __('messages.categories.' . $this->type),
        ])->layout('components.layouts.app'); // Ensure it uses the main layout
    }

    protected function getComponentTitle($type)
    {
        $titles = [
            'cpu' => 'messages.pc_builder.select_processor',
            'motherboard' => 'messages.pc_builder.select_motherboard',
            'gpu' => 'messages.pc_builder.select_graphics_card',
            'ram' => 'messages.pc_builder.select_memory',
            'storage' => 'messages.pc_builder.select_storage',
            'psu' => 'messages.pc_builder.select_power_supply',
            'case' => 'messages.pc_builder.select_case',
            'cooling' => 'messages.pc_builder.select_cooling',
            'monitor' => 'messages.pc_builder.select_monitor',
            'keyboard' => 'messages.pc_builder.select_keyboard',
            'mouse' => 'messages.pc_builder.select_mouse',
            'headset' => 'messages.pc_builder.select_headset',
            'mousepad' => 'messages.pc_builder.select_mousepad',
            'microphone' => 'messages.pc_builder.select_microphone',
            'webcam' => 'messages.pc_builder.select_webcam',
            'speakers' => 'messages.pc_builder.select_speakers',
        ];

        return isset($titles[$type])
            ? __($titles[$type])
            : __('messages.component_selector.select_component');
    }
}
