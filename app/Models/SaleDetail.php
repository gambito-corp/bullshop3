<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class SaleDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'price',
        'discount',
        'cost',
        'quantity'
    ];
}
