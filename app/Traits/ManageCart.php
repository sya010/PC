<?php

namespace App\Traits;

trait ManageCart
{
    public function addToCart($id, $name, $price, $image, $category = 'product')
    {
        $cart = session()->get('cart', []);

        // Check stock availability
        $product = \App\Models\Product::find($id);
        $currentQty = isset($cart[$id]) ? $cart[$id]['quantity'] : 0;

        if ($product && ($currentQty + 1) > $product->stock) {
            $this->dispatch('notify', type: 'error', message: __('messages.shop.insufficient_stock') ?? 'Maximum stock reached.');
            return;
        }

        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                'name' => $name,
                'quantity' => 1,
                'price' => $price,
                'image' => $image,
                'category' => $category
            ];
        }

        session()->put('cart', $cart);
        $this->dispatch('cart-updated'); // Updates Navbar count
        $this->dispatch('notify', message: 'Added to cart successfully!'); // Optional toast
    }
}
