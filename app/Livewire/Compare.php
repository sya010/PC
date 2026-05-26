<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Traits\ManageCart;

class Compare extends Component
{
    use WithPagination;
    use ManageCart {
        addToCart as traitAddToCart;
    }

    // --- Search & Filter Properties (from Shop) ---
    public $search = '';
    public $sort = 'newest';
    public $minPrice = 0;
    public $maxPrice = 5000000;
    public $selectedBrands = [];

    // --- Comparison Properties ---
    public $selectedCategory = ''; // The category the user is comparing in
    public $selectedProducts = [];
    public $compatibilityWarnings = [];
    public $allSpecKeys = [];
    public $highlightDifferences = false;
    public $winnerSpecs = [];
    public $showPicker = false;
    public $productsAreIdentical = false;
    public $overallVerdict = []; // Overall winner data for PC parts
    public $isPcPart = false; // Whether current category is a PC component

    // --- Constants ---
    protected $queryString = [
        'search' => ['except' => ''],
        'sort' => ['except' => 'newest'],
        'minPrice' => ['except' => 0],
        'maxPrice' => ['except' => 5000000],
        'selectedBrands' => ['except' => []],
    ];

    public $pcPartCategories = ['cpu', 'gpu', 'motherboard', 'ram', 'storage', 'psu', 'case', 'cooling'];

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

    // Category icons for the selection grid
    public $categoryIcons = [
        'cpu' => 'M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z',
        'gpu' => 'M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z',
        'motherboard' => 'M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z',
        'ram' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10',
        'storage' => 'M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4',
        'psu' => 'M13 10V3L4 14h7v7l9-11h-7z',
        'case' => 'M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4',
        'cooling' => 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
        'monitor' => 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
        'keyboard' => 'M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2',
        'mouse' => 'M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122',
        'mousepad' => 'M4 5a1 1 0 011-1h14a1 1 0 011 1v14a1 1 0 01-1 1H5a1 1 0 01-1-1V5z',
        'headset' => 'M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z',
        'microphone' => 'M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z',
        'webcam' => 'M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z',
        'speakers' => 'M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z',
    ];

    public function mount()
    {
        // Clear previous comparison on page load
        session()->forget('compare_products');
        $this->selectedProducts = [];
        $this->selectedCategory = '';
        $this->showPicker = false;
        $this->productsAreIdentical = false;
    }

    // --- Property Hooks ---
    public function updatedSearch() { $this->resetPage(); }
    public function updatedMinPrice() { $this->resetPage(); }
    public function updatedMaxPrice() { $this->resetPage(); }
    public function updatedSelectedBrands() { $this->resetPage(); }

    // --- Category Selection ---
    public function selectCategory($category)
    {
        $this->selectedCategory = $category;
        $this->isPcPart = in_array($category, $this->pcPartCategories);
        $this->showPicker = true;
        $this->search = '';
        $this->selectedBrands = [];
        $this->minPrice = 0;
        $this->maxPrice = 5000000;
        $this->sort = 'newest';
        $this->resetPage();
    }

    public function changeCategory()
    {
        $this->clearComparison();
        $this->selectedCategory = '';
        $this->isPcPart = false;
        $this->showPicker = false;
    }

    // --- Core Comparison Logic ---
    public function refreshComparison()
    {
        $compareList = session()->get('compare_products', []);
        
        if (!empty($compareList)) {
            // Enforce limit of 2
            if (count($compareList) > 2) {
                $compareList = array_slice($compareList, 0, 2);
                session()->put('compare_products', $compareList);
            }

            $products = \App\Models\Product::whereIn('id', $compareList)->get();
            $data = $products->toArray();
            
            // Flatten nested specs
            foreach ($data as &$p) {
                if (isset($p['specs']) && is_array($p['specs'])) {
                    $layers = ['facts', 'needs', 'provides', 'limits', 'meta'];
                    foreach ($layers as $layer) {
                        if (isset($p['specs'][$layer]) && is_array($p['specs'][$layer])) {
                            $p['specs'] = array_merge($p['specs'], $p['specs'][$layer]);
                        }
                    }
                }
            }
            $this->selectedProducts = $data;

            // Check if products are identical
            if (count($data) === 2 && $data[0]['id'] === $data[1]['id']) {
                $this->productsAreIdentical = true;
            } else {
                $this->productsAreIdentical = false;
            }
        } else {
            $this->selectedProducts = [];
            $this->productsAreIdentical = false;
        }

        $this->compileSpecKeys();
        $this->calculateWinners();
        $this->calculateOverallVerdict();
    }

