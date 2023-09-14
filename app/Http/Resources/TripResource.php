<?php

namespace App\Http\Resources;

use App\Models\FeatureValue;
use Illuminate\Http\Resources\Json\JsonResource;

class TripResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        $featureValuesByFeature = [];
        foreach ($this->feature as $feature) {
            $featureValues = [];
            $Values = FeatureValue::where('tripfeatures_id', $feature->pivot->id)->get();
            foreach ($Values as $Value) {
                $featureValues[] = [
                    'id'    => $Value->id,
                    'value' => $Value->value,
                ];
            }
            $featureValuesByFeature[$feature->name] = $featureValues;
        }
        $cities = [];
        foreach ($this->city as $city) {
            $cities = [
                'id'            => $city->id,
                'name'          => $city->name,
                // 'best_location' => $city->best_location,
                'country_id'    => $city->country_id,
                'description'   => $city->pivot->dis,
            ];
        }

        $locale = app()->getLocale();
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'description' => $this->description,
            'period'      => $this->period,
            'price'       => $this->price,
            'image'       => $this->media_urls,
            'contact'     => $this->contact,
            'date'        => $this->date,
            'feature'     => $this->feature,
            'featurevalue' => $featureValuesByFeature,
            'city'        => $cities,
        ];
        // foreach ($this->items() as $trip) {
        //     $data[] = [
        //         'lang'        => $locale,
        //         'id'          => $trip->id,
        //         'name'        => $trip->name,
        //         'description' => $trip->description,
        //         'period'      => $trip->period,
        //         'price'       => $trip->price,
        //         'image'       => $trip->media_urls,
        //         'contact'     => $trip->contact,
        //         'date'        => $trip->date,
        //         'created_at'  => $trip->created_at
        //     ];
        // }

        // return $data;
    }
}
