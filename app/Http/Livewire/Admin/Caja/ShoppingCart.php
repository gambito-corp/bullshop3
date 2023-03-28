<?php

namespace App\Http\Livewire\Admin\Caja;

use App\Http\Controllers\API\ApiController;
use App\Models\Client;
use App\Models\Product;
use Livewire\Component;

class ShoppingCart extends Component
{
    public $products, $search, $dni, $cliente, $total, $cantidad, $montoMedio, $change, $efectivo, $resto, $tipo;

    public function mount()
    {
        $this->products = $this->getProducts();
        $this->search = '';
        $this->total = $this->products->sum('total');
        $this->cantidad = $this->products->sum('cantidad');
        $this->tipo = collect();
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
        $this->total = $this->products->sum('total');
        $this->cantidad = $this->products->sum('cantidad');
    }

    public function editUnitPrice($productId, $precio)
    {
        $product = $this->products->where('id', $productId)->first();
        $product['precio'] = $precio;
        $product['total'] = $product['cantidad'] * $precio;
        $this->products->put($this->products->search($product), $product);
        $this->total = $this->products->sum('total');
        $this->cantidad = $this->products->sum('cantidad');
        $this->emit('productAdded');
    }

    public function editUnitQuantity($productId, $cantidad)
    {
        $productStock = Product::where('id', $productId)->first();
        $product = $this->products->where('id', $productId)->first();

        // Comprobar si la cantidad es válida (es decir, no excede el stock)
        if ($cantidad <= $productStock->stock) {
            $product['cantidad'] = $cantidad;
            $product['total'] = $product['precio'] * $cantidad;
            $this->products->put($this->products->search($product), $product);
            $this->total = $this->products->sum('total');
            $this->cantidad = $this->products->sum('cantidad');
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

    public function fetchClientData()
    {
        switch ($this->dni)
        {
            case strlen($this->dni) == 8:
                $cliente = ApiController::Reniec($this->dni);
                if(!$cliente ){
                    session()->flash('error', 'No Se ha encontrado un cliente en la base de datos de Reniec.');
                }
                $this->cliente = $this->buscarCliente($this->dni, $cliente['nombre']);
                    break;
            case strlen($this->dni) == 11:
                $cliente = ApiController::Sunat($this->dni);
                if(!$cliente ){
                    session()->flash('error', 'No Se ha encontrado un cliente en la base de datos de Sunat.');
                }
                $this->cliente = $this->buscarCliente($this->dni, $cliente['nombre']);
                break;
            default:
                session()->flash('error', 'El número de DNI/RUC ingresado no es válido.');
                $this->cliente = $this->buscarCliente(0, 'Cliente Anonimo');
                $this->dni = '';
                break;
        }
    }

    public function buscarCliente($dni, $name)
    {
        $cliente = Client::where('ruc', $dni)->first();

        if ($cliente) {
            // Si se encuentra un cliente con el RUC correspondiente, no es necesario hacer nada más
            session()->flash('success', 'Se ha encontrado un cliente en la base de datos.');
        } else {
            // Si no se encuentra un cliente con el RUC correspondiente, se crea un nuevo registro en la tabla "clients"
            $cliente = new Client();
            $cliente->ruc = $dni;
            $cliente->name = $name;
            $cliente->save();
            session()->flash('success', 'Se ha creado un nuevo cliente en la base de datos.');
        }
        return $cliente;
    }


    public function updatedMontoMedio()
    {
        $this->validate([
            'montoMedio' => 'required|integer|min:1',
        ]);
    }

    public function processInput($tipo, $value)
    {
        if ($value >= 1) {
            $valor = ($this->resto <= $value) ? $this->resto : $value;

            $this->tipo->push([
                'Tipo' => $tipo,
                'Valor' => $valor
            ]);

            $this->efectivo += ($value == 0 ? $this->total : $value);
            $this->change = ($this->efectivo - $this->total);
            $this->resto = $this->total - $this->efectivo;
        }

        $this->montoMedio = 0;
    }
}