    // --- Product Picker (Shop) Logic ---
    public function getAvailableProductsProperty()
    {
        // Always filter by selected category
        $dbCategory = $this->categoryMap[strtolower($this->selectedCategory)] ?? $this->selectedCategory;
        
        return \App\Models\Product::query()
            ->where('is_active', true)
            ->where('category', $dbCategory)
            ->whereNotIn('id', array_column($this->selectedProducts, 'id')) // Exclude already selected
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->minPrice > 0 || $this->maxPrice < 5000000, function ($query) {
                $query->whereBetween('price', [$this->minPrice, $this->maxPrice]);
            })
            ->when(!empty($this->selectedBrands), function ($query) {
                $query->where(function($q) {
                    foreach ($this->selectedBrands as $brand) {
                        $q->orWhere('name', 'like', '%' . $brand . '%');
                    }
                });
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
            ->paginate(12);
    }

    public function addToCart($id)
    {
        $product = \App\Models\Product::find($id);

        if (!$product) {
            $this->dispatch('toast-message', message: __('messages.compare.product_not_found'));
            return;
        }

        if ($product->stock <= 0) {
            $this->dispatch('notify', type: 'error', message: __('messages.shop.out_of_stock') ?? 'This product is out of stock!');
            return;
        }

        $this->traitAddToCart(
            $product->id,
            $product->name,
            $product->price,
            $product->image_url,
            $product->category
        );
    }

    public function addProduct($productId)
    {
        $compareList = session()->get('compare_products', []);
        
        // Block duplicates
        if (in_array($productId, $compareList)) {
            $this->dispatch('toast-message', message: __('messages.compare.already_selected'));
            return;
        }

        // Block if already at max (2)
        if (count($compareList) >= 2) {
            $this->dispatch('toast-message', message: __('messages.compare.max_products'));
            return;
        }

        // Verify product belongs to the selected category
        $product = \App\Models\Product::find($productId);
        if (!$product) {
            $this->dispatch('toast-message', message: __('messages.compare.product_not_found'));
            return;
        }

        $expectedCategory = $this->categoryMap[strtolower($this->selectedCategory)] ?? $this->selectedCategory;
        if (strtolower($product->category) !== strtolower($expectedCategory)) {
            $this->dispatch('toast-message', message: __('messages.compare.category_mismatch'));
            return;
        }

        $compareList[] = $productId;
        session()->put('compare_products', $compareList);
        $this->refreshComparison();

        // Close picker if 2 products selected
        if (count($compareList) >= 2) {
            $this->showPicker = false;
        }

        $this->dispatch('toast-message', message: __('messages.compare.added'));
    }

    public function removeProduct($productId)
    {
        $compareList = session()->get('compare_products', []);
        $key = array_search($productId, $compareList);
        
        if ($key !== false) {
            unset($compareList[$key]);
            session()->put('compare_products', array_values($compareList));
            $this->refreshComparison();
        }
    }

    public function clearComparison()
    {
        session()->forget('compare_products');
        $this->selectedProducts = [];
        $this->allSpecKeys = [];
        $this->winnerSpecs = [];
        $this->productsAreIdentical = false;
        $this->dispatch('toast-message', message: __('messages.compare.cleared'));
    }

    public function togglePicker()
    {
        if (count($this->selectedProducts) >= 2) {
             $this->dispatch('toast-message', message: __('messages.compare.full'));
             return;
        }
        $this->showPicker = !$this->showPicker;
    }

    // --- Helpers ---
    protected function localizedCategories(): array
    {
        return collect(array_keys($this->categories))
            ->mapWithKeys(fn ($key) => [$key => __('messages.categories.' . $key)])
            ->all();
    }

    // Important specs whitelist per category - only these will be shown in comparison
    protected $importantSpecs = [
        'cpu' => ['cores', 'threads', 'base_clock', 'boost_clock', 'tdp', 'socket', 'architecture', 'generation', 'performance_tier', 'supported_memory_type', 'longevity_score'],
        'gpu' => ['vram', 'tdp', 'architecture', 'generation', 'performance_tier', 'longevity_score', 'pcie_version', 'memory_gb'],
        'motherboard' => ['socket', 'chipset', 'form_factor', 'memory_type', 'max_memory_speed', 'memory_slots', 'pcie_version', 'vrm_power_delivery', 'max_ram_capacity', 'performance_tier'],
        'ram' => ['type', 'capacity', 'speed', 'capacity_gb', 'speed_mhz', 'modules', 'voltage', 'performance_tier'],
        'storage' => ['interface', 'capacity', 'capacity_gb', 'read_speed', 'write_speed', 'performance_tier'],
        'psu' => ['wattage', 'efficiency', 'modular', 'atx_version', 'performance_tier'],
        'case' => ['motherboard_support', 'max_gpu_length', 'radiator_support', 'airflow_rating', 'form_factor_support'],
        'cooling' => ['type', 'socket_support', 'tdp_rating', 'radiator_size', 'noise_level', 'performance_tier'],
        'monitor' => ['size', 'resolution', 'refresh_rate', 'panel', 'response_time'],
        'keyboard' => ['switch_type', 'layout', 'wireless', 'backlight'],
        'mouse' => ['dpi', 'weight', 'wireless', 'sensor'],
        'mousepad' => ['size', 'surface'],
        'headset' => ['type', 'surround', 'driver', 'wireless'],
        'microphone' => ['type', 'pattern', 'sample_rate'],
        'webcam' => ['resolution', 'fps', 'autofocus'],
        'speakers' => ['type', 'watts', 'wireless'],
    ];

    private function compileSpecKeys()
    {
        $keys = [];
        // Always exclude internal nested layers and model
        $excludedKeys = ['facts', 'needs', 'provides', 'limits', 'meta', 'model', 'brand',
            // Physical/internal keys not useful for comparison
            'length', 'length_mm', 'max_length', 'slot_width', 'height_mm', 'max_height_mm',
            'psu_min_wattage', 'case_max_length', 'case_max_height', 'cpu_performance_tier_min',
            'cpu_tdp', 'system_total_power', 'min_psu_wattage', 'cpu_socket', 'ram_type',
            'm2_slot', 'motherboard_max_speed', 'max_speed', 'max_cooler_height_mm',
            'max_gpu_length_mm', 'radiator_support_mm', 'headroom_recommended_pct',
            'supported_chipsets', 'upgrade_friendly', 'upgrade_path', 'recommended_cooler_tdp',
            'max_memory_speed', 'max_cooler_height', 'pcie_lanes',
        ];

        // Get whitelist for current category
        $whitelist = $this->importantSpecs[strtolower($this->selectedCategory)] ?? [];

        foreach ($this->selectedProducts as $product) {
            if (isset($product['specs']) && is_array($product['specs'])) {
                $productKeys = array_keys($product['specs']);
                $filteredKeys = array_diff($productKeys, $excludedKeys);
                $keys = array_merge($keys, $filteredKeys);
            }
        }

        $keys = array_unique($keys);

        // If we have a whitelist, only keep whitelisted keys
        if (!empty($whitelist)) {
            $keys = array_intersect($keys, $whitelist);
        }

        $this->allSpecKeys = array_values($keys);
        sort($this->allSpecKeys);
    }

    public function normalizeValue($raw, string $key)
    {
        if ($raw === null || is_array($raw)) {
            return null;
        }

        $rawStr = trim((string)$raw);
        $upperStr = strtoupper($rawStr);

        // 1. Handle Categorical Hierarchies
        // A. PSU Modularity
        if ($key === 'modular') {
            if (str_contains($upperStr, 'FULL')) return 3;
            if (str_contains($upperStr, 'SEMI')) return 2;
            if (str_contains($upperStr, 'NONE') || str_contains($upperStr, 'NO')) return 1;
            return 1;
        }

        // B. PSU Efficiency
        if ($key === 'efficiency') {
            if (str_contains($upperStr, 'TITANIUM')) return 6;
            if (str_contains($upperStr, 'PLATINUM')) return 5;
            if (str_contains($upperStr, 'GOLD')) return 4;
            if (str_contains($upperStr, 'SILVER')) return 3;
            if (str_contains($upperStr, 'BRONZE')) return 2;
            if (str_contains($upperStr, '80+')) return 1;
            return 0;
        }

        // C. Storage Interface
        if ($key === 'interface') {
            if (str_contains($upperStr, 'GEN5') || str_contains($upperStr, 'GEN 5')) return 4;
            if (str_contains($upperStr, 'GEN4') || str_contains($upperStr, 'GEN 4')) return 3;
            if (str_contains($upperStr, 'NVME')) return 2;
            if (str_contains($upperStr, 'SATA')) return 1;
            return 0;
        }

        // D. RAM / Motherboard Generation
        if ($key === 'type' || $key === 'memory_type') {
            if (str_contains($upperStr, 'DDR5')) return 3;
            if (str_contains($upperStr, 'DDR4')) return 2;
            if (str_contains($upperStr, 'DDR3')) return 1;
            return 0;
        }

        // 2. Handle Numeric Extraction and Unit Conversions
        if (preg_match('/(\d+(\.\d+)?)/', $rawStr, $matches)) {
            $num = (float)$matches[1];

            // Capacity Normalization (TB to GB)
            if (stripos($key, 'capacity') !== false || stripos($key, 'storage') !== false || stripos($key, 'vram') !== false) {
                if (str_contains($upperStr, 'TB')) {
                    return $num * 1000;
                }
            }

            // Speed Normalization (GHz to MHz)
            if (stripos($key, 'clock') !== false || stripos($key, 'speed') !== false) {
                if (str_contains($upperStr, 'GHZ')) {
                    return $num * 1000;
                }
            }

            return $num;
        }

        return null;
    }

    private function calculateWinners()
    {
        $this->winnerSpecs = [];
        
        // Helper to determine tier – handles ties
        $assignTiers = function($idsValues, $isHigherBetter) {
            if (count($idsValues) < 2) return [];
            
            $values = array_values($idsValues);
            $ids = array_keys($idsValues);
            
            // Check for tie (identical values)
            if (count(array_unique($values)) === 1) {
                $tiers = [];
                foreach ($ids as $id) {
                    $tiers[$id] = 'tie'; // Same value = tie
                }
                return $tiers;
            }
            
            // Sort values
            asort($idsValues);
            if ($isHigherBetter) {
                $idsValues = array_reverse($idsValues, true);
            }
            
            $rankedIds = array_keys($idsValues);
            $tiers = [];
            
            // 2 items: winner = green, loser = red
            $tiers[$rankedIds[0]] = 'green';
            $tiers[$rankedIds[1]] = 'red';
            
            return $tiers;
        };

        foreach ($this->allSpecKeys as $key) {
            $values = [];
            foreach ($this->selectedProducts as $p) {
                $raw = $p['specs'][$key] ?? null;
                $normalized = $this->normalizeValue($raw, $key);
                if ($normalized !== null) {
                    $values[$p['id']] = $normalized;
                }
            }
            
            if (count($values) > 1) {
                $lowerIsBetterKeys = ['price', 'tdp', 'noise_level', 'weight', 'response_time', 'voltage', 'latency', 'cl'];
                $isLowerBetter = false;
                foreach ($lowerIsBetterKeys as $lowKey) {
                    if (stripos($key, $lowKey) !== false) {
                        $isLowerBetter = true;
                        break;
                    }
                }
                $this->winnerSpecs[$key] = $assignTiers($values, !$isLowerBetter);
            }
        }

        // Price Comparison
        $prices = array_column($this->selectedProducts, 'price', 'id');
        if (count($prices) > 1) {
            $this->winnerSpecs['price_rank'] = $assignTiers($prices, false); // Lower price is better
        }
    }

    private function calculateOverallVerdict()
    {
        $this->overallVerdict = [];
        if (count($this->selectedProducts) !== 2 || $this->productsAreIdentical) return;

        $id1 = $this->selectedProducts[0]['id'];
        $id2 = $this->selectedProducts[1]['id'];
        $wins1 = 0;
        $wins2 = 0;
        $ties = 0;
        $total = 0;

        foreach ($this->winnerSpecs as $key => $tiers) {
            if ($key === 'price_rank') continue;
            $total++;
            $t1 = $tiers[$id1] ?? 'black';
            $t2 = $tiers[$id2] ?? 'black';
            if ($t1 === 'green') $wins1++;
            elseif ($t2 === 'green') $wins2++;
            else $ties++;
        }

        // Include price
        if (isset($this->winnerSpecs['price_rank'])) {
            $total++;
            $pt1 = $this->winnerSpecs['price_rank'][$id1] ?? 'black';
            if ($pt1 === 'green') $wins1++;
            elseif ($pt1 === 'red') $wins2++;
            else $ties++;
        }

        if ($total === 0) return;

        $pct1 = round(($wins1 / $total) * 100);
        $pct2 = round(($wins2 / $total) * 100);

        if ($wins1 > $wins2) {
            $winnerId = $id1;
            $verdict = 'product1';
        } elseif ($wins2 > $wins1) {
            $winnerId = $id2;
            $verdict = 'product2';
        } else {
            $winnerId = null;
            $verdict = 'tie';
        }

        $this->overallVerdict = [
            'verdict' => $verdict,
            'winnerId' => $winnerId,
            'wins1' => $wins1,
            'wins2' => $wins2,
            'ties' => $ties,
            'total' => $total,
            'pct1' => $pct1,
            'pct2' => $pct2,
        ];
    }

    public function render()
    {
        return view('livewire.compare', [
            'categories' => $this->localizedCategories(),
        ]);
    }
}
