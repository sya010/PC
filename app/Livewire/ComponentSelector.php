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

    // Filter properties
    public $minPrice = 0;
    public $maxPrice = 5000000;
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
        // Load current build state from PC Builder component if possible, 
        // or we might need to pass it via session or manage it in a shared service.
        // For now, let's assume PcBuilder stores state in session or we can just access compatibility warnings.
        // A better approach for this multi-page flow is to have a "BuildSession" service or just use session().
        
        $this->currentBuild = session()->get('pc_build', []);
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
        $this->maxPrice = 5000000;
        $this->sort = 'newest';
        $this->resetPage();
    }

    public function select(int $id)
    {
        $product = Product::find($id);
        
        if ($product) {
            $build = session()->get('pc_build', []);
            $build[$this->type] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'image' => (string) $product->image,
                'specs' => $product->specs,
                'category' => $product->category
            ];
            session()->put('pc_build', $build); // Save to session
            
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
        if ($this->minPrice > 0 || $this->maxPrice < 50000000) {
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

        return view('livewire.component-selector', [
            'products' => $products,
            'title' => $this->getComponentTitle($this->type)
        ])->layout('components.layouts.app'); // Ensure it uses the main layout
    }

    protected function getComponentTitle($type)
    {
        $titles = [
            'cpu' => 'Select Processor (CPU)',
            'motherboard' => 'Select Motherboard',
            'gpu' => 'Select Graphics Card',
            'ram' => 'Select Memory (RAM)',
            'storage' => 'Select Storage',
            'psu' => 'Select Power Supply',
            'case' => 'Select Case',
            'cooling' => 'Select Cooling',
             'monitor' => 'Select Monitor',
            'keyboard' => 'Select Keyboard',
            'mouse' => 'Select Mouse',
            'headset' => 'Select Headset',
            'mousepad' => 'Select Mousepad',
            'microphone' => 'Select Microphone',
            'webcam' => 'Select Webcam',
            'speakers' => 'Select Speakers',
        ];

        return $titles[$type] ?? 'Select Component';
    }
}
