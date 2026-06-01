<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Order;

class Orders extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';

    public function render()
    {
        $orders = Order::with('items.product')
            ->when($this->search, function ($query) {
                $query->where('id', 'like', '%'.$this->search.'%')
                      ->orWhere('full_name', 'like', '%'.$this->search.'%')
                      ->orWhere('email', 'like', '%'.$this->search.'%');
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.orders', [
            'orders' => $orders
        ])->layout('components.layouts.admin', ['title' => 'Orders']);
    }

    public function updateStatus($orderId, $status)
    {
        $validStatuses = ['pending', 'processing', 'completed', 'cancelled'];

        if (in_array($status, $validStatuses)) {
            $order = Order::find($orderId);
            if (!$order) return;

            $updateData = ['status' => $status];

            // Only sync payment_status for COD orders — never touch Wayl
            if ($status === 'completed' && $order->payment_method === 'cod') {
                $updateData['payment_status'] = 'paid';
            }

            $order->update($updateData);
            session()->flash('success', "Order #{$orderId} status updated to {$status}.");
        }
    }
}
