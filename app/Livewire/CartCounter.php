<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;

class CartCounter extends Component
{
    public $count = 0;
    public $cartItems = [];
    public $total = 0;

    public function mount()
    {
        $this->updateCount();
    }

    #[On('cart-updated')]
    public function updateCount()
    {
        $cart = session()->get('cart', []);
        $count = 0;
        foreach ($cart as $item) {
            $count += ($item['quantity'] ?? 1);
        }
        $this->count = $count;
        $this->cartItems = $cart;
        $this->calculateTotal();
    }

    public function increaseQuantity($id)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$id])) {
            // Check stock availability
            $product = \App\Models\Product::find($id);
            if ($product && $cart[$id]['quantity'] >= $product->stock) {
                $this->dispatch('notify', type: 'error', message: __('messages.shop.insufficient_stock') ?? 'Maximum stock reached.');
                return;
            }

            $cart[$id]['quantity']++;
            session()->put('cart', $cart);
            $this->updateCount();
            $this->dispatch('cart-updated');
        }
    }

    public function decreaseQuantity($id)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$id])) {
            if ($cart[$id]['quantity'] > 1) {
                $cart[$id]['quantity']--;
            } else {
                unset($cart[$id]);
            }
            session()->put('cart', $cart);
            $this->updateCount();
            $this->dispatch('cart-updated');
        }
        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $this->total = 0;
        foreach ($this->cartItems as $item) {
            $this->total += ($item['price'] ?? 0) * ($item['quantity'] ?? 1);
        }
    }

    public function render()
    {
        return view('livewire.cart-counter');
    }
}
