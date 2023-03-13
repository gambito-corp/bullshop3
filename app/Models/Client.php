<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
    public function SyncAPICustommers(){

    }
}
