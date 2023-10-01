<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SettingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'        => $this->id,
            'beds'      => $this->beds,
            'baths'     => $this->baths,
            'min_price' => $this->min_price,
            'max_price' => $this->max_price,
            'child_age' => $this->child_age,
        ];
    }
}
