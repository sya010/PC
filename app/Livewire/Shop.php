<?php

namespace App\Livewire;


use Livewire\Component;
use Livewire\WithPagination;
use App\Traits\ManageCart;

class Shop extends Component
{
    use WithPagination;
    use ManageCart {
        addToCart as traitAddToCart;
    }

    public $search = '';
    public $category = '';
    public $sort = 'featured';
    public $minPrice = 0;
    public $maxPrice = 10000000;
    public $selectedBrands = [];

    protected $queryString = [
        'search' => ['except' => ''],
        'category' => ['except' => ''],
        'sort' => ['except' => 'featured'],
        'minPrice' => ['except' => 0],
        'maxPrice' => ['except' => 10000000],
        'selectedBrands' => ['except' => []],
    ];

    public $categories = [
        'cpu' => 'Processors',
        'gpu' => 'Graphics Cards',
        'motherboard' => 'Motherboards',
        'ram' => 'Memory',
        'storage' => 'Storage',
        'psu' => 'Power Supplies',
        'case' => 'Cases',
        'cooling' => 'Cooling',
        'monitor' => 'Monitors',
        'keyboard' => 'Keyboards',
        'mouse' => 'Mice',
        'mousepad' => 'Mousepads',
        'headset' => 'Headsets',
        'microphone' => 'Microphones',
        'webcam' => 'Webcams',
        'speakers' => 'Speakers',
    ];

    protected $categoryMap = [
        'cpu' => 'CPU',
        'gpu' => 'GPU',
        'motherboard' => 'Motherboard',
        'ram' => 'RAM',
        'storage' => 'Storage',
        'psu' => 'PSU',
        'case' => 'Case',
        'cooling' => 'Cooling',
        'monitor' => 'Monitor',
        'keyboard' => 'Keyboard',
        'mouse' => 'Mouse',
        'mousepad' => 'Mousepad',
        'headset' => 'Headset',
        'microphone' => 'Microphone',
        'webcam' => 'Webcam',
        'speakers' => 'Speakers',
    ];

    // Using the same data as PcBuilder for consistency + some extras
    // Using Computed Property for efficient querying
    public function getProductsProperty()
    {
        return \App\Models\Product::query()
            ->where('is_active', true)
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%')
                      ->orWhere('category', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->category, function ($query) {
                 // Map the URL slug (lowercase) to the Database Value (Proper Case/Acronym)
                 $dbCategory = $this->categoryMap[strtolower($this->category)] ?? $this->category;
                 
                 $query->where('category', $dbCategory);
            })
            ->when($this->minPrice > 0 || $this->maxPrice < 10000000, function ($query) {
                $query->whereBetween('price', [$this->minPrice, $this->maxPrice]);
            })
            ->when(!empty($this->selectedBrands), function ($query) {
                $query->where(function($q) {
                    foreach ($this->selectedBrands as $brand) {
                        $q->orWhere('name', 'like', '%' . $brand . '%');
                    }
                });
            })
            ->when($this->sort === 'featured', function ($query) {
                $query->inRandomOrder();
            })
            ->when($this->sort === 'price_low', function ($query) {
                $query->orderBy('price', 'asc');
            })
            ->when($this->sort === 'price_high', function ($query) {
                $query->orderBy('price', 'desc');
            })
            ->when($this->sort === 'newest', function ($query) {
                $query->latest();
            })
            ->when($this->sort === 'name_asc', function ($query) {
                $query->orderBy('name', 'asc');
            })
            ->when($this->sort === 'name_desc', function ($query) {
                $query->orderBy('name', 'desc');
            })
            ->paginate(24);
    }

    public function filterByCategory($category)
    {
        $this->category = $this->category === $category ? '' : $category;
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->category = '';
        $this->selectedBrands = [];
        $this->minPrice = 0;
        $this->maxPrice = 10000000;
        $this->sort = 'featured';
        $this->resetPage();
    }

    public function addToCart($id)
    {
        if (!auth()->check()) {
            return $this->redirect(route('login'), navigate: true);
        }

        $product = \App\Models\Product::find($id);

        if (!$product) {
            $this->dispatch('toast-message', message: __('messages.compare.product_not_found'));
            return;
        }

        // Call the trait's method with all required arguments
        $this->traitAddToCart(
            $product->id,
            $product->name,
            $product->price,
            $product->image_url,
            $product->category
        );
    }

    public function addToCompare($id)
    {
        $compareList = session()->get('compare_products', []);
        
        if (!in_array($id, $compareList)) {
            if (count($compareList) >= 4) {
                array_shift($compareList);
            }
            $compareList[] = $id;
            session()->put('compare_products', $compareList);
            $this->dispatch('toast-message', message: __('messages.compare.added'));
        } else {
            $this->dispatch('toast-message', message: __('messages.compare.already_selected'));
        }

        return $this->redirect(route('compare'), navigate: true);
    }

    protected function localizedCategories(): array
    {
        return collect(array_keys($this->categories))
            ->mapWithKeys(fn ($key) => [$key => __('messages.categories.' . $key)])
            ->all();
    }

    public function render()
    {
        $allProducts = \App\Models\Product::where('is_active', true)->get();
        $categoryCounts = [];
        foreach ($this->categories as $key => $label) {
            $dbCategory = $this->categoryMap[strtolower($key)] ?? $key;
            $categoryCounts[$key] = $allProducts->where('category', $dbCategory)->count();
        }

        return view('livewire.shop', [
            'products' => $this->products,
            'categoryCounts' => $categoryCounts,
            'categories' => $this->localizedCategories(),
        ])->layout('components.layouts.app');
    }
}
