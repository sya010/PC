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

    /** Maximum allowed price for filters */
    private const MAX_PRICE = 10000000;

    public $search = '';
    public $category = '';
    public $sort = 'featured';
    public $minPrice = 0;
    /** @var int */
    public $maxPrice = self::MAX_PRICE;
    public $selectedBrands = [];

    protected $queryString = [
        'search' => ['except' => ''],
        'category' => ['except' => ''],
        'sort' => ['except' => 'featured'],
        'minPrice' => ['except' => 0],
        'maxPrice' => ['except' => self::MAX_PRICE],
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
                // inRandomOrder breaks pagination, so use a stable sort or a 'featured' column. 
                // We'll use latest() as a stable fallback.
                $query->latest();
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
 
    // --- Property Hooks ---
    public function updatedSearch() { $this->resetPage(); }
    public function updatedCategory() { $this->resetPage(); }
    public function updatedSort() { $this->resetPage(); }
    public function updatedMinPrice($value)
    {
        $this->minPrice = max(0, (int) $value);
        if ($this->maxPrice < $this->minPrice) {
            $this->maxPrice = $this->minPrice;
        }
        $this->resetPage();
    }

    public function updatedMaxPrice($value)
    {
        $value = (int) $value;
        $value = max($this->minPrice, $value);
        $value = min(self::MAX_PRICE, $value);
        $this->maxPrice = $value;
        $this->resetPage();
    }

    public function updatedSelectedBrands($value)
    {
        $this->resetPage();
    }

    /**
     * Add product to cart using image_url.
     */
    public function addToCart(int $id): void
    {
        if (!auth()->check()) {
            $this->redirect(route('login'), navigate: true);
            return;
        }

        $product = \App\Models\Product::where('is_active', true)->find($id);

        if (!$product) {
            $this->dispatch('notify', type: 'error', message: __('messages.compare.product_not_found'));
            return;
        }

        if ($product->stock <= 0) {
            $this->dispatch('notify', type: 'error', message: __('messages.shop.out_of_stock') ?? 'This product is out of stock!');
            return;
        }

        $this->traitAddToCart($product->id, $product->name, $product->price, $product->image_url, $product->category);
    }

    protected function localizedCategories(): array
    {
        return collect(array_keys($this->categories))
            ->mapWithKeys(fn ($key) => [$key => __('messages.categories.' . $key)])
            ->all();
    }

    public function render()
    {
        $dbCounts = \App\Models\Product::where('is_active', true)
            ->groupBy('category')
            ->selectRaw('category, count(*) as count')
            ->pluck('count', 'category');

        $categoryCounts = [];
        foreach ($this->categories as $key => $label) {
            $dbCategory = $this->categoryMap[strtolower($key)] ?? $key;
            $categoryCounts[$key] = $dbCounts[$dbCategory] ?? 0;
        }

        return view('livewire.shop', [
            'products' => $this->products,
            'categoryCounts' => $categoryCounts,
            'categories' => $this->localizedCategories(),
        ])->layout('components.layouts.app');
    }
}
