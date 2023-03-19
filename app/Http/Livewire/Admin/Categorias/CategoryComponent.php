<?php

namespace App\Http\Livewire\Admin\Categorias;

use Livewire\Component;
use App\Models\Category;
use Livewire\WithPagination;
use App\Services\WooCommerceService;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;



class CategoryComponent extends Component
{
    use WithPagination;

    public $name, $category_id, $categoryIdToDelete, $wp_id, $slug, $searchTerm, $confirmingForceDelete, $password, $showDeleted, $sortColumn, $sortDirection, $perPage, $isModalOpen, $isDeleteModalOpen;

    public function mount()
    {
        $this->searchTerm = ''; $this->confirmingForceDelete = false; $this->password = ''; $this->showDeleted = false; $this->sortColumn = 'name'; $this->sortDirection = 'asc'; $this->perPage = 10; $this->isModalOpen = false; $this->isDeleteModalOpen = false;
    }

    public function render()
    {
        return view('livewire.admin.categorias.category-component', [
            'categories' => $this->fetchCategories(),
        ]);
    }

    public function create()
    {
        $this->resetInputFields();
        $this->isModalOpen = true;
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $this->category_id = $id;
        $this->name = $category->name;
        $this->isModalOpen = true; // Abrir el modal
    }

    public function softDelete($id)
    {
        Category::find($id)->delete();
        session()->flash('message', 'Categoría borrada correctamente.');
    }

    public function restoreCategory($categoryId)
    {
        $category = Category::onlyTrashed()->findOrFail($categoryId);
        $category->restore();
    }

    public function forceDeleteCategory()
    {
        if (Hash::check($this->password, auth()->user()->password)) {
            $item = Category::onlyTrashed()->find($this->categoryIdToDelete);
            $woocommerceService = new WooCommerceService();
            $controller = new CategoryController($woocommerceService);
            $respuesta = $controller->delete($item->wp_id);
            $item->forceDelete();
            session()->flash('message', 'Categoría eliminada permanentemente.');
            $this->closeModal();
        } else {
            session()->flash('error_message', 'Contraseña incorrecta.');
        }
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|string|unique:categories,name' . ($this->category_id ? ',' . $this->category_id : ''),
        ]);

        try {
            $woocommerceService = new WooCommerceService();
            $controller = new CategoryController($woocommerceService);

            // Datos de la categoría
            $categoryData = [
                'name' => $this->name,
            ];

            if ($this->category_id) {
                $id = Category::where('id', $this->category_id)->first();
                $item = $controller->Update($categoryData, $id->wp_id);
            } else {
                // Crear categoría en WooCommerce
                $item = $controller->Create($categoryData);
            }

            // Crear o actualizar la categoría en la base de datos local
            Category::updateOrCreate(['id' => $this->category_id], [
                'wp_id' => $item->id,
                'name' => $item->name,
                'slug' => $item->slug,
                'description' => '0',
                'display' => '0',
                'image' => '0',
            ]);

            session()->flash('message', $this->category_id ? 'Categoría actualizada correctamente.' : 'Categoría creada correctamente.');

        } catch (\Exception $e) {
            // Mostrar mensaje de error
            session()->flash('error_message', 'Ocurrió un error durante el proceso: ' . $e->getMessage());
        }

        $this->closeModal();
        $this->resetInputFields();
    }

    //Bloque de Filtros 1/4
    private function fetchCategories()
    {
        return Category::query()
            ->when($this->showDeleted, function ($query) {
                return $query->onlyTrashed();
            })
            ->when(!$this->showDeleted, function ($query) {
                return $query->whereNull('deleted_at');
            })
            ->when($this->searchTerm, function ($query) {
                return $query->where(function ($query) {
                    $query->where('name', 'like', '%' . $this->searchTerm . '%')
                        ->orWhere('id', 'like', '%' . $this->searchTerm . '%')
                        ->orWhere('wp_id', 'like', '%' . $this->searchTerm . '%');
                });
            })
            ->orderBy($this->sortColumn, $this->sortDirection)
            ->paginate($this->perPage);
    }

    //Bloque de Filtros 2/4
    public function sortBy($column)
    {
        $this->sortColumn = $column;
        $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
    }
    //Bloque de Filtros 3/4
    public function getSortArrowClass($column)
    {
        if ($this->sortColumn !== $column) {
            return 'text-gray-400';
        }

        return $this->sortDirection === 'asc'
            ? 'text-blue-500 fas fa-sort-up'
            : 'text-blue-500 fas fa-sort-down';
    }

    //bloque de filtros 4/4
    public function toggleShowDeleted()
    {
        $this->showDeleted = !$this->showDeleted;
    }
    //Bloque para modal 1/3
    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->isDeleteModalOpen = false;
    }
    //Bloque para modal 2/3
    public function openModal()
    {
        $this->dispatchBrowserEvent('openModal');
    }
    //Bloque para modal 3/4
    public function confirmDelete($id)
    {
        $this->categoryIdToDelete = $id;
        $this->isDeleteModalOpen = true;
    }

    //Bloque para modal 4/4
    private function resetInputFields()
    {
        $this->name = '';
        $this->category_id = '';
    }
    //sincronizacion de categorias
    public function syncCategories()
    {
        $woocommerceService = new WooCommerceService();

        $controller = new CategoryController($woocommerceService);
        $controller->sync();
    }
}
