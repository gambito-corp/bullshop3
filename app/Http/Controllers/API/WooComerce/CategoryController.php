<?php

namespace App\Http\Controllers\API\WooComerce;

use App\Http\Controllers\Controller;
use App\Interfaces\IWooCommerceService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    private $woocommerceService;

    public function __construct(IWooCommerceService $woocommerceService)
    {
        $this->woocommerceService = $woocommerceService;
    }

    public function index()
    {
        return $this->woocommerceService->getCategories();
    }
}
