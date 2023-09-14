<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maize\Markable\Models\Bookmark;
use Modules\Hotels\Http\Services\HotelService;
use Modules\Hotels\Models\Hotel;

class BookMarkService
{
    public function __construct(private HotelService $hotelService)
    {
    }

    public function getAll()
    {
        return Hotel::whereHasBookmark(
            Auth::guard('customer')->user()
        )->get();
    }

    public function create($hotelId)
    {
        DB::beginTransaction();

        $hotel = $this->hotelService->showHotelDetails($hotelId);
        $user = Auth::guard('customer')->user();
        Bookmark::add($hotel, $user);

        DB::commit();
    }

    public function delete($hotelId)
    {
        DB::beginTransaction();

        $hotel = $this->hotelService->showHotelDetails($hotelId);
        $user = Auth::guard('customer')->user();
        Bookmark::remove($hotel, $user);

        DB::commit();
    }
}
