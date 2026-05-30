<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Order;

class OrderView extends Component
{
    public Order $order;

    public function mount($id)
    {
        $this->order = Order::with('items.product')->findOrFail($id);
    }

    public function updateStatus($status)
    {
        $validStatuses = ['pending', 'processing', 'completed', 'cancelled'];

        if (in_array($status, $validStatuses)) {
            $updateData = ['status' => $status];

            // Only sync payment_status for COD orders — never touch Wayl
            if ($status === 'completed' && $this->order->payment_method === 'cod') {
                $updateData['payment_status'] = 'paid';
            }

            $this->order->update($updateData);
            $this->order->refresh();
        }
    }

    public function render()
    {
        return view('livewire.admin.order-view')
            ->layout('components.layouts.admin', ['title' => 'Order #' . $this->order->id]);
    }
}
