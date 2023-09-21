<?php

namespace App\Http\Controllers;

use App\Http\Resources\TripResource;
use App\Http\Services\WishListService;
use Modules\Hotels\Http\Resources\HotelResource;

class WishlistController extends Controller
{
    public function __construct(private WishListService $wishListService)
    {
    }

    public function wishlist()
    {
        $hotels = $this->wishListService->getAll();
        return $this->successResponse(
            $this->resource($hotels, HotelResource::class),
            'dataFetchedSuccessfully'
        );
    }
    public function appWishlist()
    {
        $hotels = $this->wishListService->getAll();
        return $this->successResponse(
            $this->resource($hotels, HotelResource::class),
            'dataFetchedSuccessfully'
        );
    }

    public function favorite()
    {
        $trips = $this->wishListService->get();
        return $this->successResponse(
            $this->resource($trips, TripResource::class),
            'dataFetchedSuccessfully'
        );
    }
    public function appFavorite()
    {
        $trips = $this->wishListService->get();
        return $this->successResponse(
            $this->resource($trips, TripResource::class),
            'dataFetchedSuccessfully'
        );
    }


    public function favoriteAdd($hotelId)
    {
        $this->wishListService->create($hotelId);
        return $this->successResponse(
            null,
            'dataAddedSuccessfully'
        );
    }
    public function AddTripFavorite($tripId)
    {
        $this->wishListService->createFav($tripId);
        return $this->successResponse(
            null,
            'dataAddedSuccessfully'
        );
    }

    public function favoriteRemove($hotelId)
    {
        $this->wishListService->delete($hotelId);
        return $this->successResponse(
            null,
            'dataDeletedSuccessfully'
        );
    }
    public function RemoveTripFavorite($hotelId)
    {
        $this->wishListService->deleteFav($hotelId);
        return $this->successResponse(
            null,
            'dataDeletedSuccessfully'
        );
    }
}
