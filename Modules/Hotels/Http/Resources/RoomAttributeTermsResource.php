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
  
        return [
             'lang'      => $locale,
             'id'        => $this->id,
             'title'     => $this->getTranslation('title', $locale) ?? '',
             'content'   => $this->getTranslation('content', $locale) ?? '',
             'price'     => $this->syrian_price,
             'name'      => $this->hotel->name,
             'address'   => $this->hotel->address,
             'baths'     => $this->baths,
             'space'     => $this->size,
             'beds'      => $this->beds,
             'media_urls' => $this->media_urls,
             'attributes_and_terms' =>  $this->attributes,
        ];
    }
}
