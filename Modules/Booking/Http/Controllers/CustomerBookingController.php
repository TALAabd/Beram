<?php

namespace Modules\Booking\Http\Controllers;

use Modules\Booking\Http\Resources\BookingResource;
use Modules\Booking\Http\Resources\HotelRoomsBookingResource;
use Modules\Booking\Http\Services\BookingService;
use App\Http\Controllers\Controller;
use Modules\Booking\Http\Requests\HotelRoomsBookingRequest;
use Modules\Booking\Http\Services\HotelRoomsBookingService;

class CustomerBookingController extends Controller
{
    public function __construct(
        private BookingService $bookingService,
        private HotelRoomsBookingService $hotelRoomsBookingService
    ) {
    }

    public function getAllByCustomer($status)
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
                null// $this->resource($booking->tableBookings, BookingResource::class),
                ,'dataFetchedSuccessfully'
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
        $this->hotelRoomsBookingService->create($validatedData);
        return $this->successResponse(
            null,
            'bookingSuccessfully'
        );
    }

    // public function createResturantBooking(ResturantTablesBookingRequest $request)
    // {
    //     $validatedData = $request->validated();
    //     $this->ResturantTablesBookingService->create($validatedData);
    //     return $this->successResponse(
    //          null,
    //         'bookingSuccessfully'
    //     );
    // }
}
