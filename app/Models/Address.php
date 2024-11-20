<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'phone_number',
        'address',
        'is_default',
    ];

    /**
     * Relasi ke User (Many-to-One).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
