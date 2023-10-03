<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Authentication\Models\User;

class Wallet extends Model
{
    use HasFactory;
    protected $table = 'wallets';
 
    protected $fillable = [
        'id',
        'provider_id',
        'amount',
        'status'
    ];

    public function provider()
    {
        return $this->belongsTo(User::class,'provider_id');
    }
}
