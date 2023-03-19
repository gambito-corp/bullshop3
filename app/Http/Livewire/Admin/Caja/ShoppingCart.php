<?php

namespace App\Http\Livewire\Admin\Caja;

use App\Models\Product;
use Livewire\Component;

class ShoppingCart extends Component
{
    public $cart;

    public function render()
    {
        $this->cart = $this->getCartItems();
        $this->dispatchBrowserEvent('productAdded');
        return view('livewire.admin.caja.shopping-cart');
    }

    public function getCartItems()
    {
        $cart = session()->get('cart', []);
        $items = [];
        foreach ($cart as $productId) {
            $product = Product::find($productId);
            if ($product) {
                $sku = $product->sku;
                if (!isset($items[$sku])) {
                    $items[$sku] = [
                        'id'        => $product->id,
                        'imagen'    => $product->image,
                        'nombre'    => $product->size != 'S/T' ? $product->name . ' ' . $product->size : $product->name,
                        'precio'    => $product->price,
                        'cantidad'  => 1,
                    ];
                } else {
                    $items[$sku]['cantidad']++;
                }
            }
        }
        return $items;
    }

    public function removeFromCart($productId)
    {
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
