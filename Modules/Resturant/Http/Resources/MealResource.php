<?php

namespace Modules\Resturant\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;

class MealResource extends JsonResource
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
            'description' =>$this->getTranslation('description', $locale) ?? '',
            'price' => $this->price,
            'menu_id' => $this->menu_id,
            'created_at' => $this->created_at,
            'media_urls' => $this->media_urls,
        ];
    }
}
