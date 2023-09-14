<?php

namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Hotels\Models\Hotel;

class CityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $locale = app()->getLocale();
        return [
            'id'              => $this->id,
            'name'            => $this->getTranslation('name', $locale) ?? '',
            'best_location'   => $this->best_location,
            'country_id'      => $this->country_id,
            'media_urls'      => $this->media_urls,
            'hotels_count'    => Hotel::where('location_id',$this->id)->count(),
            'created_at'      => $this->created_at
        ];
    }
}
