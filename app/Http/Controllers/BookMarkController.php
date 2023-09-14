<?php

namespace App\Http\Controllers;

use App\Http\Services\BookMarkService;
use Modules\Hotels\Http\Resources\HotelResource;

class BookMarkController extends Controller
{
    public function __construct(private BookMarkService $bookMarkService)
    {
    }

    public function bookMarklist()
    {
        $hotels = $this->bookMarkService->getAll();
        return $this->successResponse(
            $this->resource($hotels, HotelResource::class),
            'dataFetchedSuccessfully'
        );
    }

    public function bookMarkAdd($hotelId)
    {
        $this->bookMarkService->create($hotelId);
        return $this->successResponse(
            null,
            'dataAddedSuccessfully'
        );
    }

    public function bookMarkRemove($hotelId)
    {
        $this->bookMarkService->delete($hotelId);
        return $this->successResponse(
            null,
            'dataDeletedSuccessfully'
        );
    }
}
