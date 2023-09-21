<?php

namespace App\Http\Services;

use App\Models\Trip;
use App\Services\TripService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maize\Markable\Models\Favorite;
use Modules\Hotels\Http\Services\HotelService;
use Modules\Hotels\Models\Hotel;

class WishListService
{
    public function __construct(private HotelService $hotelService,private TripService $tripService)
    {
    }

    public function getAll()
    {
        return Hotel::whereHasFavorite(
            Auth::guard('customer')->user()
        )->get();
    }
    public function get()
    {
        return Trip::whereHasFavorite(
            Auth::guard('customer')->user()
        )->get();
    }

    public function create($hotelId)
    {
        DB::beginTransaction();

        $hotel = $this->hotelService->showHotelDetails($hotelId);
        $user = Auth::guard('customer')->user();
        Favorite::add($hotel, $user);

        DB::commit();
    }
    public function createFav($tripId)
    {
        DB::beginTransaction();

        $tripId = $this->tripService->find($tripId);
        $user = Auth::guard('customer')->user();
        Favorite::add($tripId, $user);

        DB::commit();
    }

    public function delete($hotelId)
    {
        DB::beginTransaction();

        $hotel = $this->hotelService->showHotelDetails($hotelId);
        $user = Auth::guard('customer')->user();
        Favorite::remove($hotel, $user);

        DB::commit();
    }
    public function deleteFav($tripId)
    {
        DB::beginTransaction();

        $tripId = $this->tripService->find($tripId);
        $user = Auth::guard('customer')->user();
        Favorite::remove($tripId, $user);
        
        DB::commit();
    }
}
