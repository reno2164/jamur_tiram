<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'transaction_code',
        'total_price',
        'payment_method',
        'status',
        'address_id',
    ];

    /**
     * Relasi ke User (Many-to-One).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Detail Transaksi (One-to-Many).
     */
    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }
}
