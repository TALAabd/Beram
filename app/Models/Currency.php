<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;

    // protected $table = '';

    protected $fillable = [
        'id',
        'name',
        'code',
        'symbol',
        'format',
        'exchange_rate',
        'active'
    ];

    
}
