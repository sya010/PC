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
    public $category = '';
    public $sort = 'newest';
    public $minPrice = 0;
    public $maxPrice = 5000000;
    public $selectedBrands = [];
    public $showPicker = false; // Toggle for "Add Product" view

    // --- Comparison Properties ---
    public $selectedProducts = [];
    public $compatibilityWarnings = [];
    public $allSpecKeys = [];
    public $highlightDifferences = false;
    public $winnerSpecs = [];

    // --- Constants ---
    protected $queryString = [
        'search' => ['except' => ''],
        'category' => ['except' => ''],
        'sort' => ['except' => 'newest'],
        'minPrice' => ['except' => 0],
        'maxPrice' => ['except' => 5000000],
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

    public function mount()
    {
        $this->refreshComparison();
    }

    // --- Property Hooks ---
    public function updatedSearch() { $this->resetPage(); }
    public function updatedCategory() { $this->resetPage(); }
    public function updatedMinPrice() { $this->resetPage(); }
    public function updatedMaxPrice() { $this->resetPage(); }
    public function updatedSelectedBrands() { $this->resetPage(); }

    // --- Core Comparison Logic ---
    public function refreshComparison()
    {
        $compareList = session()->get('compare_products', []);
        
        if (!empty($compareList)) {
            // Enforce limit of 3
            if (count($compareList) > 3) {
                $compareList = array_slice($compareList, 0, 3);
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
        } else {
            $this->selectedProducts = [];
        }

        $this->compileSpecKeys();
        $this->checkCompatibility();
        $this->calculateWinners();
    }

    // --- Product Picker (Shop) Logic ---
    public function getAvailableProductsProperty()
    {
        return \App\Models\Product::query()
            ->where('is_active', true)
            ->whereNotIn('id', array_column($this->selectedProducts, 'id')) // Exclude already selected
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
            ->paginate(9); // Smaller page size for picker
    }

    public function addToCart($id)
    {
        $product = \App\Models\Product::find($id);

        if (!$product) {
            $this->dispatch('toast-message', message: 'Product not found.');
            return;
        }

        $this->traitAddToCart(
            $product->id,
            $product->name,
            $product->price,
            $product->image,
            $product->category
        );
    }

    public function addProduct($productId)
    {
        $compareList = session()->get('compare_products', []);
        
        if (!in_array($productId, $compareList)) {
            if (count($compareList) >= 3) {
                $this->dispatch('toast-message', message: 'Maximum 3 products allowed.');
                return;
            }
            $compareList[] = $productId;
            session()->put('compare_products', $compareList);
            $this->refreshComparison();
            $this->showPicker = false; // Close picker on success
            $this->dispatch('toast-message', message: 'Product added.');
        }
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
        $this->refreshComparison();
        $this->dispatch('toast-message', message: 'Comparison cleared.');
    }

    public function togglePicker()
    {
        if (count($this->selectedProducts) >= 3) {
             $this->dispatch('toast-message', message: 'Comparison full (Max 3). Remove a product to add another.');
             return;
        }
        $this->showPicker = !$this->showPicker;
    }

    // --- Helpers ---
    private function compileSpecKeys()
    {
        $keys = [];
        $excludedKeys = ['facts', 'needs', 'provides', 'limits', 'meta'];

        foreach ($this->selectedProducts as $product) {
            if (isset($product['specs']) && is_array($product['specs'])) {
                $productKeys = array_keys($product['specs']);
                $filteredKeys = array_diff($productKeys, $excludedKeys);
                $keys = array_merge($keys, $filteredKeys);
            }
        }
        $this->allSpecKeys = array_unique($keys);
        sort($this->allSpecKeys);
    }

    private function calculateWinners()
    {
        $this->winnerSpecs = []; // Structure: [key => [id => 'green'|'blue'|'red']]
        
        // Helper to determine tier
        $assignTiers = function($idsValues, $isHigherBetter) {
            if (count($idsValues) < 2) return [];
            
            asort($idsValues); // Sort by value
            if ($isHigherBetter) {
                $idsValues = array_reverse($idsValues, true); // Highest first
            }
            
            $rankedIds = array_keys($idsValues);
            $count = count($rankedIds);
            
            $tiers = [];
            
            // Assign tiers based on rank index
            foreach ($rankedIds as $index => $id) {
                if ($count === 2) {
                    // 2 items: 0=Green, 1=Red
                    $tiers[$id] = ($index === 0) ? 'green' : 'red';
                } elseif ($count === 3) {
                    // 3 items: 0=Green, 1=Blue, 2=Red
                    if ($index === 0) $tiers[$id] = 'green';
                    elseif ($index === 1) $tiers[$id] = 'blue';
                    else $tiers[$id] = 'red';
                } else {
                    $tiers[$id] = 'black'; // Fallback
                }
            }
            // Check for ties in values (reset tied items to same color if needed, but simple rank is ok for now)
            return $tiers;
        };

        foreach ($this->allSpecKeys as $key) {
            $values = [];
            foreach ($this->selectedProducts as $p) {
                $raw = $p['specs'][$key] ?? null;
                if ($raw && !is_array($raw)) {
                     if (preg_match('/(\d+(\.\d+)?)/', $raw, $matches)) {
                         $values[$p['id']] = (float)$matches[1];
                     }
                }
            }
            
            if (count($values) > 1) {
                // Heuristic: Price & Latency = Lower is better. Others = Higher is better.
                $isLowerBetter = (stripos($key, 'latency') !== false || stripos($key, 'price') !== false);
                $this->winnerSpecs[$key] = $assignTiers($values, !$isLowerBetter);
            }
        }

        // Price Comparison
        $prices = array_column($this->selectedProducts, 'price', 'id');
        if (count($prices) > 1) {
            $this->winnerSpecs['price_rank'] = $assignTiers($prices, false); // Lower price is better
        }
    }

    private function getComponentByCategory($category)
    {
        foreach ($this->selectedProducts as $product) {
            if (strtolower($product['category']) === strtolower($category)) {
                return $product;
            }
        }
        return null;
    }

    private function checkCompatibility()
    {
        $this->compatibilityWarnings = [];
        
        $cpu = $this->getComponentByCategory('cpu');
        $mb = $this->getComponentByCategory('motherboard');
        $case = $this->getComponentByCategory('case');
        $gpu = $this->getComponentByCategory('gpu');
        $ram = $this->getComponentByCategory('ram');
        $psu = $this->getComponentByCategory('psu');
        $cooling = $this->getComponentByCategory('cooling');

        // CPU & Motherboard Socket
        if ($cpu && $mb) {
            $cpuSocket = $cpu['specs']['socket'] ?? null;
            $mbSocket = $mb['specs']['socket'] ?? null;

            if ($cpuSocket && $mbSocket && $cpuSocket !== $mbSocket) {
                $this->compatibilityWarnings[] = [
                    'type' => 'error',
                    'message' => "Incompatible Socket: CPU uses {$cpuSocket} but Motherboard uses {$mbSocket}."
                ];
            }
        }

        // RAM & Motherboard Type
        if ($ram && $mb) {
            $ramType = $ram['specs']['type'] ?? null;
            $mbRam = $mb['specs']['memory_type'] ?? null;

            if ($ramType && $mbRam && $ramType !== $mbRam) {
                $this->compatibilityWarnings[] = [
                    'type' => 'error',
                    'message' => "Incompatible RAM: Memory is {$ramType} but Motherboard supports {$mbRam}."
                ];
            }
        }

        // PSU Wattage Check
        if ($psu && ($cpu || $gpu)) {
            $psuWattage = isset($psu['specs']['wattage']) ? (float)filter_var($psu['specs']['wattage'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) : 0;
            $load = 100;
            if ($cpu) $load += isset($cpu['specs']['tdp']) ? (float)filter_var($cpu['specs']['tdp'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) : 65;
            if ($gpu) $load += isset($gpu['specs']['tdp']) ? (float)filter_var($gpu['specs']['tdp'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) : 0;

            if ($psuWattage > 0 && $psuWattage < $load) {
                $this->compatibilityWarnings[] = [
                    'type' => 'error',
                    'message' => "PSU Too Weak: Estimated load is {$load}W but PSU only provides {$psuWattage}W."
                ];
            }
        }
    }

    public function render()
    {
        return view('livewire.compare');
    }
}
