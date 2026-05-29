<?php

namespace App\Livewire;

use Livewire\Component;

class Cart extends Component
{
    public $cartItems = [];
    public $total = 0;
    public $couponCode = '';
    public $discount = 0;

    public function mount()
    {
        $this->loadCart();
    }

    public function loadCart()
    {
        $this->cartItems = session()->get('cart', []);
        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $subtotal = 0;
        foreach ($this->cartItems as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        
        $this->total = $subtotal - $this->discount;
    }

    public function applyCoupon()
    {
        if (strtolower($this->couponCode) === 'save10') {
            $subtotal = 0;
            foreach ($this->cartItems as $item) {
                $subtotal += $item['price'] * $item['quantity'];
            }
            $this->discount = $subtotal * 0.10; // 10% discount
            $this->calculateTotal();
            $this->dispatch('toast-message', message: 'Coupon applied successfully!');
        } else {
            $this->discount = 0;
            $this->calculateTotal();
            $this->dispatch('toast-message', message: 'Invalid coupon code.');
        }
    }

    public function updateQuantity($id, $quantity)
    {
        if ($quantity < 1) {
            return;
        }

        if (isset($this->cartItems[$id])) {
            // Check stock availability
            $product = \App\Models\Product::find($id);
            if ($product && $quantity > $product->stock) {
                $quantity = $product->stock;
                $this->dispatch('notify', type: 'error', message: __('messages.shop.insufficient_stock') ?? 'Only ' . $product->stock . ' available in stock.');
            }

            $this->cartItems[$id]['quantity'] = $quantity;
            session()->put('cart', $this->cartItems);
            $this->calculateTotal();
            $this->dispatch('cart-updated');
        }
    }

    public function removeItem($id)
    {
        if (isset($this->cartItems[$id])) {
            unset($this->cartItems[$id]);
            session()->put('cart', $this->cartItems);
            $this->calculateTotal();
            $this->dispatch('cart-updated');
        }
    }

    public function render()
    {
        return view('livewire.cart')->layout('components.layouts.app');
    }
}
