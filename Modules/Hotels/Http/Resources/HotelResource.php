<?php

namespace Modules\Hotels\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use App\Traits\CurrencyConvert;
use Illuminate\Support\Facades\DB;
use Modules\Hotels\Models\Hotel;

class HotelResource extends JsonResource
{
    use CurrencyConvert;
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {

        if (request()->routeIs('appWishlist')) {
            return $this->getAppHomePage($request);
        }
        $actionMethod = $request->route()->getActionMethod();
        return match ($actionMethod) {
            'getAppHomePageData' => $this->getAppHomePage($request),
            'getAllHotels'       => $this->getAppHomePage($request),
            'getNearestHotel'    => $this->getNearestHotel($request),
            'getHotelsAndRooms'  => $this->getHotelsAndRooms($request),
            default              => $this->allData($request),
        };
    }

    public function getNearestHotel($request)
    {
        $locale = app()->getLocale();
        $data = [];
        $data = [
            'lang'                => $locale,
            'id'                  => $this->id,
            'name'                => $this->getTranslation('name', $locale) ?? '',
            'address'             => $this->getTranslation('address', $locale) ?? '',
            'star_rate'           => $this->star_rate,
            'lat'                 => $this->map_lat,
            'long'                => $this->map_lng,
        ];

        return $data;
    }

    public function getHotelsAndRooms($request)
    {
        $locale = app()->getLocale();
        $data = [];

        $rooms = [];
        foreach ($this->rooms as $room) {
            $rooms[] = [
                'id'    => $room->id,
                'title' => $room->getTranslation('title', $locale) ?? '',
            ];
        }

        $data = [
            'id'    => $this->id,
            'name'  => $this->getTranslation('name', $locale) ?? '',
            'rooms' => $rooms,
        ];

        return $data;
    }
    public function getAppHomePage($request)
    {
    
        $fav = 0;
        if (Auth::guard('customer')->id()) {
            $fav = DB::table('markable_favorites')->select('markable_favorites.*')->where([
                ['customer_id', '=', Auth::guard('customer')->id()],
                ['markable_id', '=', $this->id],
                ['markable_type', '=', get_class(new Hotel())]
            ])->count();

        }

        $locale = app()->getLocale();
        $currencyDetails = $this->currencyConvert($request->currencySymbol);
        $data = [];
        // $favorite = 0;
        // if (Auth::guard('customer')->user() && $this->whereHasFavorite(Auth::guard('customer')->user())->count() > 0) {
        //     $favorite = 1;
        // }
        if (isset($request->page) && (is_numeric($request->page) && is_numeric($request->per_page))) {    //for pagination
            foreach ($this->items() as $hotel) {
                $data[] = [

                    'lang'                => $locale,
                    'id'                  => $hotel->id,
                    'image'               => $hotel->media_urls,
                    'name'                => $hotel->getTranslation('name', $locale) ?? '',
                    'address'             => $hotel->getTranslation('address', $locale) ?? '',
                    // 'min_price'           => (int)$hotel->min_price,
                    // 'max_price'           => (int)$hotel->max_price ,
                    'star_rate'           => $hotel->star_rate,
                    'is_featured'         => $hotel->is_featured,
                    'email'               => $hotel->email,
                    'phone'               => $hotel->phone,
                    'lat'                 => $hotel->map_lat,
                    'long'                => $hotel->map_lng,
                    'numberOfReviews'     => $hotel->numberOfReviews(),
                    'numberOfRatings'     => $hotel->numberOfRatings(),
                    'favorite'            => $fav,

                ];
            }
        } else {
            $data = [

                'lang'                => $locale,
                'id'                  => $this->id,
                'image'               => $this->media_urls,
                'name'                => $this->getTranslation('name', $locale) ?? '',
                'address'             => $this->getTranslation('address', $locale) ?? '',
                // 'min_price'           => (int)$this->min_price ,
                // 'max_price'           => (int)$this->max_price ,
                'lat'                 => $this->map_lat,
                'long'                => $this->map_lng,
                'star_rate'           => $this->star_rate,
                'email'               => $this->email,
                'is_featured'         => $this->is_featured,
                'phone'               => $this->phone,
                'numberOfReviews'     => $this->numberOfReviews(),
                'numberOfRatings'     => $this->numberOfRatings(),

                'favorite'            => $fav,

            ];
        }
        return $data;
    }

