<?php

namespace Modules\Booking\Http\Controllers;

use Modules\Booking\Http\Resources\BookingResource;
use Modules\Booking\Http\Resources\HotelRoomsBookingResource;
use Modules\Booking\Http\Services\BookingService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Modules\Booking\Http\Requests\BookingRequest;
use Modules\Booking\Http\Requests\HotelRoomsBookingRequest;
use Modules\Booking\Http\Services\HotelRoomsBookingService;
use Modules\Booking\Http\Services\TripBookingService;

class CustomerBookingController extends Controller
{
    public function __construct(
        private BookingService $bookingService,
        private HotelRoomsBookingService $hotelRoomsBookingService,
        private TripBookingService $tripBookingService,
    ) {
        if (Auth::guard('user')->user()) {
            $this->middleware('permission:book_room', ['only' => ['HotelGuestBooking']]);
            $this->middleware('permission:book_trip', ['only' => ['createGuestBooking']]);
        }
    }

    public function getAllByCustomerStatus($status)
    {
        $bookings = $this->bookingService->getAllByCustomerStatus($status);
        return $this->successResponse(
            $this->resource($bookings, BookingResource::class),
            'dataFetchedSuccessfully'
        );
    }

    public function search(BookingRequest $request)
    {
        $validatedData = $request->validated();

        $bookings = $this->bookingService->search($validatedData);
        return $this->successResponse(
            $this->resource($bookings, BookingResource::class),
            'dataFetchedSuccessfully'
        );
    }

    public function getAllByCustomer(Request $request)
    {
        App::setlocale($request->lang);
        if (!isset($request->type) || $request->type == null)
            $type = 'hotel';
        else
            $type = $request->type;

        $bookings = $this->bookingService->getAllByCustomer($type, $request->status);
        // $bookings['Pending']   = $this->resource($bookings['Pending'], BookingResource::class);
        // $bookings['Confirmed'] = $this->resource($bookings['Confirmed'], BookingResource::class);
        // $bookings['Cancelled'] = $this->resource($bookings['Cancelled'], BookingResource::class);
        return $this->successResponse(
            $this->resource($bookings, BookingResource::class),
            'dataFetchedSuccessfully'
        );
    }

    public function getAllTripsByCustomer(Request $request)
    {
        App::setlocale($request->lang);
        $type = 'trip';
        $bookings = $this->bookingService->getAllByCustomer($type, $request->status);
        // $bookings['Pending']   = $this->resource($bookings['Pending'], BookingResource::class);
        // $bookings['Confirmed'] = $this->resource($bookings['Confirmed'], BookingResource::class);
        // $bookings['Cancelled'] = $this->resource($bookings['Cancelled'], BookingResource::class);
        return $this->successResponse(
            $this->resource($bookings, BookingResource::class),
            'dataFetchedSuccessfully'
        );
    }

    public function getBookingDetails($bookingId)
    {
        $booking = $this->bookingService->getBookingDetails($bookingId);
        // dd($booking->roomBookings);
        if ($booking->service_type == "hotel")

            return $this->successResponse(
                $this->resource($booking->roomBookings, HotelRoomsBookingResource::class),
                'dataFetchedSuccessfully'
            );
        else
            return $this->successResponse(
                null // $this->resource($booking->tableBookings, BookingResource::class),
                ,
                'dataFetchedSuccessfully'
            );
    }

    public function cancelBooking($bookingId)
    {
        $this->bookingService->cancelBooking($bookingId);
        return $this->successResponse(
            null,
            'dataUpdatedSuccessfully'
        );
    }

    public function createHotelBooking(HotelRoomsBookingRequest $request)
    {
        $validatedData = $request->validated();
        $data = $this->hotelRoomsBookingService->create($validatedData);
        return $this->successResponse(
            $data,
            'bookingSuccessfully'
        );
    }
    public function HotelGuestBooking(HotelRoomsBookingRequest $request)
    {
        $validatedData = $request->validated();
        $data = $this->hotelRoomsBookingService->HotelGuestBooking($validatedData);
        return $data;
        // return $this->successResponse(
        //     $data,
        //     'bookingSuccessfully'
        // );
    }

    public function createTripBooking(BookingRequest $request)
    {
        $validatedData = $request->validated();
        $data = $this->tripBookingService->create($validatedData);
        return $this->successResponse(
            $data,
            'bookingSuccessfully'
        );
    }
    public function createGuestBooking(BookingRequest $request) //trip
    {
        $validatedData = $request->validated();
        $data =  $this->tripBookingService->createGuestBooking($validatedData);
        return $data;
        //  $this->successResponse(
        //     $data,
        //     'bookingSuccessfully'
        // );
    }
}
