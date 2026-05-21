<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\Order;
use Livewire\WithPagination;
use Wayl\Facades\Wayl;
use Illuminate\Support\Facades\Log;

class Orders extends Component
{
    use WithPagination;

    public function render()
    {
        $orders = Order::with('items.product')
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(5);

        return view('livewire.user.orders', [
            'orders' => $orders
        ])->layout('components.layouts.user');
    }

    public function retryWaylPayment($orderId)
    {
        $order = Order::where('user_id', auth()->id())->findOrFail($orderId);

        if ($order->payment_method !== 'wayl' || $order->payment_status !== 'pending') {
            session()->flash('error', 'This order cannot be paid online.');
            return;
        }

        if ($order->created_at->diffInMinutes(now()) > 58) {
            session()->flash('error', 'The payment link has expired. Please create a new order.');
            return;
        }

        try {
            $response = Wayl::links()->find((string) $order->id);
            if (isset($response['data']['url'])) {
                return redirect($response['data']['url']);
            }
            session()->flash('error', 'Could not retrieve payment link.');
        } catch (\Wayl\Exceptions\WaylException $e) {
            Log::error('Wayl retry error', ['msg' => $e->getMessage(), 'resp' => $e->getResponse()]);
            session()->flash('error', 'Could not retrieve payment link: ' . $e->getMessage());
        }
    }
}
