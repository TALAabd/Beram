<?php

namespace App\Http\Services;

use App\Traits\ModelHelper;
use App\Http\Services\StoryService;
use App\Http\Services\CityService;
use App\Http\Services\BannerService;
use App\Models\Banner;
use Illuminate\Support\Facades\Cache;
use Modules\Hotels\Http\Services\HotelService;
use Modules\Hotels\Http\Services\RoomService;
use Modules\Booking\Http\Services\BookingService;

class ConfigService
{
    use ModelHelper;

    public function __construct(
        private StoryService   $storyService,
        private CityService    $cityService,
        private HotelService   $hotelService,
        private BannerService  $bannerService,
        private BookingService $bookingService,
        private RoomService    $roomService,
    ) {
    }

    public function getAppHomePageData(): array
    {
        // return Cache::remember('home_page_data', now()->addMinutes(10), function () {
            return [
                'banner1'        => $this->bannerService->banner1(),
                'banner2'        => $this->bannerService->banner2(),
                'topRatedHotels' => $this->hotelService->getTopRatedHotels(),
                'recentlyHotels' => $this->hotelService->recentlyHotels(),
            ];
        // });
    }

    public function getControlPanelStatistics(): array
    {
        return Cache::remember('control_panel_statistics', now()->addMinutes(10), function () {
            return [
                'hotelsCount' => $this->hotelService->count(),
                'bookingsCount' => $this->bookingService->count(),
                'roomsCount' => $this->roomService->count(),
                'bestLocationsCount' => $this->cityService->count(),
            ];
        });
    }
}
