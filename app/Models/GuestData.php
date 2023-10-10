<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuestData extends Model
{
    use HasFactory;

    protected $table = 'guest_data';

    protected $fillable = [
        'id',
        'first_name',
        'last_name',
        'age',
        'booking_id'
    ];
    public function booking()
    {
        return $this->belongsTo(GuestData::class, 'booking_id');
    }
}
