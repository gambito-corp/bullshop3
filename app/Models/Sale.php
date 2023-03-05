<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Sale extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable =[
        'total',
        'discount',
        'sale_price',
        'total_cost',
        'items',
        'cash',
        'change',
        'status',
        'payment_method'
    ];
}
