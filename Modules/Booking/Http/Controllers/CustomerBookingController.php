<?php

namespace Modules\Booking\Http\Controllers;

use Modules\Booking\Http\Resources\BookingResource;
use Modules\Booking\Http\Resources\HotelRoomsBookingResource;
use Modules\Booking\Http\Services\BookingService;
use App\Http\Controllers\Controller;
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
    public function getAllByCustomer()
    {
        $bookings = $this->bookingService->getAllByCustomer();
        $bookings['Pending']   = $this->resource($bookings['Pending'], BookingResource::class);
        $bookings['Confirmed'] = $this->resource($bookings['Confirmed'], BookingResource::class);
        $bookings['Cancelled'] = $this->resource($bookings['Cancelled'], BookingResource::class);
        return $this->successResponse(
            $bookings,
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
        $this->hotelRoomsBookingService->create($validatedData);
        return $this->successResponse(
            null,
            'bookingSuccessfully'
        );
    }

    public function createTripBooking(BookingRequest $request)
    {
        $validatedData = $request->validated();
        $this->tripBookingService->create($validatedData);
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
