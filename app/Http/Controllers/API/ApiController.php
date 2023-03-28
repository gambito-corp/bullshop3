<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Automattic\WooCommerce\Client;
use GuzzleHttp\Client as Sunat;
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

    public static function Reniec($numero)
    {
        $token = 'apis-token-4144.uroE17axwQiGjCq-Hs3xZYLW7TqSRH9U';
        $client = new Sunat(['base_uri' => 'https://api.apis.net.pe', 'verify' => false]);
        $parameters = [
            'http_errors' => false,
            'connect_timeout' => 5,
            'headers' => [
                'Authorization' => 'Bearer '.$token,
                'Referer' => 'https://apis.net.pe/api-consulta-dni',
                'User-Agent' => 'laravel/guzzle',
                'Accept' => 'application/json',
            ],
            'query' => ['numero' => $numero]
        ];
        $res = $client->request('GET', '/v1/dni', $parameters);
        $response = json_decode($res->getBody()->getContents(), true);
        return $response;
    }

    public static function Sunat($numero)
    {
        $token = 'apis-token-4144.uroE17axwQiGjCq-Hs3xZYLW7TqSRH9U';
        $client = new Sunat(['base_uri' => 'https://api.apis.net.pe', 'verify' => false]);
        $parameters = [
            'http_errors' => false,
            'connect_timeout' => 5,
            'headers' => [
                'Authorization' => 'Bearer '.$token,
                'Referer' => 'https://apis.net.pe/api-consulta-ruc',
                'User-Agent' => 'laravel/guzzle',
                'Accept' => 'application/json',
            ],
            'query' => ['numero' => $numero]
        ];
        $res = $client->request('GET', '/v1/ruc', $parameters);
        $response = json_decode($res->getBody()->getContents(), true);
        return $response;
    }
}
