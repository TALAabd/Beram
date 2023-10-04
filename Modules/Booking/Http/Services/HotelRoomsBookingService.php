<?php

namespace Modules\Booking\Http\Services;

use App\Models\Wallet;
use App\Mail\AdminBookingMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Modules\Authentication\Models\User;
use Modules\Booking\Repositories\HotelRoomsBookingRepository;
use Modules\Hotels\Repositories\HotelRepository;
use Modules\Booking\Repositories\BookingRepository;
use Modules\Hotels\Models\Room;
use App\Traits\ApiResponser;

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
        $customer = Auth::guard('customer')->user();

        // Find hotel for booking user
        $hotel = $this->hotelRepository->find($validatedData['hotel_id']);
        $room  = Room::where('id', $validatedData['room_id'])->first();
        // Create booking
        // $validatedData['total_price'] = $customer->nationality == 'Syrian' ? $room->syrian_price * $validatedData['rooms_count'] : $room->foreign_price * $validatedData['rooms_count'];
        $validatedData['total_price'] = $room->syrian_price * $validatedData['rooms_count'];
        $validatedData['total_guests']  = $validatedData['max_guests'];
        $booking = $this->bookingRepository->create($validatedData);

        // Assign booking to hotel
        $hotel->bookings()->save($booking);

        // Create room bookings
        $roomBookings = [];
        $roomData = [
            'room_id'     => $validatedData['room_id'],
            'start_date'  => $validatedData['check_in_date'],
            'end_date'    => $validatedData['check_out_date'],
            'max_guests'  => $validatedData['max_guests'],
            'rooms_count' => $validatedData['rooms_count'],
        ];
        // foreach ($validatedData['rooms'] as $room) {
        $roomBooking = $this->hotelRoomsBookingRepository->create($roomData);
        $roomBookings[] = $roomBooking;
        // }

        // Assign room bookings to booking user
        $booking->roomBookings()->saveMany($roomBookings);

        $admins = User::where('role', 'administrator')->get();
        foreach ($admins as $admin) {
            if ($admin->email) {
                Mail::to($admin->email)->send(new AdminBookingMail($booking, $hotel->getTranslation('name', 'en')));
            }
        }

        DB::commit();

        return $booking->booking_code;
    }
    public function HotelGuestBooking($validatedData)
    {
        DB::beginTransaction();
        // Find hotel for booking user
        $hotel = $this->hotelRepository->find($validatedData['hotel_id']);
        $room  = Room::where('id', $validatedData['room_id'])->first();
        // Create booking
        $validatedData['total_price'] = $room->syrian_price * $validatedData['rooms_count'];
        $validatedData['total_guests']  = $validatedData['max_guests'];

        //booking by provider 
        if (Auth::guard('user')->user()->role == "provider"||Auth::guard('user')->user()->role == "Hotel_provider") {

            $validatedData['provider_id'] = Auth::guard('user')->user()->id;

            $wallet = Wallet::where('provider_id', Auth::guard('user')->user()->id)->first();
            //check peovider wallet
            if ($wallet->amount >= $room->syrian_price) {

                $new_wallet     = $wallet->amount -  $room->syrian_price;
                $wallet->amount = $new_wallet;
                $wallet->save();

                if ($new_wallet <= 0) {
                    $wallet->status = 0;
                    $wallet->save();
                }
            } else {
                return ApiResponser::errorResponse(
                    null,
                    'wallet dose not have enough money'
                );
            }
        }
        $booking = $this->bookingRepository->HotelGuestBooking($validatedData);

        // Assign booking to hotel
        $hotel->bookings()->save($booking);

        // Create room bookings
        $roomBookings = [];
        $roomData = [
            'room_id'     => $validatedData['room_id'],
            'start_date'  => $validatedData['check_in_date'],
            'end_date'    => $validatedData['check_out_date'],
            'max_guests'  => $validatedData['max_guests'],
            'rooms_count' => $validatedData['rooms_count'],
        ];
        // foreach ($validatedData['rooms'] as $room) {
        $roomBooking = $this->hotelRoomsBookingRepository->create($roomData);
        $roomBookings[] = $roomBooking;
        // }

        // Assign room bookings to booking user
        $booking->roomBookings()->saveMany($roomBookings);

        $admins = User::where('role', 'administrator')->get();
        foreach ($admins as $admin) {
            if ($admin->email) {
                Mail::to($admin->email)->send(new AdminBookingMail($booking, $hotel->getTranslation('name', 'en')));
            }
        }

        DB::commit();

        return ApiResponser::successResponse(
            $booking->booking_code,
            'bookingSuccessfully'

        );
        // return $booking->booking_code;
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
