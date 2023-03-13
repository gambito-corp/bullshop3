<?php

namespace App\Services;

use App\Http\Controllers\API\ApiController;
use App\Interfaces\IWooCommerceService;

class WooCommerceService implements IWooCommerceService
{
//    private HelpersController $client;
    private ApiController $client;

    public function __construct()
    {
        $this->client = new ApiController();
    }

    public function getAllCustomers()
    {
        return $this->client->Woocomerce()->get('customers');
    }

    public function getCategories($id = null)
    {
        if($id !== null){
            $return = $this->client->Woocomerce()->get('products/categories/'.$id);
        }else{
            $params = ['per_page' => 100, 'page' => 1];
            $return = $this->client->Woocomerce()->get('products/categories', $params);
        }
        return $return;
    }

    public function createCategory($params)
    {
        return $this->client->Woocomerce()->post('products/categories/', $params);
    }

    public function updateCategory($params, $id)
    {
        return $this->client->Woocomerce()->put('products/categories/'.$id, $params);
    }

    public function deleteCategory($id)
    {
        return $this->client->Woocomerce()->delete('products/categories/'.$id, ['force' => true]);
    }
}
