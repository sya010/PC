<?php

namespace App\Traits;

trait ManageCart
{
    public function addToCart($id, $name, $price, $image, $category = 'product')
    {
        $cart = session()->get('cart', []);

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
