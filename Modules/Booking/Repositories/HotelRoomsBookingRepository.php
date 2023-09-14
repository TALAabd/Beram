<?php

namespace Modules\Booking\Repositories;

use Modules\Booking\Models\HotelRoomsBooking;
use App\Traits\ModelHelper;

class HotelRoomsBookingRepository
{
    use ModelHelper;

    public function getAll()
    {
      
    }

    public function find($hotel_rooms_bookingId)
    {
        return $this->findByIdOrFail(HotelRoomsBooking::class, 'hotel_rooms_booking', $hotel_rooms_bookingId);
    }

    public function create($validatedData)
    {
        $room_booking = new HotelRoomsBooking($validatedData);
        $room_booking->save();
        return $room_booking;
    }

    public function update($validatedData, HotelRoomsBooking $hotel_rooms_booking)
    {
        return $hotel_rooms_booking->update($validatedData);
    }

    public function delete(HotelRoomsBooking $hotel_rooms_booking)
    {
        return $hotel_rooms_booking->delete();
    }
}
