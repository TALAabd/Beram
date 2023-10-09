<?php

namespace Modules\Hotels\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Authentication\Models\Customer;
use Illuminate\Support\Facades\Auth;

class RoomAttributeTermsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $locale = app()->getLocale();
        $media = $this->getMedia('rooms-media');
        $sub_media_urls = $media->map(function ($item) {
            return $item->getFullUrl();
        });

        return [
            'lang'      => $locale,
            'id'        => $this->id,
            'title'     => $this->getTranslation('title', $locale) ?? '',
            'content'   => $this->getTranslation('content', $locale) ?? '',
            'policy'    => $this->getTranslation('policy', $locale) ?? '',
            'price'     => $this->syrian_price,
            'hotel_id'  => $this->hotel->id,
            'name'      => $this->hotel->name,
            'address'   => $this->hotel->address,
            'baths'     => $this->baths,
            'room_count' => $this->number,
            'space'     => $this->size,
            'beds'      => $this->beds,
            'media_urls' => $this->media_urls,
            'sub_media_urls'       => $sub_media_urls,
            'attributes_and_terms' =>  $this->attributes,

        ];
    }
}
