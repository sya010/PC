<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\Order;
use Livewire\WithPagination;

class Orders extends Component
{
    use WithPagination;

    public function render()
    {
        $orders = Order::where('user_id', auth()->id())
            ->latest()
            ->paginate(5);

        return view('livewire.user.orders', [
            'orders' => $orders
        ])->layout('components.layouts.user');
    }
}
