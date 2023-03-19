<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\ApiController;
use App\Interfaces\IWooCommerceService;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    private $woocommerceService;

    public function __construct(IWooCommerceService $woocommerceService)
    {
        $this->woocommerceService = $woocommerceService;
    }

    public function index()
    {
        return view("admin.productos.index");
    }

    protected function SyncCategories()
    {
        $wooCommerce = $this->woocommerceService->getProducts();
        $productos = Product::all();

        foreach ($wooCommerce as $product) {
            $exists = $productos->first(function ($item) use ($product) {
                return $item->name === $product->name;
            });

            if (!$exists) {
                $item = new Product([
                    'wp_id'         => $product->id,
                    'name'          => $product->name,
                    'slug'          => $product->slug,
                    'description'   => $product->name,
                    'display'       => $product->display,
                    'image'         => $product->image,
                ]);
                $item->save();
                $productos->push($item);
            }
        }
    }

    public function getProducts2()
    {
        $client = ApiController::Woocomerce2();

        $all_products = collect();
        for ($i = 1; $i <= 33; $i++) {
            $params = [
                'per_page' => 100,
                'page' => $i,
                'total' => true
            ];
            $response = $client->get('products/', $params);
            if (empty($response)) {
                break;
            }
            foreach ($response as $res) {
                $all_products->push($res);
            }
        }

        $all_products->each(function ($product, $key) use ($client) {
            switch ($product->type) {
                case 'variable':
                    $variations = collect($client->get("products/$product->id/variations"));
                    foreach ($variations as $variation) {
                        $resource = [
                            'category_id' => $this->getCategory($product),
                            'wp_id' => $variation->id,
                            'name' => $product->name,
                            'slug' => $variation->permalink,
                            'type' => $product->type,
                            'status' => $variation->status,
                            'sku' => $variation->sku,
                            'price' => $variation->price,
                            'cost' => $this->getCost($variation, $product),
                            'stock' => $variation->stock_quantity,
                            'brand' => $this->getBrand($product),
                            'size' => $this->getSize($variation),
                            'image' => $this->getImage($variation)
                        ];
                    }
                    break;
                default:
                    $resource = [
                        'category_id' => $this->getCategory($product),
                        'wp_id' => $product->id,
                        'name' => $product->name,
                        'slug' => $product->permalink,
                        'type' => $product->type,
                        'status' => $product->status,
                        'sku' => $product->sku,
                        'price' => $product->price,
                        'cost' => $this->getCostSimple($product),
                        'stock' => $product->stock_quantity,
                        'brand' => $this->getBrand($product),
                        'size' => $this->getSize($product),
                        'image' => $this->getImage($product)
                    ];
                    break;
            }

            try {
                // Actualiza o crea un nuevo producto en la base de datos
                Product::updateOrCreate(
                    ['wp_id' => $resource['wp_id']],
                    $resource
                );
            } catch (\Illuminate\Database\QueryException $e) {
                // Manejar la excepción aquí
                echo "Error al insertar o actualizar el producto: " . $e->getMessage();
            }
        });
    }


    public function getProducts()
    {
        $client = ApiController::Woocomerce2();
        // Configuramos los parámetros de la petición para obtener todos los resultados en una sola llamada.

        $all_products = collect();
        for ($i = 1; $i<=33 ; $i++){
            $params = [
                'per_page' => 100,
                'page' => $i,
                'total' => true
            ];
            $response = $client->get('products/', $params);
            if(empty($response)){break;}
            foreach ($response as $res){
                $all_products->push($res);
            }
        }
        $all_products->each(function ($product, $key) use($client) {
            switch ($product->type){
                case 'variable':
                    $variations = collect($client->get("products/$product->id/variations"));
                    foreach ($variations as $variation){
                        $resource = [
//                            'category_id'   => 1,
                            'category_id'   => $this->getCategory($product),
                            'wp_id'         => $variation->id,
                            'name'          => $product->name,
                            'slug'          => $variation->permalink,
                            'type'          => $product->type,
                            'status'        => $variation->status,
                            'sku'           => $variation->sku,
                            'price'         => $variation->price,
                            'cost'          => $this->getCost($variation, $product),
                            'stock'         => $variation->stock_quantity,
                            'brand'         => $this->getBrand($product),
                            'size'          => $this->getSize($variation),
                            'image'         => $this->getImage($variation)
                        ];
                        try {
                            // Actualiza o crea un nuevo producto en la base de datos
                            $item = Product::where('wp_id', $resource['wp_id'])->first();
                            if($item == null){
                                $item = new Product();
                                $item->category_id = $resource['category_id'];
                                $item->wp_id = $resource['wp_id'];
                                $item->name = $resource['name'];
                                $item->slug = $resource['slug'];
                                $item->type = $resource['type'];
                                $item->status = $resource['status'];
                                $item->sku = $resource['sku'];
                                $item->price = $resource['price'];
                                $item->cost = $resource['cost'];
                                $item->stock = $resource['stock'];
                                $item->brand = $resource['brand'];
                                $item->size = $resource['size'];
                                $item->image = $resource['image'];
                                $item->save();
                            }else{
                                $item->category_id = $resource['category_id'];
                                $item->wp_id = $resource['wp_id'];
                                $item->name = $resource['name'];
                                $item->slug = $resource['slug'];
                                $item->type = $resource['type'];
                                $item->status = $resource['status'];
                                $item->sku = $resource['sku'];
                                $item->price = $resource['price'];
                                $item->cost = $resource['cost'];
                                $item->stock = $resource['stock'];
                                $item->brand = $resource['brand'];
                                $item->size = $resource['size'];
                                $item->image = $resource['image'];
                                $item->update();
                            }
                        } catch (\Illuminate\Database\QueryException $e) {
                            dd($product, $variation, $e);
                            // Manejar la excepción aquí
                            echo "Error al insertar o actualizar el producto: " . $e->getMessage();
                        }
                    }
                    break;
                default:
                    $resource = [
                        'category_id'   => $this->getCategory($product),
                        'wp_id'         => $product->id,
                        'name'          => $product->name,
                        'slug'          => $product->permalink,
                        'type'          => $product->type,
                        'status'        => $product->status,
                        'sku'           => $product->sku,
                        'price'         => $product->price,
                        'cost'          => $this->getCostSimple($product),
                        'stock'         => $product->stock_quantity,
                        'brand'         => $this->getBrand($product),
                        'size'          => $this->getSize($product),
                        'image'         => $this->getImage($product)
                    ];
                    try {
                        // Actualiza o crea un nuevo producto en la base de datos
                        $item = Product::where('wp_id', $resource['wp_id'])->first();
                        if($item == null){
                            $item = new Product();
                            $item->category_id = $resource['category_id'];
                            $item->wp_id = $resource['wp_id'];
                            $item->name = $resource['name'];
                            $item->slug = $resource['slug'];
                            $item->type = $resource['type'];
                            $item->status = $resource['status'];
                            $item->sku = $resource['sku'];
                            $item->price = $resource['price'];
                            $item->cost = $resource['cost'];
                            $item->stock = $resource['stock'];
                            $item->brand = $resource['brand'];
                            $item->size = $resource['size'];
                            $item->image = $resource['image'];
                            $item->save();
                        }else{
                            $item->category_id = $resource['category_id'];
                            $item->wp_id = $resource['wp_id'];
                            $item->name = $resource['name'];
                            $item->slug = $resource['slug'];
                            $item->type = $resource['type'];
                            $item->status = $resource['status'];
                            $item->sku = $resource['sku'];
                            $item->price = $resource['price'];
                            $item->cost = $resource['cost'];
                            $item->stock = $resource['stock'];
                            $item->brand = $resource['brand'];
                            $item->size = $resource['size'];
                            $item->image = $resource['image'];
                            $item->update();
                        }
                    } catch (\Illuminate\Database\QueryException $e) {
                        dd($product, $e);
                        // Manejar la excepción aquí
                        echo "Error al insertar o actualizar el producto: " . $e->getMessage();
                    };
                    break;
            }
        });
    }

    protected function getCategory($product)
    {
        $categoria = Category::where('wp_id', $product->categories[0]->id)->first();
        return $categoria->id;
    }

    protected function getCost($product, $padre)
    {
        $metadata = collect($product->meta_data);
        $costo = $metadata->where('key', 'purchase_product_variable');

        if($costo->first() == null){
            $costo = 0;
        }else{
            $costo = $costo->first()->value;
        }
        return $costo;
    }

    protected function getCostSimple($product){
        $metadata = collect($product->meta_data);
        $costo = $metadata->where('key', 'purchase_product_simple');
        if($costo->first() == null){
            $costo = 0;
        }else{
            $costo = $costo->first()->value;
        }
        return $costo;
//        dd($product);
    }

    protected function getBrand($producto)
    {
        $atributos = collect($producto->attributes);
        $marca = $atributos->where('name', 'Marca')->first();
        if($marca == null){
            $marca = 'S/M';
        }else{
            $marca = $marca->options[0];
        }
        return $marca;
    }

    protected function getSize($producto)
    {
        $atributos = collect($producto->attributes);
        $talla = $atributos->where('name', 'Talla')->first();
        if($talla == null){
            $talla = 'S/T';
        }else{
            if(isset($talla->option)){
                $talla = $talla->option;
            }else{
                $talla = 'S/T';
            }
        }
        return $talla;
    }

    protected function getImage($producto)
    {
        if((isset($producto->image->src) && $producto->image->src != null)){
            $img = $producto->image->src;
        }elseif(isset($producto->images[0]->src) && $producto->images[0]->src != null){
            $img = $producto->images[0]->src;
        }else{
            $img = 'no image';
        }

        return $img;
    }

    public function sync()
    {
        $this->SyncCategories();
    }

}
