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
        $bookings = Booking::bookings()->where('status', $status)->orderBy('id','Desc')->get();
        return $bookings;
    }

    public function getAllByUserStatus($status)
    {
        $customer = Auth::guard('customer')->user();
        $customer = Customer::where('email', $customer->email)->orWhere('phone', $customer->phone)->first();
        return $customer->bookings()->where('status', $status)->orderBy('id','Desc')->get();
    }

    public function getAllByCustomer($type)
    {
        $customer = Auth::guard('customer')->user();
        $customer = Customer::where('email', $customer->email)->orWhere('phone', $customer->phone)->first();
        $bookings['Pending']   =  $customer->bookings()->where('service_type', $type)->where('status', 'Pending')->orderBy('id','Desc')->get();
        $bookings['Cancelled'] =  $customer->bookings()->where('service_type', $type)->where('status', 'Cancelled')->orderBy('id','Desc')->get();
        $bookings['Confirmed'] =  $customer->bookings()->where('service_type', $type)->where('status', 'Confirmed')->orderBy('id','Desc')->get();
        return $bookings;
    }


    public function search($request)
    {
        $bookings = Booking::where('booking_code', $request['booking_code'])
            ->where('last_name', $request['last_name'])->where('first_name', $request['first_name'])->where('service_type', 'hotel')->get();
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
        $validatedData['total_price'] = $customer->total_price;
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
        $validatedData['total_guests'] = $customer->total_guests;
        $validatedData['email']       = $customer->email;
        $validatedData['first_name']  = $customer->first_name;
        $validatedData['last_name']   = $customer->last_name;
        $validatedData['phone']       = $customer->phone;
        $validatedData['total_price'] = $customer->total_price;
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
