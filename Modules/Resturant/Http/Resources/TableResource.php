<?php

namespace Modules\Resturant\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TableResource extends JsonResource
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
            'title' => $this->getTranslation('title', $locale) ?? '',
            'content' => $this->getTranslation('content', $locale) ?? '',
            'price' => $this->price,
            'number' => $this->number,
            'size' => $this->size,
            'status' => $this->status,
            'resturant_id' => $this->resturant_id,
            'created_at' => $this->created_at,
            'media_urls' => $this->media_urls,
        ];
    }
}
