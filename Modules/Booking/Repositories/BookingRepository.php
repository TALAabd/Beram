<?php

namespace Modules\Booking\Repositories;

use Modules\Booking\Models\Booking;
use App\Traits\ModelHelper;
use App\Helper\bookingHelper;
use Illuminate\Support\Facades\Auth;
use Modules\Authentication\Models\Customer;

class BookingRepository
{
    use ModelHelper;

    public function getAll($status)
    {
        $bookings = Booking::bookings()->where('status', $status)->get();
        return $bookings;
    }

    public function getAllByUser($status)
    {
        $customer = Auth::guard('customer')->user();
        $customer = Customer::where('email', $customer->email)->orWhere('phone', $customer->phone)->first();
        return $customer->bookings()->where('status', $status)->get();
    }

    public function find($bookingId)
    {
        return $this->findByIdOrFail(Booking::class, 'booking', $bookingId);
    }

    public function create($validatedData)
    {
        $validatedData['booking_code'] = bookingHelper::generateBookingCode();
        $validatedData['service_type'] = "hotel";
        $booking = new Booking($validatedData);
        $booking->customer_id = Auth::guard('customer')->user()->id;
        $booking->save();
        return $booking;
    }

    public function update($validatedData, Booking $booking)
    {
        return $booking->update($validatedData);
    }

    public function delete(Booking $booking)
    {
        return $booking->delete();
    }
}
