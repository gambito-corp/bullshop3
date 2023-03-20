<?php

namespace App\Http\Livewire\Admin\Caja;

use App\Models\Product;
use Livewire\Component;

class ShoppingCart extends Component
{
    public $cart;

    protected $listeners = ['productPriceUpdated' => 'updateProductPrice'];

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
                        'editMode'  => false, // Agregar la clave 'editMode' con valor false
                    ];
                } else {
                    $items[$sku]['cantidad']++;
                }
            }
        }
        return $items;
    }

    public function updateProductPrice($productId, $newPrice)
    {
        foreach ($this->cart as &$product) {
            if ($product['id'] == $productId) {
                $product['precio'] = $newPrice;
                break;
            }
        }
    }
}