    public function allData($request)
    {

        $media = $this->getMedia('hotels-media');
        $sub_media_urls = $media->map(function ($item) {
            return $item->getFullUrl();
        });

        $locale = app()->getLocale();
        $currencyDetails = $this->currencyConvert($request->currencySymbol);
        $data = [];
        if (isset($request->page) && (is_numeric($request->page) && is_numeric($request->per_page))) {    //for pagination
            foreach ($this->items() as $hotel) {
                $data[] = [
                    'lang'                  => $locale,
                    'id'                    => $hotel->id,
                    'name'                  => $hotel->name,
                    'title'                 => $hotel->title,
                    'slug'                  => $hotel->slug,
                    'content'               => $hotel->content,
                    'location_id'           => $hotel->location_id,
                    'address'               => $hotel->address,
                    'map_lat'               => $hotel->map_lat,
                    'map_lng'               => $hotel->map_lng,
                    'map_zoom'              => $hotel->map_zoom,
                    'is_featured'           => $hotel->is_featured,
                    'policy'                => $this->getTranslation('policy', $locale) ?? '',
                    'star_rate'             => $hotel->star_rate,
                    'check_in_time'         => $hotel->check_in_time,
                    'check_out_time'        => $hotel->check_out_time,
                    'status'                => $hotel->status,
                    // 'min_price'             => (int)$hotel->min_price,
                    // 'max_price'             => (int)$hotel->max_price,
                    'symbol_price'          => $currencyDetails['symbol'],
                    'web'                   => $hotel->web,
                    'email'                 => $hotel->email,
                    'fax'                   => $hotel->fax,
                    'phone'                 => $hotel->phone,
                    'image'                 => $hotel->media_urls,
                    'sub_media_urls'        => $sub_media_urls,
                    'likes'                 => $hotel->likes->count(),
                    'numberOfReviews'       => $hotel->numberOfReviews(),
                    'numberOfRatings'       => $hotel->numberOfRatings()
                ];
            }
        } else {           //for get data
            $data = [
                'lang'                => $locale,
                'id'                  => $this->id,
                'name'                => $this->getTranslation('name', $locale) ?? '',
                'title'               => $this->getTranslation('title', $locale) ?? '',
                'slug'                => $this->slug,
                'content'             => $this->getTranslation('content', $locale) ?? '',
                'location_id'         => $this->location_id,
                'address'             => $this->getTranslation('address', $locale) ?? '',
                'map_lat'             => $this->map_lat,
                'map_lng'             => $this->map_lng,
                'map_zoom'            => $this->map_zoom,
                'is_featured'         => $this->is_featured,
                'policy'              => $this->getTranslation('policy', $locale) ?? '',
                'star_rate'           => $this->star_rate,
                'check_in_time'       => $this->check_in_time,
                'check_out_time'      => $this->check_out_time,
                'status'              => $this->status,
                // 'min_price'           => (int)$this->min_price ,
                // 'max_price'           => (int)$this->max_price ,
                'symbol_price'        => $currencyDetails['symbol'],
                'web'                 => $this->web,
                'email'               => $this->email,
                'fax'                 => $this->fax,
                'phone'               => $this->phone,
                'image'               => $this->media_urls,
                'sub_media_urls'        => $sub_media_urls,
                'provider_id'         => (int)$this->user_id,
                'likes'               => $this->likes->count(),
                'numberOfReviews'     => $this->numberOfReviews(),
                'numberOfRatings'     => $this->numberOfRatings()
            ];
        }

        return $data;
    }
}
