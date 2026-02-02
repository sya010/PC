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
        $orders = Order::query()
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
            Order::find($orderId)->update(['status' => $status]);
            session()->flash('success', "Order #{$orderId} status updated to {$status}.");
        }
    }
}
