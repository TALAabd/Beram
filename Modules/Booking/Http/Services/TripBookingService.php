<?php

namespace Modules\Booking\Http\Services;

use App\Mail\AdminBookingMail;
use App\Models\Trip;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Modules\Authentication\Models\User;
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
        $validatedData['total_price']   = $trip->price * $validatedData['total_guests'];
        $validatedData['check_in_date'] = $trip->date;
        // Create booking
        $booking = $this->bookingRepository->tripCreate($validatedData);

        // Assign booking to hotel
        $trip->bookings()->save($booking);

        $admins = User::where('role', 'administrator')->get();
        foreach ($admins as $admin) {
            if ($admin->email) {
                Mail::to($admin->email)->send(new AdminBookingMail($booking, $trip->getTranslation('name', 'en')));
            }
        }

        DB::commit();

        return $booking->booking_code;
    }
    public function createGuestBooking($validatedData)
    {
        DB::beginTransaction();

        $trip = Trip::find($validatedData['trip_id']);
        $validatedData['total_price']   = $trip->price * $validatedData['total_guests'];
        $validatedData['check_in_date'] = $trip->date;
        // Create booking
        $booking = $this->bookingRepository->createGuestBooking($validatedData);

        // Assign booking to hotel
        $trip->bookings()->save($booking);

        $admins = User::where('role', 'administrator')->get();
        foreach ($admins as $admin) {
            if ($admin->email) {
                Mail::to($admin->email)->send(new AdminBookingMail($booking, $trip->getTranslation('name', 'en')));
            }
        }

        DB::commit();

        return $booking->booking_code;
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
