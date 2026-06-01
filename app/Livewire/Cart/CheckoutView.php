<?php

namespace App\Livewire\Cart;

use Livewire\Component;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Wayl\Facades\Wayl;

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
        $order = DB::transaction(function () {
            // Validate stock for all cart items before proceeding
            foreach ($this->cartItems as $key => $item) {
                $productId = $item['id'] ?? $key;
                $product = \App\Models\Product::lockForUpdate()->find($productId);

                if (!$product) {
                    throw new \Exception(__('messages.shop.product_not_found') ?? 'Product not found: ' . ($item['name'] ?? 'Unknown'));
                }

                if ($product->stock < $item['quantity']) {
                    throw new \Exception(
                        ($item['name'] ?? 'Product') . ' - ' . (__('messages.shop.insufficient_stock') ?? 'Insufficient stock. Available: ' . $product->stock)
                    );
                }
            }

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

            // Create Order Items & Decrement Stock
            foreach ($this->cartItems as $key => $item) {
                $productId = $item['id'] ?? $key;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $productId,
                    'product_name' => $item['name'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);

                // Decrement product stock
                \App\Models\Product::where('id', $productId)
                    ->decrement('stock', $item['quantity']);
            }
            
            return $order;
        });

        // Redirect based on payment method
        if ($this->paymentMethod === 'wayl') {
            try {
                $lineItems = [];
                foreach ($this->cartItems as $item) {
                    $qty = (int) ($item['quantity'] ?? 1);
                    $subTotal = (int) ($item['price']) * $qty;
                    $lineItems[] = [
                        'label' => $qty . 'x ' . ($item['name'] ?? 'PC Part'),
                        'amount' => $subTotal,
                        'type' => 'increase',
                    ];
                }

                $response = Wayl::links()->create([
                    'referenceId' => (string) $order->id,
                    'total' => (int) $this->total,
                    'currency' => 'IQD',
                    'lineItem' => $lineItems,
                    'webhookUrl' => url('/api/webhooks/wayl'),
                    'webhookSecret' => env('WAYL_WEBHOOK_SECRET', 'default_secret_string_min_10_chars'),
                    'redirectionUrl' => route('home', ['payment' => 'success'])
                ]);

                // Clear Session
                session()->forget(['cart', 'delivery_address']);

                session()->flash('success', 'Redirecting to payment gateway...');
                return redirect($response['data']['url']);

            } catch (\Wayl\Exceptions\WaylException $e) {
                // If there's an error, mark order as cancelled
                $order->update(['status' => 'cancelled']);
                
                $errorMsg = $e->getMessage();
                $respData = $e->getResponse();
                
                if (!empty($respData) && is_array($respData)) {
                    $errorMsg .= ' - ' . json_encode($respData);
                }
                
                session()->flash('error', 'Payment gateway error: ' . $errorMsg);
                return $this->redirect(route('checkout'), navigate: true);
            }
        }

        // Clear Session for COD
        session()->forget(['cart', 'delivery_address']);

        // Redirect with success
        session()->flash('success', 'Order placed successfully! Order ID: #' . ($order->id ?? 'NEW'));
        return $this->redirect(route('home'), navigate: true);
    }

    public function render()
    {
        return view('livewire.cart.checkout-view');
    }
}
