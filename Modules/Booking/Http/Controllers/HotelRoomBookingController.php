<?php

namespace Modules\Booking\Http\Controllers;

use Modules\Booking\Http\Requests\HotelRoomsBookingRequest;
use Modules\Booking\Http\Resources\HotelRoomsBookingResource;
use Modules\Booking\Http\Services\HotelRoomsBookingService;
use App\Http\Controllers\Controller;

class HotelRoomBookingController extends Controller
{
    public function __construct(private HotelRoomsBookingService $hotelRoomsBookingService)
    {
    }

    public function getAll()
    {
        $hotel_rooms_bookings = $this->hotelRoomsBookingService->getAll();
        return $this->successResponse(
            $this->resource($hotel_rooms_bookings, HotelRoomsBookingResource::class),
            'dataFetchedSuccessfully'
        );
    }

    public function find($hotel_rooms_bookingId)
    {
        $hotel_rooms_booking = $this->hotelRoomsBookingService->find($hotel_rooms_bookingId);

        return $this->successResponse(
            $this->resource($hotel_rooms_booking, HotelRoomsBookingResource::class),
            'dataFetchedSuccessfully'
        );
    }


    public function create(HotelRoomsBookingRequest $request)
    {
        $validatedData = $request->validated();
        $this->hotelRoomsBookingService->create($validatedData);
        return $this->successResponse(
            null,
            'bookingSuccessfully'
        );
    }

    public function update(HotelRoomsBookingRequest $request, $hotel_rooms_bookingId)
    {
        $validatedData = $request->validated();
        $this->hotelRoomsBookingService->update($validatedData, $hotel_rooms_bookingId);

        return $this->successResponse(
            null,
            'dataUpdatedSuccessfully'
        );
    }

    public function delete($hotel_rooms_bookingId)
    {
        $this->hotelRoomsBookingService->delete($hotel_rooms_bookingId);
        return $this->successResponse(
            null,
            'dataDeletedSuccessfully'
        );
    }

}
