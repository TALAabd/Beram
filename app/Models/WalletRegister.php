<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Authentication\Models\User;

class WalletRegister extends Model
{
    use HasFactory;
    protected $table = 'wallet_registers';
 
    protected $fillable = [
        'id',
        'provider_id',
        'amount',
    ];
    public function provider()
    {
        return $this->belongsTo(User::class,'provider_id');
    }
}
