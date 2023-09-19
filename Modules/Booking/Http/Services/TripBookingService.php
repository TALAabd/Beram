<?php

namespace Modules\Booking\Http\Services;

use App\Models\Trip;
use Illuminate\Support\Facades\DB;
use Modules\Booking\Repositories\HotelRoomsBookingRepository;
use Modules\Hotels\Repositories\HotelRepository;
use Modules\Booking\Repositories\BookingRepository;

class TripBookingService
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

        $trip = Trip::find($validatedData['trip_id']);

        // Create booking
        $booking = $this->bookingRepository->tripCreate($validatedData);

        // Assign booking to hotel
        $trip->bookings()->save($booking);

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
