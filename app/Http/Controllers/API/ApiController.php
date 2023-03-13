<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Automattic\WooCommerce\Client;
class ApiController extends Controller
{
    public function Woocomerce()
    {
        $woocomerce = new Client(
            env('API_URL'),
            env('API_PUBLIC_KEY'),
            env('API_PRIVATE_KEY'),
            [
                'version' => 'wc/v3',
                'verify_ssl' => false
            ]
        );
        return $woocomerce;
    }

    public static function Woocomerce2()
    {
        $woocomerce = new Client(
            env('API_URL'),
            env('API_PUBLIC_KEY'),
            env('API_PRIVATE_KEY'),
            [
                'version' => 'wc/v3',
                'verify_ssl' => false
            ]
        );
        return $woocomerce;
    }
}
