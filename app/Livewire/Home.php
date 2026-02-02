<?php

namespace App\Livewire;

use Livewire\Component;
use App\Traits\ManageCart;
use App\Models\Product;

class Home extends Component
{
    use ManageCart;

    public $featuredProducts = [];
    public $categories = [];
    public $peripheralProducts = [];

    public function mount()
    {
        // Get real category counts from database
        $categoryCounts = Product::where('is_active', true)
            ->selectRaw('category, count(*) as count')
            ->groupBy('category')
            ->pluck('count', 'category')
            ->toArray();

        // Categories with real images and counts
        $this->categories = [
            [
                'id' => 'cpu',
                'name' => 'Processors',
                'image' => 'https://images.unsplash.com/photo-1591799264318-7e6ef8ddb7ea?w=300&h=200&fit=crop',
                'count' => ($categoryCounts['CPU'] ?? 0) . ' Products'
            ],
            [
                'id' => 'gpu',
                'name' => 'Graphics Cards',
                'image' => 'https://images.unsplash.com/photo-1587202372775-e229f172b9d7?w=300&h=200&fit=crop',
                'count' => ($categoryCounts['GPU'] ?? 0) . ' Products'
            ],
            [
                'id' => 'motherboard',
                'name' => 'Motherboards',
                'image' => 'https://images.unsplash.com/photo-1518770660439-4636190af475?w=300&h=200&fit=crop',
                'count' => ($categoryCounts['Motherboard'] ?? 0) . ' Products'
            ],
            [
                'id' => 'keyboard',
                'name' => 'Keyboards',
                'image' => 'https://images.unsplash.com/photo-1541140532154-b024d705b90a?w=300&h=200&fit=crop',
                'count' => ($categoryCounts['Keyboard'] ?? 0) . ' Products'
            ],
            [
                'id' => 'mouse',
                'name' => 'Gaming Mice',
                'image' => 'https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?w=300&h=200&fit=crop',
                'count' => ($categoryCounts['Mouse'] ?? 0) . ' Products'
            ],
            [
                'id' => 'headset',
                'name' => 'Headsets',
                'image' => 'https://images.unsplash.com/photo-1599669454699-248893623440?w=300&h=200&fit=crop',
                'count' => ($categoryCounts['Headset'] ?? 0) . ' Products'
            ],
            [
                'id' => 'monitor',
                'name' => 'Monitors',
                'image' => 'https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?w=300&h=200&fit=crop',
                'count' => ($categoryCounts['Monitor'] ?? 0) . ' Products'
            ],
            [
                'id' => 'microphone',
                'name' => 'Microphones',
                'image' => 'https://images.unsplash.com/photo-1590602847861-f357a9332bbc?w=300&h=200&fit=crop',
                'count' => ($categoryCounts['Microphone'] ?? 0) . ' Products'
            ],
        ];

        // Get real featured products from database (latest 8 products)
        // Get real featured products from database (latest 8 products)
        $this->featuredProducts = Product::where('is_active', true)
            ->inRandomOrder()
            ->take(8)
            ->get();

        // Get peripheral products specifically for a "Gaming Gear" section
        $this->peripheralProducts = Product::where('is_active', true)
            ->whereIn('category', ['Mouse', 'Keyboard', 'Headset', 'Mousepad'])
            ->inRandomOrder()
            ->take(4)
            ->get();
    }

    public function render()
    {
        return view('livewire.home');
    }
}
