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
                        $fail('The image must be a file of type: ' . implode(', ', $allowedMimes) . '.');
                    }
                    if ($value->getSize() > 5120 * 1024) { // 5MB
                         $fail('The image must not be greater than 5MB.');
                    }
                }, 
            ],
            'is_active' => 'boolean',
            'specs' => 'nullable|array',
            'specs.*.key' => 'required|string|max:100',
            'specs.*.value' => 'required|string|max:255',
        ];
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
        }
    }

    public function addSpec()
    {
        $this->specs[] = ['key' => '', 'value' => ''];
    }

    public function removeSpec($index)
    {
        unset($this->specs[$index]);
        $this->specs = array_values($this->specs);
    }

    public function save()
    {
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
            
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'updated_product',
                'description' => 'Updated product: ' . $name,
                'subject_type' => Product::class,
                'subject_id' => $this->product->id,
                'ip_address' => request()->ip(),
            ]);

            session()->flash('success', 'Product updated successfully.');
        } else {
            $product = Product::create($data);

            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'created_product',
                'description' => 'Created new product: ' . $name,
                'subject_type' => Product::class,
                'subject_id' => $product->id,
                'ip_address' => request()->ip(),
            ]);

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
