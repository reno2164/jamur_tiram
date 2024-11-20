<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'image', 
        'title', 
        'description', 
        'price',
        'qty_out', 
        'stok'
    ];
    public function carts()
    {
        return $this->hasMany(Cart::class, 'product_id');
    }
}


