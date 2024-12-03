<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tpk extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'quantity',
        'price',
        'transactions',
    ];

    /**
     * Relasi ke User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}