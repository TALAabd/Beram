<?php

namespace Modules\Booking\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Hotels\Models\Room;

class HotelRoomsBooking extends Model
{
    use HasFactory;

    protected $table         = 'hotel_rooms_bookings';

    protected $fillable = [
        'id',
        'start_date',
        'end_date',
        'price',
        'max_guests',
        'active',
        'note_to_customer',
        'note_to_admin',
        'number',
        'room_id',
        'booking_id',
        'rooms_count',
    ];

    protected $casts = [
        'price' => 'float',
        'max_guests' => 'integer',
        'number' => 'integer',
        'booking_id' => 'integer',
        'number' => 'integer',
        'zip_code' => 'integer',
        'rooms_count' => 'integer',
        'room_id' => 'integer',
    ];

    protected $hidden = [
        'deleted_at'
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

}
