<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //
    protected $fillable = [
        'name',
        'price',
        'stock',
        'image',
    ];

    // Relasi ke Pembelian (Satu produk bisa memiliki banyak pembelian)
    public function pembelians()
    {
        return $this->hasMany(Sales::class, 'product_id', 'id');
    }
}
