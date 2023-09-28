<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CoreTermResource extends JsonResource
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
        $resource = [
            'id'                => $this->id,
            'name'              => $this->getTranslation('name', $locale) ?? '',
            'content'           => $this->getTranslation('content', $locale) ?? '',
            'core_attribute_id' => $this->core_attribute_id,
            'price'             => $this->price,
            'slug'              => $this->slug,
            'icon_name'         => $this->icon_name,
            'created_at'        => $this->created_at,
        ];

        //for get attirbutes for hotel
        if (isset($this->status))
            $resource += [
                'status' => $this->status,
            ];

        return $resource;
    }
}
