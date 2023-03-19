<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use mysql_xdevapi\Collection;

class Client extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'phone',
        'ruc',
        'email',
        'address_1',
        'address_2',
        'city',
        'postcode',
        'where'
    ];
    public function SyncAPICustomers($customer)
    {
        Client::create([
            'name' => $customer->name,
            'phone' => $customer->phone,
            'email' => $customer->email,
            'address_1' => $customer->address_1,
            'address_2' => $customer->address_2,
            'city' => $customer->city,
            'postcode' => $customer->postcode,
            'where' => 'ONLINE'
        ]);
    }

}
