<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'wp_id',
        'name',
        'slug',
        'type',
        'status',
        'sku',
        'price',
        'cost',
        'stock',
        'brand',
        'size',
        'image'
    ];

    public function scopeSearch($query, $search)
    {
        if (trim($search)) {
            $query->where('sku', 'like', "%{$search}%")
                ->orWhere('name', 'like', "%{$search}%")
                ->orWhere('brand', 'like', "%{$search}%")
                ->orWhere('wp_id', 'like', "%{$search}%")
                ->orWhere('id', 'like', "%{$search}%");
        }
    }

    public function scopeSortByField($query, $sortField, $sortDirection)
    {
        $query->orderBy($sortField, $sortDirection);
    }

    public function Category()
    {
        return $this->belongsTo(Category::class);
    }

}
