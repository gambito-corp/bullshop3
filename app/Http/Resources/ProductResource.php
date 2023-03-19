<?php

namespace App\Http\Resources;

use App\Http\Controllers\API\ApiController;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use PHPUnit\Exception;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'category_id'   => $this->getCategoryId(),
            'wp_id'         => $this->id,
            'name'          => $this->name,
            'slug'          => $this->slug,
            'type'          => $this->type,
            'status'        => $this->status,
            'sku'           => $this->sku,
            'price'         => $this->price,
            'cost'          => $this->getCost(),
            'stock'         => $this->stock_quantity,
            'brand'         => $this->getBrand(),
            'size'          => $this->getSize(),
            'image'         => $this->getImage()
        ];
    }

    private function getCategoryId()
    {
        switch ($this->type) {
            case 'variation':
                $client = ApiController::Woocomerce2();
                dd($this->parent_id, $client->get('products/' .$this->parent_id));


                $padre = $client->get('products/' .$this->parent_id);
                $cat = $padre->categories[0]->id;
                $cat = Category::where('wp_id', $cat)->first();
                $category = Category::where('wp_id', $padre->categories[0]->id)->first();
                return $category->id;
                break;
            default:
                $category = Category::where('wp_id', $this->categories[0]->id)->first();
                return $category->id;
                break;
        }
    }

    private function getCost()
    {

        switch ($this->type) {
            case 'variation':
                $costo = array_filter($this->meta_data, function ($meta_data){
                    return $meta_data->key === 'purchase_product_variable';
                });
                if (!empty($costo)) {
                    $costo = reset($costo);
                    return $costo->value;
                }
                break;
            default:
                $costo = array_filter($this->meta_data, function ($meta_data){
                    return $meta_data->key === 'purchase_product_simple';
                });
                if (!empty($costo)) {
                    $costo = reset($costo);
                    return $costo->value;
                }
                break;
        }
        return null;
    }

    private function getBrand()
    {
        switch ($this->type){
            case 'variation' :
                $client = ApiController::Woocomerce2();
                $padre = $client->get('products/'.$this->parent_id);
                $Marca = array_filter($padre->attributes, function ($attribute) {
                    return $attribute->name == "Marca";
                });

                if (!empty($Marca)) {
                    $marca = reset($Marca);
                    return $marca->options[0];
                }
                return 'S/M';
                break;
            default:
                $Marca = array_filter($this->attributes, function ($attribute) {
                    return $attribute->name == "Marca";
                });

                if (!empty($Marca)) {
                    $marca = reset($Marca);
                    return $marca->option;
                }
                return 'S/M';
                break;
        }
    }

    private function getSize()
    {
        $tallaAttribute = array_filter($this->attributes, function ($attribute) {
            return $attribute->name === "Talla";
        });

        if (!empty($tallaAttribute)) {
            $tallaObject = reset($tallaAttribute);
            return $tallaObject->option;
        }

        return 'S/T';
    }

    private function getImage()
    {
        if (isset($this->images[0]->src)) {
            return $this->images[0]->src;
        }

        return '';
    }
}
