<?php

namespace App\Http\Resources;

use App\Models\FeatureValue;
use App\Models\Trip;
use App\Traits\CurrencyConvert;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class TripResource extends JsonResource
{
    use CurrencyConvert;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if (request()->routeIs('app.home.page')) {
            return $this->allDataForApp($request);
        } elseif (request()->routeIs('trip.index.customer')) {
            return $this->getDataForApp($request);
        } elseif (request()->routeIs('trip.show.customer')) {
            return $this->allDataForApp($request);
        }
        elseif (request()->routeIs('app.favorite')) {
            return $this->getDataForApp($request);
        }

        $actionMethod = $request->route()->getActionMethod();
        return match ($actionMethod) {
            default  => $this->allData($request),
        };
    }

    public function getAppHomePage($request)
    {
        $locale = app()->getLocale();

        $fav = 0;
        if (Auth::guard('customer')->id()) {
            $fav = DB::table('markable_favorites')->select('markable_favorites.*')->where([
                ['customer_id', '=', Auth::guard('customer')->id()],
                ['markable_id', '=', $this->id],
                ['markable_type', '=', get_class(new Trip())]
            ])->count();
        }


        $currencyDetails = $this->currencyConvert($request->currencySymbol);
        $data = [];
        if (isset($request->page) && (is_numeric($request->page) && is_numeric($request->per_page))) {    //for pagination
            foreach ($this->items() as $trip) {

                $data[] = [
                    'lang'       => $locale,
                    'id'         => $trip->id,
                    'media_urls' => $trip->media_urls,
                    'name'       => $trip->getTranslation('name', $locale) ?? '',
                    'price'      => (int)$trip->price,
                    'period'     => $trip->period,
                    'contact'    => $trip->contact,
                    'date'       => $trip->date,
                    'status'     => $trip->status,
                    'favorite'   => $fav,
                    'starting_city_id' => $trip->starting_city_id,
                    'starting_city'    => $trip->startingCity ? $trip->startingCity->name : null,
                ];
            }
        } else {
            $data = [
                'lang'       => $locale,
                'id'         => $this->id,
                'media_urls' => $this->media_urls,
                'name'       => $this->getTranslation('name', $locale) ?? '',
                'price'      => (int)$this->price ,
                'period'     => $this->period,
                'contact'    => $this->contact,
                'date'       => $this->date,
                'status'     => $this->status,
                'favorite'   => $fav,
                'starting_city_id' => $this->starting_city_id,
                'starting_city'    => $this->startingCity ? $this->startingCity->name : null,
            ];
        }
        return $data;
    }
    public function allData($request)
    {
        $fav = 0;
        if (Auth::guard('customer')->id()) {
            $fav = DB::table('markable_favorites')->select('markable_favorites.*')->where([
                ['customer_id', '=', Auth::guard('customer')->id()],
                ['markable_id', '=', $this->id],
                ['markable_type', '=', get_class(new Trip())]
            ])->count();
        }


        $locale = app()->getLocale();
        $featureValuesByFeature = [];
        foreach ($this->feature as $key => $feature) {
            $featureValues = [];
            $Values = FeatureValue::where('tripfeatures_id', $feature->pivot->id)->get();
            foreach ($Values as $Value) {
                $featureValues[] = [
                    'id'    => $Value->id,
                    'value' => $Value->value,
                ];
            }
            $featureValuesByFeature[$key]['feature'] = $feature;
            $featureValuesByFeature[$key]['values']  = $featureValues;
        }
        $cities = [];
        foreach ($this->city as $city) {

            $description = json_decode($city->pivot->dis, true);
            $englishDescription = $description[$locale] ?? '';


            $cities[] = [
                'id'            => $city->id,
                'name'          => $city->name,
                'country_id'    => $city->country_id,
                'description'   => $englishDescription,
            ];
        }

        return [
            // dd($this->provider->first_name),
            'id'          => $this->id,
            'provider_id' => $this->provider_id,
            'provider'    => $this->provider ? $this->provider->first_name : null,
            'name'        => $this->name,
            'description' => $this->description,
            'period'      => $this->period,
            'price'       => (int)$this->price,
            'image'       => $this->media_urls,
            'contact'     => $this->contact,
            'date'        => $this->date,
            'status'      => $this->status,
            'starting_city' => $this->startingCity ? $this->startingCity->name : null,
            // 'feature'     => $this->feature,
            'featurevalue' => $featureValuesByFeature,
            'city'        => $cities,
            'starting_city_id' => $this->starting_city_id,
            'favorite'         => $fav,
        ];
    }
    public function allDataForApp($request)
    {
        $fav = 0;
        if (Auth::guard('customer')->id()) {
            $fav = DB::table('markable_favorites')->select('markable_favorites.*')->where([
                ['customer_id', '=', Auth::guard('customer')->id()],
                ['markable_id', '=', $this->id],
                ['markable_type', '=', get_class(new Trip())]
            ])->count();
        }

        $locale = app()->getLocale();
        $featureValuesByFeature = [];
        foreach ($this->feature as $key => $feature) {
            $featureValues = [];
            $Values = FeatureValue::where('tripfeatures_id', $feature->pivot->id)->get();
            foreach ($Values as $Value) {
                $featureValues[] = [
                    'id'    => $Value->id,
                    'value' => $Value->value,
                ];
            }
            $featureValuesByFeature[$key]['feature'] = $feature;
            $featureValuesByFeature[$key]['values']  = $featureValues;
        }
        $cities = [];
        foreach ($this->city as $city) {

            $description = json_decode($city->pivot->dis, true);
            $englishDescription = $description[$locale] ?? '';


            $cities[] = [
                'id'            => $city->id,
                'name'          => $city->name,
                'country_id'    => $city->country_id,
                'description'   => $englishDescription,
            ];
        }

        $media = $this->getMedia('trips-media');
        $sub_media_urls = $media->map(function ($item) {
            return $item->getFullUrl();
        });

        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'description' => $this->description,
            'period'      => $this->period,
            'price'       => (int)$this->price,
            'media_urls'  => $this->media_urls,
            'sub_media_urls' => $sub_media_urls,
            'contact'     => $this->contact,
            'date'        => $this->date,
            'status'      => $this->status,
            'provider_id' => $this->provider_id,
            'starting_city' => $this->startingCity ? $this->startingCity->name : null,
            // 'feature'     => $this->feature,
            'featurevalue' => $featureValuesByFeature,
            'city'        => $cities,
            'starting_city_id' => $this->starting_city_id,
            'favorite'         => $fav,
        ];
    }
    public function getDataForApp($request)
    {
        $locale = app()->getLocale();
        $fav = 0;
        if (Auth::guard('customer')->id()) {
            $fav = DB::table('markable_favorites')->select('markable_favorites.*')->where([
                ['customer_id', '=', Auth::guard('customer')->id()],
                ['markable_id', '=', $this->id],
                ['markable_type', '=', get_class(new Trip())]
            ])->count();
        }


        // $featureValuesByFeature = [];
        // foreach ($this->feature as $key => $feature) {
        //     $featureValues = [];
        //     $Values = FeatureValue::where('tripfeatures_id', $feature->pivot->id)->get();
        //     foreach ($Values as $Value) {
        //         $featureValues[] = [
        //             'id'    => $Value->id,
        //             'value' => $Value->value,
        //         ];
        //     }
        //     $featureValuesByFeature[$key]['feature'] = $feature;
        //     $featureValuesByFeature[$key]['values']  = $featureValues;
        // }
        // $cities = [];
        // foreach ($this->city as $city) {

        //     $description = json_decode($city->pivot->dis, true);
        //     $englishDescription = $description[$locale] ?? '';


        //     $cities[] = [
        //         'id'            => $city->id,
        //         'name'          => $city->name,
        //         'country_id'    => $city->country_id,
        //         'description'   => $englishDescription,
        //     ];
        // }

        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'provider_id' => $this->provider_id,
            'description' => $this->description,
            'period'      => $this->period,
            'price'       => (int)$this->price,
            'media_urls'  => $this->media_urls,
            'contact'     => $this->contact,
            'status'      => $this->status,
            'date'        => $this->date,
            'starting_city' => $this->startingCity ? $this->startingCity->name : null,
            // 'feature'     => $this->feature,
            // 'featurevalue' => $featureValuesByFeature,
            // 'city'        => $cities,
            'starting_city_id' => $this->starting_city_id,
            'favorite'         => $fav,
        ];
    }
}
