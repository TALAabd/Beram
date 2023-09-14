<?php

namespace Modules\Booking\Http\Services;

use Illuminate\Support\Facades\DB;
use Modules\Booking\Repositories\HotelRoomsBookingRepository;
use Modules\Hotels\Repositories\HotelRepository;
use Modules\Booking\Repositories\BookingRepository;

class HotelRoomsBookingService
{

    public function __construct(
        private HotelRepository $hotelRepository,
        private BookingRepository $bookingRepository,
        private HotelRoomsBookingRepository $hotelRoomsBookingRepository
    ) {
    }

    public function getAll()
    {
        return $this->hotelRoomsBookingRepository->getAll();
    }

    public function find($hotel_rooms_bookingId)
    {
        return $this->hotelRoomsBookingRepository->find($hotel_rooms_bookingId);
    }

    public function create($validatedData)
    {
        DB::beginTransaction();

        // Find hotel for booking user
        $hotel = $this->hotelRepository->find($validatedData['hotel_id']);

        // Create booking
        $booking = $this->bookingRepository->create($validatedData);

        // Assign booking to hotel
        $hotel->bookings()->save($booking);

        // Create room bookings
        $roomBookings = [];
        foreach ($validatedData['rooms'] as $room) {
            $roomBooking = $this->hotelRoomsBookingRepository->create($room);
            $roomBookings[] = $roomBooking;
        }

        // Assign room bookings to booking user
        $booking->roomBookings()->saveMany($roomBookings);

        DB::commit();

        return $booking->roomBookings;
    }

    public function update($validatedData, $hotel_rooms_bookingId)
    {
        $hotel_rooms_booking = $this->hotelRoomsBookingRepository->find($hotel_rooms_bookingId);

        DB::beginTransaction();

        $this->hotelRoomsBookingRepository->update($validatedData, $hotel_rooms_booking);

        DB::commit();

        return true;
    }

    public function delete($hotel_rooms_bookingId)
    {
        $hotel_rooms_booking = $this->hotelRoomsBookingRepository->find($hotel_rooms_bookingId);

        DB::beginTransaction();

        $this->hotelRoomsBookingRepository->delete($hotel_rooms_booking);

        DB::commit();

        return true;
    }
}
