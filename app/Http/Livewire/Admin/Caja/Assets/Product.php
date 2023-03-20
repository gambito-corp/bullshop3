<?php

namespace App\Http\Livewire\Admin\Caja\Assets;

use Livewire\Component;

class Product extends Component
{
    public $product, $editing, $newPrice;

    public function mount($product)
    {
        $this->product = $product;
        $this->editing = false;
        $this->newPrice = $this->product['precio'];
    }

    public function render()
    {
        return view('livewire.admin.caja.assets.product');
    }

    public function toggleEditing()
    {
        $this->editing = !$this->editing;
    }
    public function updateCartProduct()
    {
        $this->toggleEditing();
        $this->emit('productPriceUpdated', $this->product['id'], $this->product['precio']);
        $this->emit('cartUpdated');
    }

    public function removeFromCart($item)
    {
        dd($item);
        $cart = session()->get('cart', []);
        $productRemoved = null;

        foreach ($cart as $index => $item) {
            if ($item == $productId) {
                $productRemoved = $item;
                unset($cart[$index]);
            }
        }
        session()->forget('cart');
        session()->get('cart', []);
        session()->put('cart', $cart);
        $cart = session()->get('cart', []);
        $this->cart = $cart;
        $this->emit('cartUpdated');
        session()->flash('error', 'Producto eliminado del carrito.');
    }
}
