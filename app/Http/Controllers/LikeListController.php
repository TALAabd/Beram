<?php

namespace App\Http\Controllers;

use App\Http\Services\LikeListService;
use Modules\Hotels\Http\Resources\HotelResource;

class LikeListController extends Controller
{
    public function __construct(private LikeListService $likeListService)
    {
    }

    public function likelist()
    {
        $hotels = $this->likeListService->getAll();
        return $this->successResponse(
            $this->resource($hotels, HotelResource::class),
            'dataFetchedSuccessfully'
        );
    }

    public function likeAdd($hotelId)
    {
        $this->likeListService->create($hotelId);
        return $this->successResponse(
            null,
            'dataAddedSuccessfully'
        );
    }

    public function likeRemove($hotelId)
    {
        $this->likeListService->delete($hotelId);
        return $this->successResponse(
            null,
            'dataDeletedSuccessfully'
        );
    }
}
