<?php

namespace App\Http\Livewire\Admin\Caja;

use App\Http\Controllers\API\ApiController;
use App\Models\Product;
use Livewire\Component;

class ShoppingCart extends Component
{
    public $products, $search;

    public function mount()
    {
        $this->products = $this->getProducts();
        $this->search = '';
    }

    public function render()
    {
        return view('livewire.admin.caja.shopping-cart');
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

//    public function editUnitQuantity($productId, $cantidad)
//    {
//        $productStock = Product::where('id', $productId)->first();
//        $product = $this->products->where('id', $productId)->first();
//        $product['cantidad'] = $cantidad;
//    }

    public function editUnitQuantity($productId, $cantidad)
    {
        $productStock = Product::where('id', $productId)->first();
        $product = $this->products->where('id', $productId)->first();

        // Comprobar si la cantidad es válida (es decir, no excede el stock)
        if ($cantidad <= $productStock->stock) {
            $product['cantidad'] = $cantidad;
            session()->flash('message', 'La cantidad del producto '.$product['sku'].' se Actualizo');
        } else {
            session()->flash('error', 'La cantidad supera el stock disponible para el producto '.$product['sku'].' el cual solo tiene '.$productStock->stock.' unidades de stock actualmente.');
        }
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
            $cart = session()->get('cart');
            if ($cart && count($cart) > 0) {
                $count = 0;
                foreach ($cart as $id) {
                    if ($id == $product->id) {
                        $count++;
                    }
                }
                if ($count >= $product->stock) {
                    session()->flash('error', 'No se pueden agregar más productos. Se ha alcanzado el límite del stock disponible para el producto '.$product->sku.' su stockmaximo actual es de '.$product->stock.' unidades.');
                    return;
                }
            }

            session()->push('cart', $product->id);
            $this->products = $this->getProducts();
            $this->emit('productAdded');
        }
    }

}
