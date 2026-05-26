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
    public $specs = [];
    public $availableCategories = [];

    const CATEGORY_SPECS = [
        'CPU' => [
            'defaults' => ['socket', 'tdp', 'cores', 'threads', 'boost_clock'],
            'additional' => ['brand', 'base_clock', 'generation', 'architecture']
        ],
        'GPU' => [
            'defaults' => ['vram', 'tdp', 'length', 'pcie_version', 'brand'],
            'additional' => ['model', 'slot_width', 'generation', 'architecture']
        ],
        'MOTHERBOARD' => [
            'defaults' => ['socket', 'chipset', 'form_factor', 'memory_type', 'max_memory_speed'],
            'additional' => ['brand', 'memory_slots', 'pcie_version', 'vrm_power_delivery']
        ],
        'RAM' => [
            'defaults' => ['type', 'capacity', 'speed', 'modules', 'brand'],
            'additional' => ['model', 'voltage', 'performance_tier']
        ],
        'STORAGE' => [
            'defaults' => ['capacity', 'interface', 'read_speed', 'write_speed', 'brand'],
            'additional' => ['model', 'form_factor']
        ],
        'PSU' => [
            'defaults' => ['wattage', 'efficiency', 'modular', 'brand', 'model'],
            'additional' => ['certification', 'fan_size']
        ],
        'CASE' => [
            'defaults' => ['form_factor', 'gpu_length', 'cooler_height', 'radiator', 'brand'],
            'additional' => ['model', 'color', 'weight']
        ],
        'COOLING' => [
            'defaults' => ['type', 'tdp_rating', 'radiator', 'sockets', 'brand'],
            'additional' => ['model', 'fan_rpm', 'noise_level']
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
            'specs' => 'nullable|array',
            'specs.*.key' => 'required|string|max:100',
            'specs.*.value' => 'required|string|max:255',
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
            
            if ($this->product->specs) {
                foreach ($this->product->specs as $key => $value) {
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

    public function save()
    {
        // Filter out completely empty spec rows before validation
        $this->specs = array_values(array_filter($this->specs, function ($item) {
            return !empty(trim($item['key'] ?? '')) || !empty(trim($item['value'] ?? ''));
        }));

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
            'specs' => collect($this->specs)->mapWithKeys(function ($item) {
                return [strip_tags($item['key']) => strip_tags($item['value'])];
            })->toArray(),
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
