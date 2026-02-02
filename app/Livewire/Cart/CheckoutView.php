<?php

namespace App\Livewire\Cart;

use Livewire\Component;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class CheckoutView extends Component
{
    public $address = [];
    public $paymentMethod = 'cod';
    public $cartItems = [];
    public $total = 0;

    public function mount()
    {
        $this->address = session()->get('delivery_address', []);
        $this->cartItems = session()->get('cart', []);
        
        if (empty($this->cartItems) || empty($this->address)) {
            return redirect()->route('cart');
        }

        foreach ($this->cartItems as $item) {
            $this->total += $item['price'] * $item['quantity'];
        }
    }

    public function placeOrder()
    {
        // DB Transaction to ensure data integrity
        DB::transaction(function () {
            // Create Order
            $order = Order::create([
                'user_id' => auth()->id(),
                'full_name' => $this->address['full_name'],
                'email' => $this->address['email'],
                'phone' => $this->address['phone'],
                'address' => $this->address['street_address'],
                'city' => $this->address['city'],
                'state' => $this->address['state'],
                'zip_code' => $this->address['zip_code'],
                'total_amount' => $this->total,
                'status' => 'pending',
                'payment_method' => $this->paymentMethod,
                'payment_status' => 'pending',
            ]);

            // Create Order Items
            foreach ($this->cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'] ?? null,
                    'product_name' => $item['name'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
            }
        });

        // Clear Session
        session()->forget(['cart', 'delivery_address']);

        // Redirect with success
        session()->flash('success', 'Order placed successfully! Order ID: #' . (Order::latest()->first()->id ?? 'NEW'));
        return $this->redirect(route('home'), navigate: true);
    }

    public function render()
    {
        return view('livewire.cart.checkout-view');
    }
}
