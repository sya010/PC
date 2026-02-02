<?php

namespace App\Livewire\Cart;

use Livewire\Component;

class DeliveryView extends Component
{
    public $address = [
        'full_name' => '',
        'street_address' => '',
        'city' => '',
        'state' => '',
        'zip_code' => '',
        'phone' => '',
        'email' => '',
    ];

    protected $rules = [
        'address.full_name' => 'required|min:3',
        'address.street_address' => 'required',
        'address.city' => 'required',
        'address.state' => 'required',
        'address.zip_code' => 'required',
        'address.phone' => 'required',
        'address.email' => 'required|email',
    ];

    public function mount()
    {
        // Pre-fill if user is logged in or session has data
        if (auth()->check()) {
            $this->address['full_name'] = auth()->user()->name;
            $this->address['email'] = auth()->user()->email;
        }
        
        $sessionAddress = session()->get('delivery_address');
        if ($sessionAddress) {
            $this->address = array_merge($this->address, $sessionAddress);
        }
    }

    public function saveDeliveryInfo()
    {
        $this->validate();

        session()->put('delivery_address', $this->address);

        return $this->redirect(route('checkout'), navigate: true);
    }

    public function render()
    {
        $cartItems = session()->get('cart', []);
        if (empty($cartItems)) {
            return redirect()->route('cart');
        }

        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return view('livewire.cart.delivery-view', [
            'cartItems' => $cartItems,
            'total' => $total,
        ]);
    }
}
