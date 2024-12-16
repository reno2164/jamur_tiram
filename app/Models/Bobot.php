<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bobot extends Model
{
    use HasFactory;

    protected $fillable = [
        'quantity',
        'price',
        'transactions',
    ];
}
