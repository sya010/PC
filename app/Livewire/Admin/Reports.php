<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class Reports extends Component
{
    public function render()
    {
        // Total Sales / Revenue (excluding cancelled orders)
        $totalRevenue = Order::where('status', '!=', 'cancelled')->sum('total_amount');
        
        // Estimated Net Profit (25% margin)
        $estimatedProfit = $totalRevenue * 0.25;

        // Deliveries Status counts
        $activeDeliveries = Order::whereIn('status', ['pending', 'processing', 'shipped'])->count();
        $completedDeliveries = Order::where('status', 'completed')->count();
        $cancelledDeliveries = Order::where('status', 'cancelled')->count();

        // Stock / Inventory
        $totalProducts = Product::count();
        $totalStock = Product::sum('stock');
        $outOfStockCount = Product::where('stock', '<=', 0)->count();
        $lowStockProducts = Product::where('stock', '>', 0)
            ->where('stock', '<', 5)
            ->orderBy('stock', 'asc')
            ->limit(10)
            ->get();

        // Top Selling Items (grouped by product)
        $topSellingItems = OrderItem::select(
                'product_id', 
                'product_name', 
                DB::raw('SUM(quantity) as total_qty'), 
                DB::raw('SUM(quantity * price) as total_sales')
            )
            ->groupBy('product_id', 'product_name')
            ->orderBy('total_qty', 'desc')
            ->limit(10)
            ->get();

        // Load current stock for the top selling items
        foreach ($topSellingItems as $item) {
            $product = Product::find($item->product_id);
            $item->current_stock = $product ? $product->stock : 0;
            $item->image_url = $product ? $product->image_url : null;
        }

        return view('livewire.admin.reports', [
            'totalRevenue' => $totalRevenue,
            'estimatedProfit' => $estimatedProfit,
            'activeDeliveries' => $activeDeliveries,
            'completedDeliveries' => $completedDeliveries,
            'cancelledDeliveries' => $cancelledDeliveries,
            'totalProducts' => $totalProducts,
            'totalStock' => $totalStock,
            'outOfStockCount' => $outOfStockCount,
            'lowStockProducts' => $lowStockProducts,
            'topSellingItems' => $topSellingItems,
        ])->layout('components.layouts.admin', ['title' => 'Sales & Profit Reports']);
    }
}
