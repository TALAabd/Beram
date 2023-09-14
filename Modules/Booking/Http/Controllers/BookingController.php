<?php

namespace Modules\Booking\Http\Controllers;

use Modules\Booking\Http\Requests\BookingRequest;
use Modules\Booking\Http\Resources\BookingResource;
use Modules\Booking\Http\Services\BookingService;
use App\Http\Controllers\Controller;
use Modules\Booking\Http\Resources\HotelRoomsBookingResource;

class BookingController extends Controller
{
    public function __construct(private BookingService $bookingService)
    {
        $this->middleware('permission:bookings_manager', ['only' => ['getAll', 'getBookingDetails', 'find', 'update', 'changeStatusBooking']]);
    }

    public function getBookings($status)
    {
        $bookings = $this->bookingService->getAll($status);
        return $this->successResponse(
            $this->resource($bookings, BookingResource::class),
            'dataFetchedSuccessfully'
        );
    }


    public function getAllByCustomer($status)   //get id
    {
        $bookings = $this->bookingService->getAllByCustomer($status);
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
}
