<?php


namespace Modules\Hotels\Http\Controllers\CustomerController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Hotels\Models\Hotel;
use Modules\Hotels\Http\Resources\HotelAttributeTermsResource;
use Modules\Hotels\Http\Resources\HotelResource;
use Modules\Hotels\Http\Resources\RoomAttributeTermsResource;
use Modules\Hotels\Http\Resources\RoomResource;
use Modules\Hotels\Http\Services\HotelService;
use Modules\Hotels\Http\Services\RoomService;

class CustomerHotelsController extends Controller
{
    public function __construct(private HotelService $hotelService, private RoomService $roomService)
    {
    }

    public function getAllHotels(Request $request)
    {
        $hotels = $this->hotelService->getHotels($request);
        return $this->successResponse(
            $this->resource($hotels, HotelResource::class),
            'dataFetchedSuccessfully'
        );
    }

    public function getFeaturedHotels()
    {
        $hotels = $this->hotelService->getFeaturedHotels();
        return $this->successResponse(
            $this->resource($hotels, HotelResource::class),
            'dataAddedSuccessfully'
        );
    }

    public function getDetailsByHotel($hotelId)
    {
        $hotelDetails = $this->hotelService->showHotelDetailsWithTerms($hotelId);
        return $this->successResponse(
            $this->resource($hotelDetails, HotelAttributeTermsResource::class),
            'dataAddedSuccessfully'
        );
    }

    public function getRoomsByHotel(Request $request)
    {
        $rooms = $this->hotelService->getRoomsByhotel($request);
        return $this->successResponse(
            $this->resource($rooms, RoomResource::class),
            'dataAddedSuccessfully'
        );
    }


    public function getMediaByHotel($hotelId)
    {
        $media = $this->hotelService->getAllMedia($hotelId);
        return $this->successResponse(
            $media,
            'mediaFetchedSuccessfully'
        );
    }

    public function getMediaByRoom($roomId)
    {
        $media = $this->roomService->getAllMedia($roomId);
        return $this->successResponse(
            $media,
            'mediaFetchedSuccessfully'
        );
    }

    public function getDetailsByRoom($roomId)
    {
        $rooms = $this->roomService->showRoomDetailsWithTerms($roomId);
        return $this->successResponse(
            $this->resource($rooms, RoomAttributeTermsResource::class),
            'dataAddedSuccessfully'
        );
    }
}
