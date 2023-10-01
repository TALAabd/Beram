<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FeatureResource extends JsonResource
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
        $can_update = 1;
        if ($this->getTranslation('name', 'en') == 'trip program') {
            $can_update = 0;
        }
        return [
            'id'         => $this->id,
            'name'       =>  $this->getTranslation('name', $locale) ?? '',
            'can_update' => $can_update,
            'created_at' => $this->created_at
        ];
    }
}
