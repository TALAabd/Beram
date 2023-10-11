<?php

namespace Modules\Booking\Http\Controllers;

use Modules\Booking\Http\Requests\BookingRequest;
use Modules\Booking\Http\Resources\BookingResource;
use Modules\Booking\Http\Services\BookingService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Booking\Http\Resources\HotelRoomsBookingResource;

class BookingController extends Controller
{
    public function __construct(private BookingService $bookingService)
    {
        $this->middleware('permission:hotel_bookings_manager|trip_bookings_manager', ['only' => ['update','updateTrip', 'changeStatusBooking']]);
        $this->middleware('permission:hotel_bookings_manager|trip_bookings_manager|get_confirmed_hotel_bookings_manager|get_confirmed_trip_bookings_manager',
         ['only' => ['getBookings','getRecentBookings','find']]);
    }

    public function getBookings(Request $request, $status)
    {
        $type = 'hotel';
        if (isset($request->type))
            $type = $request->type;

        $bookings = $this->bookingService->getAll($type, $status);
        return $this->successResponse(
            $this->resource($bookings, BookingResource::class),
            'dataFetchedSuccessfully'
        );
    }

    public function getRecentBookings(Request $request)
    {

        $bookings = $this->bookingService->getRecentBookings($request);
        return $this->successResponse(
            $this->resource($bookings, BookingResource::class),
            'dataFetchedSuccessfully'
        );
    }

    public function getAllByCustomer(Request $request, $status)   //get id
    {
        $type = 'hotel';
        if (isset($request->type))
            $type = $request->type;

        $bookings = $this->bookingService->getAllByCustomer($type, $status);
        return $this->successResponse(
            $this->resource($bookings, BookingResource::class),
            'dataFetchedSuccessfully'
        );
    }

    public function getBookingDetails($bookingId)
    {
        $booking = $this->bookingService->getBookingDetails($bookingId);
        if ($booking->service_type == "hotel")
            return $this->successResponse(
                $this->resource($booking->roomBookings, HotelRoomsBookingResource::class),
                'dataFetchedSuccessfully'
            );
        else
            return $this->successResponse(
                null // $this->resource($booking->tableBookings, RestuurantTablesBookingResource::class),
                ,
                'dataFetchedSuccessfully'
            );
    }

    public function find($bookingId)
    {
        $booking = $this->bookingService->find($bookingId);
        return $this->successResponse(
            $this->resource($booking, BookingResource::class),
            'dataFetchedSuccessfully'
        );
    }

    public function update(BookingRequest $request, $bookingId)
    {
        $validatedData = $request->validated();
        $this->bookingService->update($validatedData, $bookingId);
        return $this->successResponse(
            null,
            'dataUpdatedSuccessfully'
        );
    }
    public function updateTrip(BookingRequest $request, $bookingId)
    {
        $validatedData = $request->validated();
        $this->bookingService->updateTrip($validatedData, $bookingId);
        return $this->successResponse(
            null,
            'dataUpdatedSuccessfully'
        );
    }

    public function changeStatusBooking(BookingRequest $request, $bookingId)
    {
        $validatedData = $request->validated();
        $this->bookingService->changeStatus($validatedData, $bookingId);
        return $this->successResponse(
            null,
            'dataUpdatedSuccessfully'
        );
    }

    public function confirmedBooking($bookingId)
    {
        $this->bookingService->confirmedBooking($bookingId);
        return $this->successResponse(
            null,
            'dataUpdatedSuccessfully'
        );
    }

    public function saveBookingFile(BookingRequest $request, $bookingId)
    {
        $validatedData = $request->validated();

        if ($request->file('file') && $request->file('file')->isValid()) {
            $this->bookingService->saveBookingFile($bookingId, $request->file('file'));
        }


        return $this->successResponse(
            null,
            'dataUpdatedSuccessfully'
        );
    }


}
