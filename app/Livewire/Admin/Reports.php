<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Reports extends Component
{
    public string $period = '30'; // '7', '30', '90', '365', 'custom', 'all'
    public ?string $dateFrom = null;
    public ?string $dateTo = null;

    public bool $showOutOfStockModal = false;
    public bool $showActiveDeliveriesModal = false;

    public function toggleOutOfStockModal(): void
    {
        $this->showOutOfStockModal = !$this->showOutOfStockModal;
    }

    public function toggleActiveDeliveriesModal(): void
    {
        $this->showActiveDeliveriesModal = !$this->showActiveDeliveriesModal;
    }

    public function mount(): void
    {
        $this->dateTo   = now()->toDateString();
        $this->dateFrom = now()->subDays(29)->toDateString();
    }

    /** Recalculate dates whenever a quick-period button is clicked */
    public function setPeriod(string $period): void
    {
        $this->period = $period;

        if ($period !== 'custom' && $period !== 'all') {
            $days = (int) $period;
            $this->dateFrom = now()->subDays($days - 1)->toDateString();
            $this->dateTo   = now()->toDateString();
        } elseif ($period === 'all') {
            $this->dateFrom = null;
            $this->dateTo   = null;
        }
    }

    /** Called when the user manually picks dates in custom mode */
    public function applyCustomRange(): void
    {
        $this->period = 'custom';
    }

    private function dateQuery($query)
    {
        if ($this->period === 'all') {
            return $query;
        }
        $from = Carbon::parse($this->dateFrom)->startOfDay();
        $to   = Carbon::parse($this->dateTo)->endOfDay();
        return $query->whereBetween('created_at', [$from, $to]);
    }

    public function render()
    {
        // --- Revenue & Profit ---
        $revenueQuery  = Order::where('status', '!=', 'cancelled');
        $totalRevenue  = $this->dateQuery(clone $revenueQuery)->sum('total_amount');
        $estimatedProfit = $totalRevenue * 0.25;

        // --- Delivery Counts ---
        $activeDeliveries    = $this->dateQuery(Order::whereIn('status', ['pending', 'processing', 'shipped']))->count();
        $completedDeliveries = $this->dateQuery(Order::where('status', 'completed'))->count();
        $cancelledDeliveries = $this->dateQuery(Order::where('status', 'cancelled'))->count();

        // Total orders in range (for conversion stat)
        $totalOrders = $this->dateQuery(Order::query())->count();

        // --- Payment breakdown ---
        $codOrders  = $this->dateQuery(Order::where('payment_method', 'cod'))->count();
        $waylOrders = $this->dateQuery(Order::where('payment_method', 'wayl'))->count();

        // --- Stock / Inventory (always current) ---
        $totalProducts  = Product::count();
        $totalStock     = Product::sum('stock');
        $outOfStockCount = Product::where('stock', '<=', 0)->count();
        $lowStockProducts = Product::where('stock', '>', 0)
            ->where('stock', '<', 5)
            ->orderBy('stock', 'asc')
            ->limit(10)
            ->get();

        // Out of stock products list (for modal)
        $outOfStockProducts = Product::where('stock', '<=', 0)
            ->orderBy('name', 'asc')
            ->get();

        // Active delivery orders (for modal)
        $activeDeliveryOrders = Order::whereIn('status', ['pending', 'processing', 'shipped'])
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();

        // --- Top Selling Items (filtered by date range via orders) ---
        $topSellingQuery = OrderItem::select(
                'order_items.product_id',
                'order_items.product_name',
                DB::raw('SUM(order_items.quantity) as total_qty'),
                DB::raw('SUM(order_items.quantity * order_items.price) as total_sales')
            )
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.status', '!=', 'cancelled')
            ->groupBy('order_items.product_id', 'order_items.product_name')
            ->orderBy('total_qty', 'desc')
            ->limit(10);

        if ($this->period !== 'all') {
            $from = Carbon::parse($this->dateFrom)->startOfDay();
            $to   = Carbon::parse($this->dateTo)->endOfDay();
            $topSellingQuery->whereBetween('orders.created_at', [$from, $to]);
        }

        $topSellingItems = $topSellingQuery->get();

        // Attach live stock + image
        foreach ($topSellingItems as $item) {
            $product = Product::find($item->product_id);
            $item->current_stock = $product ? $product->stock : 0;
            $item->image_url     = $product ? $product->image_url : null;
        }

        // --- Revenue over time (sparkline data) — daily totals ---
        $dailyRevenue = [];
        if ($this->period !== 'all') {
            $from = Carbon::parse($this->dateFrom)->startOfDay();
            $to   = Carbon::parse($this->dateTo)->endOfDay();

            $rows = Order::selectRaw('DATE(created_at) as day, SUM(total_amount) as revenue')
                ->where('status', '!=', 'cancelled')
                ->whereBetween('created_at', [$from, $to])
                ->groupByRaw('DATE(created_at)')
                ->orderBy('day')
                ->get();

            // Build a zero-filled date map
            $cursor = $from->copy();
            while ($cursor->lte($to)) {
                $dailyRevenue[$cursor->toDateString()] = 0;
                $cursor->addDay();
            }
            foreach ($rows as $r) {
                $dailyRevenue[$r->day] = (float) $r->revenue;
            }
        }

        return view('livewire.admin.reports', [
            'totalRevenue'         => $totalRevenue,
            'estimatedProfit'      => $estimatedProfit,
            'activeDeliveries'     => $activeDeliveries,
            'completedDeliveries'  => $completedDeliveries,
            'cancelledDeliveries'  => $cancelledDeliveries,
            'totalOrders'          => $totalOrders,
            'codOrders'            => $codOrders,
            'waylOrders'           => $waylOrders,
            'totalProducts'        => $totalProducts,
            'totalStock'           => $totalStock,
            'outOfStockCount'      => $outOfStockCount,
            'lowStockProducts'     => $lowStockProducts,
            'outOfStockProducts'   => $outOfStockProducts,
            'activeDeliveryOrders' => $activeDeliveryOrders,
            'topSellingItems'      => $topSellingItems,
            'dailyRevenue'         => $dailyRevenue,
        ])->layout('components.layouts.admin', ['title' => 'Sales & Profit Reports']);
    }
}
