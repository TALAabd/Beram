<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Booking\Models\Booking;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class PaymentMethod extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
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
