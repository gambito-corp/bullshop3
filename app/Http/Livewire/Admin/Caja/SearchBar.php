<?php

namespace App\Http\Livewire\Admin\Caja;

use App\Http\Controllers\API\ApiController;
use App\Models\Product;
use Livewire\Component;

class SearchBar extends Component
{
    public $search = '';

    public function render()
    {
        return view('livewire.admin.caja.search-bar');
    }

    public function updateProductStock($product)
    {
        $client = ApiController::Woocomerce2();
        $stock = $client->get('products/' . $product->wp_id);
        $product->stock = $stock->stock_quantity;
        $product->update();
    }

    public function searchProduct()
    {
        $product = Product::Search($this->search)->first();

        if ($product->category->name != 'Limpieza') {
            $this->updateProductStock($product);
        } else {
            $product->stock = 1;
        }

        if ($product->stock <= 0) {
            session()->flash('error', 'El producto está agotado, se actualizó el stock a 0');
            return;
        }

        if ($product) {
            session()->push('cart', $product->id);
            $this->emit('productAdded');
        }
    }
}
