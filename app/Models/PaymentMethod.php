<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Booking\Models\Booking;

class PaymentMethod extends Model
{
    use HasFactory;
    protected $table = 'payment_methods';
    const PATH = 'payment_methods';


    protected $fillable = [
        'id',
        'name',
        'status'
    ];
    public function book()
    {
        return $this->hasMany(Booking::class);
    }
}
