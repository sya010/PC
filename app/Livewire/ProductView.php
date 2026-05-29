<?php

namespace App\Livewire;

use App\Models\Product;
use App\Traits\ManageCart;
use Livewire\Component;

class ProductView extends Component
{
    use ManageCart;

    public Product $product;
    public $activeImage;
    public $quantity = 1;

    public function mount($id)
    {
        $this->product = Product::findOrFail($id);
        $this->activeImage = $this->product->image_url;
    }

    public function selectImage($image)
    {
        $this->activeImage = $image;
    }

    public function addToCart()
    {
        if (!auth()->check()) {
            $this->redirect(route('login'), navigate: true);
            return;
        }

        if ($this->product->stock <= 0) {
            $this->dispatch('notify', type: 'error', message: __('messages.shop.out_of_stock') ?? 'This product is out of stock!');
            return;
        }

        $cart = session()->get('cart', []);
        $id = $this->product->id;
        $currentQty = isset($cart[$id]) ? $cart[$id]['quantity'] : 0;

        // Check if adding would exceed stock
        if (($currentQty + $this->quantity) > $this->product->stock) {
            $this->dispatch('notify', type: 'error', message: __('messages.shop.insufficient_stock') ?? 'Not enough stock available.');
            return;
        }

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] += $this->quantity;
        } else {
            $cart[$id] = [
                "name" => $this->product->name,
                "quantity" => $this->quantity,
                "price" => $this->product->price,
                "image" => $this->product->image_url
            ];
        }
        
        session()->put('cart', $cart);
        $this->dispatch('cart-updated');
        $this->dispatch('notify', message: 'Added to cart successfully!');
    }

    public function render()
    {
        // Get related products (same category)
        $relatedProducts = Product::where('category', $this->product->category)
            ->where('id', '!=', $this->product->id)
            ->take(4)
            ->get();

        return view('livewire.product-view', [
            'relatedProducts' => $relatedProducts
        ]);
    }
}
