<?php

namespace Modules\Resturant\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ResturantResource extends JsonResource
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
            'lang' => $locale,
            'id' => $this->id,
            'name' => $this->getTranslation('name', $locale) ?? '',
            'title' => $this->getTranslation('title', $locale) ?? '',
            'slug' => $this->slug,
            'content' => $this->getTranslation('content', $locale) ?? '',
            'location_id' => $this->location_id,
            'address' =>$this->getTranslation('address', $locale) ?? '',
            'map_lat' => $this->map_lat,
            'map_lng' => $this->map_lng,
            'map_zoom' => $this->map_zoom,
            'is_featured' => $this->is_featured,
            'policy' => $this->getTranslation('policy', $locale) ?? '',
            'star_rate' => $this->star_rate,
            'check_in_time' => $this->check_in_time,
            'check_out_time' => $this->check_out_time,
            'status' => $this->status,
            'media_urls' => $this->media_urls,
        ];
    }
}
