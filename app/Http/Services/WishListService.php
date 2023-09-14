<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maize\Markable\Models\Favorite;
use Modules\Hotels\Http\Services\HotelService;
use Modules\Hotels\Models\Hotel;

class WishListService
{
    public function __construct(private HotelService $hotelService)
    {
    }

    public function getAll()
    {
        return Hotel::whereHasFavorite(
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

    public function delete($hotelId)
    {
        DB::beginTransaction();

        $hotel = $this->hotelService->showHotelDetails($hotelId);
        $user = Auth::guard('customer')->user();
        Favorite::remove($hotel, $user);

        DB::commit();
    }
}
