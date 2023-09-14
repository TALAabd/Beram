<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Hotels\Http\Resources\HotelResource;
use App\Http\Resources\{
    SettingResource,
    CountryResource,
};


class ConfigResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if (request()->routeIs('setting')) {
             return [
            'ranges'    => SettingResource::collection($this->resource['rangs']),
            'country'   => CountryResource::collection($this->resource['country']),
        ]; 
        }
        $actionMethod = $request->route()->getActionMethod();
        return match ($actionMethod) {
            'getAppHomePageData' => $this->getAppHomePageData(),
            'getControlPanelStatistics' => $this->getControlPanelStatistics(),
            default => []
        };
    }

    public function getAppHomePageData()
    {
        return [
            'banners1'         => BannerResource::collection($this->resource['banner1']),
            'banners2'         => BannerResource::collection($this->resource['banner2']),
            'topRatedHotels'   => HotelResource::collection($this->resource['topRatedHotels']),
            'recentlyHotels'   => HotelResource::collection($this->resource['recentlyHotels']),
            'tours'            => HotelResource::collection($this->resource['recentlyHotels']),
        ]; 
    }

    public function getControlPanelStatistics()
    {
        return [
            'hotels_count'          => $this->resource['hotelsCount'],
            'bookings_count'        => $this->resource['bookingsCount'],
            'rooms_count'           => $this->resource['roomsCount'],
            'best_locations_count'  => $this->resource['bestLocationsCount'],
        ];
    }
}
