<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Product;
use App\Models\ActivityLog;
use App\Services\ImageService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProductForm extends Component
{
    use WithFileUploads;

    public ?Product $product = null;
    
    public $name;
    public $price;
    public $description;
    public $category;
    public $stock;
    public $image; // Can be string (url) or TemporaryUploadedFile
    public $existingImage; // To hold the existing image URL when editing
    public $is_active = true;
    public $has_specs = true;
    public $specs = [];
    public $availableCategories = [];

    const CATEGORY_SPECS = [
        'CPU' => [
            'defaults' => ['socket', 'tdp', 'cores', 'threads', 'boost_clock'],
            'additional' => ['brand', 'base_clock', 'generation', 'architecture', 'min_psu_wattage', 'supported_memory_type', 'performance_tier', 'max_memory_speed', 'longevity_score', 'recommended_cooler_tdp']
        ],
        'GPU' => [
            'defaults' => ['vram', 'tdp', 'length', 'pcie_version', 'brand'],
            'additional' => ['model', 'slot_width', 'generation', 'architecture', 'memory_gb', 'length_mm', 'psu_min_wattage', 'case_max_length', 'cpu_performance_tier_min', 'performance_tier', 'longevity_score']
        ],
        'MOTHERBOARD' => [
            'defaults' => ['socket', 'chipset', 'form_factor', 'memory_type', 'max_memory_speed'],
            'additional' => ['brand', 'memory_slots', 'pcie_version', 'vrm_power_delivery', 'cpu_socket', 'ram_type', 'supports_socket', 'pcie_lanes', 'max_ram_capacity', 'max_gpu_length', 'generation', 'upgrade_path', 'performance_tier']
        ],
        'RAM' => [
            'defaults' => ['type', 'capacity', 'speed', 'modules', 'brand'],
            'additional' => ['model', 'voltage', 'performance_tier', 'capacity_gb', 'speed_mhz', 'memory_type', 'motherboard_max_speed']
        ],
        'STORAGE' => [
            'defaults' => ['capacity', 'interface', 'read_speed', 'write_speed', 'brand'],
            'additional' => ['model', 'form_factor', 'capacity_gb', 'performance_tier']
        ],
        'PSU' => [
            'defaults' => ['wattage', 'efficiency', 'modular', 'brand', 'model'],
            'additional' => ['certification', 'fan_size', 'atx_version', 'performance_tier']
        ],
        'CASE' => [
            'defaults' => ['form_factor', 'max_gpu_length', 'motherboard_support', 'brand', 'model'],
            'additional' => ['max_cooler_height', 'radiator_support', 'color', 'weight', 'airflow_rating', 'form_factor_support']
        ],
        'COOLING' => [
            'defaults' => ['type', 'tdp_rating', 'radiator', 'socket_support', 'brand'],
            'additional' => ['model', 'fan_rpm', 'noise_level', 'performance_tier']
        ],
        'MONITOR' => [
            'defaults' => ['size', 'resolution', 'refresh_rate', 'brand', 'model'],
            'additional' => ['panel_type', 'response_time', 'hdr']
        ],
        'KEYBOARD' => [
            'defaults' => ['switch_type', 'layout', 'brand', 'model'],
            'additional' => ['backlight', 'connectivity']
        ],
        'MOUSE' => [
            'defaults' => ['dpi', 'weight', 'brand', 'model'],
            'additional' => ['sensor', 'buttons', 'connectivity']
        ],
        'HEADSET' => [
            'defaults' => ['driver', 'surround', 'brand', 'model'],
            'additional' => ['frequency_response', 'connectivity', 'mic_type']
        ],
        'MOUSEPAD' => [
            'defaults' => ['size', 'surface', 'brand', 'model'],
            'additional' => ['thickness', 'rgb']
        ],
        'MICROPHONE' => [
            'defaults' => ['type', 'pattern', 'brand', 'model'],
            'additional' => ['frequency_response', 'connectivity']
        ],
        'WEBCAM' => [
            'defaults' => ['resolution', 'fps', 'brand', 'model'],
            'additional' => ['fov', 'mic_built_in']
        ],
        'SPEAKERS' => [
            'defaults' => ['watts', 'type', 'brand', 'model'],
            'additional' => ['connectivity', 'channels']
        ],
        'default' => [
            'defaults' => ['brand', 'model', 'type', 'wireless', 'color'],
            'additional' => ['warranty', 'weight', 'dimensions', 'material']
        ]
    ];

    // Validation Rules
    protected function rules() 
    {
        return [
            'name' => 'required|string|min:3|max:255',
            'price' => 'required|numeric|min:0|max:100000000', // Reasonable max price
            'stock' => 'required|integer|min:0|max:100000',
            'category' => 'required|string|max:100',
            'description' => 'nullable|string|max:5000',
            'image' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if (is_string($value)) return; // Allow string URLs
                    if (!is_object($value)) return; // Should be uploaded file
                    
                    // Validate file upload manually if generic 'image' rule fails on strings
                    $allowedMimes = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
                    if (!in_array($value->extension(), $allowedMimes)) {
                        $fail(__('messages.validation.admin_product.image_mimes'));
                    }
                    if ($value->getSize() > 5120 * 1024) { // 5MB
                         $fail(__('messages.validation.admin_product.image_max'));
                    }
                }, 
            ],
            'is_active' => 'boolean',
            'has_specs' => 'boolean',
            'specs' => 'nullable|array',
            'specs.*.key' => 'required_if:has_specs,true|string|max:100',
            'specs.*.value' => 'required_if:has_specs,true|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => __('messages.validation.admin_product.name_required'),
            'name.min' => __('messages.validation.admin_product.name_min'),
            'name.max' => __('messages.validation.admin_product.name_max'),
            'price.required' => __('messages.validation.admin_product.price_required'),
            'price.numeric' => __('messages.validation.admin_product.price_numeric'),
            'price.min' => __('messages.validation.admin_product.price_min'),
            'price.max' => __('messages.validation.admin_product.price_max'),
            'stock.required' => __('messages.validation.admin_product.stock_required'),
            'stock.integer' => __('messages.validation.admin_product.stock_integer'),
            'stock.min' => __('messages.validation.admin_product.stock_min'),
            'stock.max' => __('messages.validation.admin_product.stock_max'),
            'category.required' => __('messages.validation.admin_product.category_required'),
            'category.max' => __('messages.validation.admin_product.category_max'),
            'description.max' => __('messages.validation.admin_product.description_max'),
            'specs.*.key.required' => __('messages.validation.admin_product.specs_key_required'),
            'specs.*.key.max' => __('messages.validation.admin_product.specs_key_max'),
            'specs.*.value.required' => __('messages.validation.admin_product.specs_value_required'),
            'specs.*.value.max' => __('messages.validation.admin_product.specs_value_max'),
        ];
    }

    private function initializeSpecsForCategory($category)
    {
        $categoryUpper = strtoupper($category ?? '');
        $specMap = self::CATEGORY_SPECS['default'];
        foreach (self::CATEGORY_SPECS as $catKey => $map) {
            if (strtoupper($catKey) === $categoryUpper) {
                $specMap = $map;
                break;
            }
        }

        $newSpecs = [];
        foreach ($specMap['defaults'] as $defaultKey) {
            $newSpecs[] = ['key' => $defaultKey, 'value' => ''];
        }
        return $newSpecs;
    }

    public function updatedCategory($value)
    {
        // If specs is empty or only contains empty key/value rows, populate with defaults
        $isEmpty = true;
        foreach ($this->specs as $spec) {
            if (!empty(trim($spec['key'] ?? '')) || !empty(trim($spec['value'] ?? ''))) {
                $isEmpty = false;
                break;
            }
        }

        if ($isEmpty) {
            $this->specs = $this->initializeSpecsForCategory($value);
        }
    }

    public function mount($id = null)
    {
        $existingCategories = Product::select('category')->distinct()->pluck('category')->filter()->toArray();
        $defaultCategories = ['CPU', 'GPU', 'Motherboard', 'RAM', 'Storage', 'PSU', 'Case', 'Cooling', 'Monitor', 'Keyboard', 'Mouse', 'Headset', 'Microphone', 'Chair', 'Accessory'];
        $this->availableCategories = array_values(array_unique(array_merge($defaultCategories, $existingCategories)));

        if ($id) {
            $this->product = Product::findOrFail($id);
            $this->name = $this->product->name;
            $this->price = $this->product->price;
            $this->description = $this->product->description;
            $this->category = $this->product->category;
            $this->stock = $this->product->stock;
            $this->existingImage = $this->product->image_url; // Use accessor for display
            $this->is_active = (bool) $this->product->is_active;
            $this->has_specs = $this->product->has_specs !== null ? (bool) $this->product->has_specs : true;
            
            if ($this->product->specs) {
                $rawSpecs = $this->product->specs;
                $flatSpecs = [];
                $layers = ['facts', 'needs', 'provides', 'limits', 'meta'];

                // 1. Add top-level scalar values first (excluding layer arrays)
                foreach ($rawSpecs as $key => $value) {
                    if (!in_array($key, $layers) && is_scalar($value)) {
                        $flatSpecs[$key] = $value;
                    }
                }

                // 2. Add layered nested values recursively
                foreach ($layers as $layer) {
                    if (isset($rawSpecs[$layer]) && is_array($rawSpecs[$layer])) {
                        foreach ($rawSpecs[$layer] as $subKey => $subValue) {
                            if (is_scalar($subValue)) {
                                $flatSpecs[$subKey] = $subValue;
                            }
                        }
                    }
                }

                // 3. Populate $this->specs
                foreach ($flatSpecs as $key => $value) {
                    $this->specs[] = ['key' => $key, 'value' => $value];
                }
            }

            // Pad existing specs up to 5 if needed using default keys
            $categoryUpper = strtoupper($this->category ?? '');
            $specMap = self::CATEGORY_SPECS['default'];
            foreach (self::CATEGORY_SPECS as $catKey => $map) {
                if (strtoupper($catKey) === $categoryUpper) {
                    $specMap = $map;
                    break;
                }
            }

            $existingKeys = array_map('strtolower', array_column($this->specs, 'key'));
            foreach ($specMap['defaults'] as $defaultKey) {
                if (count($this->specs) >= 5) {
                    break;
                }
                if (!in_array(strtolower($defaultKey), $existingKeys)) {
                    $this->specs[] = ['key' => $defaultKey, 'value' => ''];
                }
            }

            // If still less than 5 rows, pad with blank rows
            while (count($this->specs) < 5) {
                $this->specs[] = ['key' => '', 'value' => ''];
            }
        } else {
            $this->category = $this->availableCategories[0] ?? 'CPU';
            $this->specs = $this->initializeSpecsForCategory($this->category);
            $this->has_specs = true;
        }
    }

    public function addSpec()
    {
        $categoryUpper = strtoupper($this->category ?? '');
        $specMap = self::CATEGORY_SPECS['default'];
        foreach (self::CATEGORY_SPECS as $catKey => $map) {
            if (strtoupper($catKey) === $categoryUpper) {
                $specMap = $map;
                break;
            }
        }

        $existingKeys = array_map('strtolower', array_filter(array_column($this->specs, 'key')));
        
        // Find a suggestion from defaults first that is not already in the list
        $suggestedKey = '';
        foreach ($specMap['defaults'] as $key) {
            if (!in_array(strtolower($key), $existingKeys)) {
                $suggestedKey = $key;
                break;
            }
        }

        // If all defaults are present, find a suggestion from additional keys
        if (empty($suggestedKey)) {
            foreach ($specMap['additional'] as $key) {
                if (!in_array(strtolower($key), $existingKeys)) {
                    $suggestedKey = $key;
                    break;
                }
            }
        }

        $this->specs[] = ['key' => $suggestedKey, 'value' => ''];
    }

    public function removeSpec($index)
    {
        unset($this->specs[$index]);
        $this->specs = array_values($this->specs);
    }

    public function getSpecPlaceholder($key)
    {
        $keyLower = strtolower(trim($key ?? ''));
        $placeholders = [
            'socket' => 'e.g. AM5, LGA1700',
            'tdp' => 'e.g. 65W, 125W',
            'cores' => 'e.g. 8, 16',
            'threads' => 'e.g. 16, 32',
            'boost_clock' => 'e.g. 5.4 GHz',
            'brand' => 'e.g. AMD, Intel, NVIDIA, Corsair',
            'base_clock' => 'e.g. 3.8 GHz',
            'generation' => 'e.g. Ryzen 7000, 14th Gen',
            'architecture' => 'e.g. Zen 4, Raptor Lake',
            'vram' => 'e.g. 16GB GDDR6X',
            'length' => 'e.g. 340mm',
            'pcie_version' => 'e.g. PCIe 4.0 x16',
            'model' => 'e.g. RTX 4080 Super',
            'slot_width' => 'e.g. 3-slot',
            'chipset' => 'e.g. B650, Z790',
            'form_factor' => 'e.g. ATX, Micro-ATX, Mini-ITX',
            'memory_type' => 'e.g. DDR5, DDR4',
            'max_memory_speed' => 'e.g. 7200MHz',
            'memory_slots' => 'e.g. 4',
            'vrm_power_delivery' => 'e.g. 14+2+1 Phases',
            'type' => 'e.g. DDR5, NVMe M.2 SSD, AIO Liquid Cooler',
            'capacity' => 'e.g. 32GB (2x16GB), 2TB',
            'speed' => 'e.g. 6000MHz, 7300 MB/s',
            'modules' => 'e.g. 2',
            'voltage' => 'e.g. 1.35V',
            'performance_tier' => 'e.g. High-Performance',
            'interface' => 'e.g. NVMe PCIe 4.0 x4, SATA III',
            'read_speed' => 'e.g. 7300 MB/s',
            'write_speed' => 'e.g. 6400 MB/s',
            'wattage' => 'e.g. 850W',
            'efficiency' => 'e.g. 80+ Gold',
            'modular' => 'e.g. Full Modular',
            'certification' => 'e.g. Cybenetics Gold',
            'fan_size' => 'e.g. 135mm, 120mm',
            'max_gpu_length' => 'e.g. 360mm',
            'max_cooler_height' => 'e.g. 165mm',
            'motherboard_support' => 'e.g. ATX, Micro-ATX, Mini-ITX',
            'radiator_support' => 'e.g. 360mm, 280mm',
            'radiator' => 'e.g. 360mm, 240mm',
            'color' => 'e.g. Black, White',
            'weight' => 'e.g. 7.5 kg, 80g',
            'tdp_rating' => 'e.g. 250W',
            'socket_support' => 'e.g. LGA1700, AM5, AM4',
            'fan_rpm' => 'e.g. 600-2000 RPM',
            'noise_level' => 'e.g. 15-30 dBA',
            'size' => 'e.g. 27", Extra Large (900x400mm)',
            'resolution' => 'e.g. 2560x1440',
            'refresh_rate' => 'e.g. 144Hz, 240Hz',
            'panel_type' => 'e.g. IPS, OLED',
            'response_time' => 'e.g. 1ms (GtG)',
            'hdr' => 'e.g. DisplayHDR 400',
            'switch_type' => 'e.g. Cherry MX Red, Linear',
            'layout' => 'e.g. ANSI (US), 75% Layout',
            'backlight' => 'e.g. Per-Key RGB',
            'connectivity' => 'e.g. USB Type-C, Bluetooth, 2.4GHz Wireless',
            'dpi' => 'e.g. 26000 DPI',
            'sensor' => 'e.g. Focus Pro 30K',
            'buttons' => 'e.g. 5, 8',
            'driver' => 'e.g. 50mm Neodymium',
            'surround' => 'e.g. 7.1 Spatial Audio',
            'frequency_response' => 'e.g. 20Hz - 20kHz',
            'mic_type' => 'e.g. Cardioid, Detachable',
            'surface' => 'e.g. Micro-woven Cloth',
            'thickness' => 'e.g. 4mm',
            'rgb' => 'e.g. 2-Zone RGB',
            'pattern' => 'e.g. Cardioid, Supercardioid',
            'fps' => 'e.g. 60 FPS',
            'fov' => 'e.g. 90 Degrees',
            'mic_built_in' => 'e.g. Yes, Dual Omni-directional',
            'watts' => 'e.g. 120W Peak, 60W RMS',
            'channels' => 'e.g. 2.1 Channels',
            'warranty' => 'e.g. 3 Years',
            'dimensions' => 'e.g. 450 x 230 x 480 mm',
            'material' => 'e.g. Tempered Glass, Steel',
            'wireless' => 'e.g. Yes'
        ];

        return $placeholders[$keyLower] ?? __('messages.admin.product_form.value_placeholder');
    }

    public function loadPresetSpecs($categoryName)
    {
        $categoryUpper = strtoupper($categoryName ?? '');
        $specMap = self::CATEGORY_SPECS['default'];
        foreach (self::CATEGORY_SPECS as $catKey => $map) {
            if (strtoupper($catKey) === $categoryUpper) {
                $specMap = $map;
                break;
            }
        }

        // Combine defaults and additional keys for advanced specifications
        $allKeys = array_unique(array_merge($specMap['defaults'], $specMap['additional']));

        // Build a map of existing specs
        $existingSpecs = [];
        foreach ($this->specs as $spec) {
            if (!empty($spec['key'])) {
                $existingSpecs[strtolower(trim($spec['key']))] = $spec['value'];
            }
        }

        // Generate the new specs array
        $newSpecs = [];
        foreach ($allKeys as $key) {
            $keyLower = strtolower(trim($key));
            $value = $existingSpecs[$keyLower] ?? '';
            $newSpecs[] = ['key' => $key, 'value' => $value];
        }

        $this->specs = $newSpecs;
    }

    public function save()
    {
        if ($this->has_specs) {
            // Filter out completely empty spec rows before validation
            $this->specs = array_values(array_filter($this->specs, function ($item) {
                return !empty(trim($item['key'] ?? '')) || !empty(trim($item['value'] ?? ''));
            }));
        } else {
            $this->specs = [];
        }

        $this->validate();

        // Handle Image Upload
        $imagePath = null;

        if ($this->image && !is_string($this->image)) {
            // New file upload — convert to WebP via ImageService
            // Delete old image first if updating
            if ($this->product && $this->product->image) {
                ImageService::delete($this->product->image);
            }

            $result = ImageService::upload($this->image, 'products');
            $imagePath = $result['image'];
        } elseif (!empty($this->existingImage) && filter_var($this->existingImage, FILTER_VALIDATE_URL)) {
            // URL entered in the fallback input field
            $imagePath = $this->existingImage;
        } elseif ($this->product) {
            // Keep existing image (no change)
            $imagePath = $this->product->image;
        }

        // Sanitize Strings
        $name = strip_tags($this->name);
        $description = strip_tags($this->description); // Or use a cleaner if allowing HTML

        $data = [
            'name' => $name,
            'slug' => Str::slug($name),
            'price' => $this->price,
            'description' => $description,
            'category' => $this->category,
            'stock' => $this->stock,
            'image' => $imagePath,
            'is_active' => $this->is_active,
            'has_specs' => $this->has_specs,
            'specs' => $this->has_specs ? collect($this->specs)->mapWithKeys(function ($item) {
                return [strip_tags($item['key']) => strip_tags($item['value'])];
            })->toArray() : [],
        ];

        if ($this->product) {
            $this->product->update($data);
            
            ActivityLog::log('updated_product', 'Updated product: ' . $name, $this->product);

            session()->flash('success', 'Product updated successfully.');
        } else {
            $product = Product::create($data);

            ActivityLog::log('created_product', 'Created new product: ' . $name, $product);

            session()->flash('success', 'Product created successfully.');
        }

        return $this->redirect(route('admin.products'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.product-form')
            ->layout('components.layouts.admin', ['title' => $this->product ? 'Edit Product' : 'Create Product']);
    }
}
