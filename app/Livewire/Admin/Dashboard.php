<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.admin.dashboard', [
            'totalSales' => Order::where('status', 'completed')->sum('total_amount'),
            'activeOrders' => Order::whereIn('status', ['pending', 'processing'])->count(),
            'totalProducts' => Product::count(),
            'newUsers' => User::where('created_at', '>=', now()->subDays(30))->count(),
            'recentOrders' => Order::latest()->take(5)->get(),
        ])->layout('components.layouts.admin', ['title' => 'Dashboard']);
    }
}
