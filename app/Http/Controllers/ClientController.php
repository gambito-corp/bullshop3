<?php

namespace App\Http\Controllers;

use App\Interfaces\IWooCommerceService;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    private $woocommerceService;

    public function __construct(IWooCommerceService $woocommerceService)
    {
        $this->woocommerceService = $woocommerceService;
    }
    public function index()
    {
        $Clients = $this->woocommerceService->getAllCustomers();
        dd($Clients);
        return view("admin.clientes.index");
    }
}
