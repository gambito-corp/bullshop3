<?php

namespace App\Http\Livewire;

use App\Models\Product;
use Livewire\Component;

class Test extends Component
{
    public $products;

    public function mount()
    {
        $this->products = $this->getProducts();
    }

    public function render()
    {
        return view('livewire.test');
    }

    public function getProducts()
    {
        $cart = session()->get('cart', []);
        $coleccion = collect();
        foreach ($cart as $productId) {
            $product = Product::find($productId);
            $itemIndex = $coleccion->search(function ($item) use ($productId) {
                return $item['id'] === $productId;
            });
            if ($itemIndex !== false) {
                $item = $coleccion->get($itemIndex);
                $item['cantidad'] += 1;
                $item['total'] = $item['precio'] * $item['cantidad'];
                $coleccion->put($itemIndex, $item);
            } else {
                $item = [
                    'id' => $product->id,
                    'sku' => $product->sku,
                    'wp_id' => $product->wp_id,
                    'imagen' => $product->image,
                    'nombre' => $product->size != 'S/T' ? $product->name . ' ' . $product->size : $product->name,
                    'precio' => $product->price,
                    'cantidad' => 1,
                    'total' => $product->price,
                    'editMode' => false,
                ];
                $coleccion->push($item);
            }
        }
        return $coleccion;
    }

    public function remover($productId)
    {
        $cart = session()->get('cart', []);
        $filteredCart = array_filter($cart, function ($item) use ($productId) {
            return $item !== $productId;
        });
        session()->put('cart', $filteredCart);
        $this->products = $this->getProducts();
    }

    public function editUnitPrice($productId, $precio)
    {
        $product = $this->products->where('id', $productId)->first();
        $product['precio'] = $precio;
    }

    public function editUnitQuantity($productId, $cantidad)
    {
        $product = $this->products->where('id', $productId)->first();
        $product['cantidad'] = $cantidad;
    }
}
