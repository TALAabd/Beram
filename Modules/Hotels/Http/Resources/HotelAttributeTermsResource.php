<?php

namespace Modules\Hotels\Http\Resources;

use App\Http\Resources\CoreTermResource;
use Illuminate\Http\Resources\Json\JsonResource;

class HotelAttributeTermsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $data = array();
        // $data = array();
        if (isset($this->attributes)) {
            foreach ($this->attributes as $attributes) {
                $data[] = [
                    'id'         => $attributes->id,
                    'name'       => $attributes->name,
                    'service'    => $attributes->service,
                    'name'       => $attributes->name,
                    'core_terms' => $attributes->core_terms,
                ];
            }
        }
        $locale = app()->getLocale();
        return [
            'lang'                 => $locale,
            'id'                   => $this->id,
            'name'                 => $this->getTranslation('name', $locale) ?? '',
            'content'              => $this->getTranslation('content', $locale) ?? '',
            'location_id'          => $this->location_id,
            'address'              => $this->getTranslation('address', $locale) ?? '',
            'map_lat'              => $this->map_lat,
            'map_lng'              => $this->map_lng,
            'star_rate'            => $this->star_rate,
            'phone'                => $this->phone,
            'email'                => $this->email,
            'web'                  => $this->web,
            'media_urls'           => $this->media_urls,
            'attributes_and_terms' =>  $data,
        ];
    }
}
