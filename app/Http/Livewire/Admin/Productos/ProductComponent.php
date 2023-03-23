<?php

namespace App\Http\Livewire\Admin\Productos;

use App\Http\Controllers\API\ApiController;
use App\Http\Controllers\ProductController;
use App\Models\Product;
use App\Services\WooCommerceService;
use Livewire\Component;
use Livewire\WithPagination;

class ProductComponent extends Component
{
    use WithPagination;
    public $isLoading, $perPage, $search, $sortField, $sortDirection;

    public function mount()
        {
            $this->isLoading = false;
            $this->perPage = 10;
            $this->search = '';
            $this->sortField = 'id';
            $this->sortDirection = 'asc';
        }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
    }

    public function render()
    {
        $products = Product::query()
            ->search($this->search)
            ->sortByField($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.productos.product-component', compact('products'));
    }

    public function getSortArrowClass($field)
    {
        if ($this->sortField === $field) {
            return $this->sortDirection === 'asc' ? 'fas fa-sort-up' : 'fas fa-sort-down';
        }
        return 'fas fa-sort';
    }

    public function syncProducts()
    {
        $this->isLoading = true;
        $woocommerceService = new WooCommerceService();
        $controller = new ProductController($woocommerceService);
        $productos = $controller->getProducts();
        $this->isLoading = false;
    }

    public function openModal()
    {
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
    }

    public function updateProductStock($product)
    {
        $client = ApiController::Woocomerce2();
        $stock = $client->get('products/' . $product->wp_id);
        $product->stock = $stock->stock_quantity;
        $product->update();
    }

    public function addToCart($productId)
    {
        $product = Product::Search($productId)->first();
        if (!$product) {
            session()->flash('error', 'Producto no encontrado.');
            return;
        }
        if ($product->category->name != 'Limpieza') {
            $this->updateProductStock($product);
        } else {
            $product->stock = 1;
        }
        if ($product->stock <= 0) {
            session()->flash('error', 'El producto está agotado, se actualizó el stock a 0');
            return;
        }
        $cart = session()->get('cart', []);
        $cart = session()->get('cart');
        if ($cart && count($cart) > 0) {
            $count = 0;
            foreach ($cart as $id) {
                if ($id == $product->id) {
                    $count++;
                }
            }
            if ($count >= $product->stock) {
                session()->flash('error', 'No se pueden agregar más productos. Se ha alcanzado el límite del stock disponible.');
                return;
            }
        }
        $cart[] = $productId;
        session()->put('cart', $cart);
        session()->flash('message', 'Producto agregado al carrito.');
    }

}
