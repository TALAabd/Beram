<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maize\Markable\Models\Like;
use Modules\Hotels\Http\Services\HotelService;
use Modules\Hotels\Models\Hotel;

class LikeListService
{
    public function __construct(private HotelService $hotelService)
    {
    }

    public function getAll()
    {
        return Hotel::whereHasLike(
            Auth::guard('customer')->user()
        )->get();
    }

    public function create($hotelId)
    {
        DB::beginTransaction();

        $hotel = $this->hotelService->showHotelDetails($hotelId);
        $user = Auth::guard('customer')->user();
        Like::add($hotel, $user);

        DB::commit();
    }

    public function delete($hotelId)
    {
        DB::beginTransaction();

        $hotel = $this->hotelService->showHotelDetails($hotelId);
        $user = Auth::guard('customer')->user();
        Like::remove($hotel, $user);

        DB::commit();
    }
}
