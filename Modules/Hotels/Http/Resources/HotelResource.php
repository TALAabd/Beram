<?php

namespace Modules\Hotels\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use App\Traits\CurrencyConvert;

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
        $actionMethod = $request->route()->getActionMethod();
        return match ($actionMethod) {
            'getAppHomePageData'=> $this->getAppHomePage($request),
            'getAllHotels'      => $this->getAppHomePage($request),
            default             => $this->allData($request),
        };
    }
    public function getAppHomePage($request)
    {
        $locale = app()->getLocale();
        $currencyDetails = $this->currencyConvert($request->currencySymbol);
         $data = [];
        if(isset($request->page) && (is_numeric($request->page) && is_numeric($request->per_page))) {    //for pagination
            foreach ($this->items() as $hotel) {

    $data[] = [
            'lang'                => $locale,
            'id'                  => $hotel->id,
            'media_urls'          => $hotel->media_urls,
            'name'                => $hotel->getTranslation('name', $locale) ?? '',
            'address'             => $hotel->getTranslation('address', $locale) ?? '',
            'min_price'           => $hotel->min_price * $currencyDetails['exchange_rate'],
            'max_price'           => $hotel->max_price * $currencyDetails['exchange_rate'],
            'star_rate'           => $hotel->star_rate,
            'favorite'            => $hotel->whereHasFavorite(Auth::guard('customer')->user()) ? 1 : 0,
        ];
            }
            }else
            {
        $data = [
            'lang'                => $locale,
            'id'                  => $this->id,
            'media_urls'          => $this->media_urls,
            'name'                => $this->getTranslation('name', $locale) ?? '',
            'address'             => $this->getTranslation('address', $locale) ?? '',
            'min_price'           => $this->min_price * $currencyDetails['exchange_rate'],
            'max_price'           => $this->max_price * $currencyDetails['exchange_rate'],
            'star_rate'           => $this->star_rate,
            'favorite'            => $this->whereHasFavorite(Auth::guard('customer')->user()) ? 1 : 0,
        ];
            }
        return $data;

    }
        
    public function allData($request)
    {
        $locale = app()->getLocale();
        $currencyDetails = $this->currencyConvert($request->currencySymbol);
        $data = [];
        if(isset($request->page) && (is_numeric($request->page) && is_numeric($request->per_page))) {    //for pagination
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
                    'policy'                => $hotel->policy,
                    'star_rate'             => $hotel->star_rate,
                    'check_in_time'         => $hotel->check_in_time,
                    'check_out_time'        => $hotel->check_out_time,
                    'status'                => $hotel->status,
                    'min_price'             => $hotel->min_price * $currencyDetails['exchange_rate'],
                    'max_price'             => $hotel->max_price * $currencyDetails['exchange_rate'],
                    'symbol_price'          => $currencyDetails['symbol'],
                    'web'                   => $hotel->web,
                    'email'                 => $hotel->email,
                    'fax'                   => $hotel->fax,
                    'phone'                 => $hotel->phone,
                    'media_urls'            => $hotel->media_urls,
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
                'min_price'           => $this->min_price * $currencyDetails['exchange_rate'],
                'max_price'           => $this->max_price * $currencyDetails['exchange_rate'],
                'symbol_price'        => $currencyDetails['symbol'],
                'web'                 => $this->web,
                'email'               => $this->email,   
                'fax'                 => $this->fax,
                'phone'               => $this->phone,
                'media_urls'          => $this->media_urls,
                'provider_id'         => (int)$this->user_id,
                'likes'               => $this->likes->count(),
                'numberOfReviews'     => $this->numberOfReviews(),
                'numberOfRatings'     => $this->numberOfRatings()
            ];
        }

        return $data;
    }
}

