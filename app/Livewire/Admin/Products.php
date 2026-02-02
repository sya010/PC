<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;

class Products extends Component
{
    use WithPagination;

    public $search = '';

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function delete($id)
    {
        Product::find($id)->delete();
        session()->flash('success', 'Product deleted successfully.');
    }

    public function render()
    {
        $products = Product::where('name', 'like', '%'.$this->search.'%')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('livewire.admin.products', [
            'products' => $products
        ])->layout('components.layouts.admin', ['title' => 'Products Management']);
    }
}
