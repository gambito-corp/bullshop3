<?php

namespace App\Http\Controllers;

use App\Interfaces\IWooCommerceService;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;


class CategoryController extends Controller
{
    private $woocommerceService;

    public function __construct(IWooCommerceService $woocommerceService)
    {
        $this->woocommerceService = $woocommerceService;
    }
    protected function SyncCategories()
    {
        $wooCommerce = $this->woocommerceService->getCategories();
        $categorias = Category::all();

        foreach ($wooCommerce as $category) {
            $exists = $categorias->first(function ($cat) use ($category) {
                return $cat->name === $category->name;
            });

            if (!$exists) {
                $cat = new Category([
                    'wp_id'         => $category->id,
                    'name'          => $category->name,
                    'slug'          => $category->slug,
                    'description'   => $category->name,
                    'display'       => $category->display,
                    'image'         => $category->image,
                ]);
                $cat->save();
                $categorias->push($cat);
            }
        }
    }

    public function index()
    {
        return view("admin.categorias.index");
    }

    protected function createCategory(array $params)
    {
        return $this->woocommerceService->createCategory($params);
    }

    public function Create(array $params)
    {
        return $this->createCategory($params);
    }

    protected function updateCategory(array $params, $id)
    {
        return $this->woocommerceService->updateCategory($params, $id);
    }

    public function update(array $params, $id)
    {
        return $this->updateCategory($params, $id);
    }

    protected function deleteCategory($id)
    {
        return $this->woocommerceService->deleteCategory($id);
    }

    public function Delete($id)
    {
        return $this->deleteCategory($id);
    }

    public function sync()
    {
        $this->SyncCategories();
    }

    public function test()
    {
        return view("test");
    }


}
