<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class hasil_tpk extends Model
{
    use HasFactory;

    protected $table = 'hasil_tpks'; 

    protected $fillable = [
        'user_id',
        'username',
        'score',
    ];
}
