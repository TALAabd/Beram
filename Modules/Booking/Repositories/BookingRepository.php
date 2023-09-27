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

    public function getAll($type = 'hotel', $status)
    {
        $bookings = Booking::bookings()->where('service_type', $type)->where('status', $status)->orderBy('id','Desc')->get();
        return $bookings;
    }

    public function getAllByUserStatus($status)
    {
        $customer = Auth::guard('customer')->user();
        $customer = Customer::where('email', $customer->email)->orWhere('phone', $customer->phone)->first();
        return $customer->bookings()->where('service_type', 'hotel')->where('status', $status)->get();
    }

    public function getAllByCustomer($type, $status)
    {
        $customer = Auth::guard('customer')->user();
        $customer = Customer::where('email', $customer->email)->orWhere('phone', $customer->phone)->first();
        $bookings =  $customer->bookings()->where('service_type', $type)->where('status', $status)->orderBy('id', 'desc')->get();
        // $bookings['Cancelled'] =  $customer->bookings()->where('service_type', $type)->where('status', 'Cancelled')->get();
        // $bookings['Confirmed'] =  $customer->bookings()->where('service_type', $type)->where('status', 'Confirmed')->get();

        return $bookings;
    }


    public function search($request)
    {
        $bookings = Booking::where('booking_code', $request['booking_code'])
            ->where('last_name', $request['last_name'])->where('first_name', $request['first_name'])->get();
        return  $bookings;
    }

    public function find($bookingId)
    {
        return $this->findByIdOrFail(Booking::class, 'booking', $bookingId);
    }

    public function create($validatedData)
    {
        $customer = Auth::guard('customer')->user();
        $validatedData['booking_code'] = bookingHelper::generateBookingCode();
        $validatedData['service_type'] = "hotel";
        $validatedData['email'] = $customer->email;
        $validatedData['first_name'] = $customer->first_name;
        $validatedData['last_name'] = $customer->last_name;
        $validatedData['phone'] = $customer->phone;
        $booking = new Booking($validatedData);
        $booking->customer_id = Auth::guard('customer')->user()->id;
        $booking->save();
        return $booking;
    }

    public function tripCreate($validatedData)
    {
        $customer = Auth::guard('customer')->user();
        $validatedData['booking_code'] = bookingHelper::generateBookingCode();
        $validatedData['service_type'] = "trip";
        $validatedData['email']        = $customer->email;
        $validatedData['first_name']   = $customer->first_name;
        $validatedData['last_name']    = $customer->last_name;
        $validatedData['phone']        = $customer->phone;
        $booking = new Booking($validatedData);
        $booking->customer_id   = Auth::guard('customer')->user()->id;
        $booking->save();
        return $booking;
    }

    public function update($validatedData, Booking $booking)
    {
        return $booking->update($validatedData);
    }
    public function updateTrip($validatedData, Booking $booking)
    {
        return $booking->update($validatedData);
    }

    public function delete(Booking $booking)
    {
        return $booking->delete();
    }
}
