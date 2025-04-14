<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    protected $table = "purchases";

    protected $fillable =[
        'invoice_number',
        'name',
        'product_id',
        'member_id',
        'product_data',
        'quantity',
        'subtotal',
        'diskon_member',
        'points',
        'total_paid',
        'made_by',
    ];

    public function produk()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id', 'id');
    }
}
