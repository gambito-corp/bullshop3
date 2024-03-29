<?php

namespace App\Http\Controllers;

use App\Interfaces\IWooCommerceService;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    private $woocommerceService;

    public function __construct(IWooCommerceService $woocommerceService)
    {
        $this->woocommerceService = $woocommerceService;
    }

    public function ejemplo()
    {
        $Clientes = Client::all();
        $Clients = $this->woocommerceService->getAllCustomers();

    }

    public function index()
    {
        return view("admin.clientes.index");
    }
}
