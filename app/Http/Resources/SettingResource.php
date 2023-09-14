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
        // dd($this->rangs);
        return [
            'id'        => $this->id,
            'beds'      => $this->beds,
            'baths'     => $this->baths,
            'min price' => $this->min_prie,
            'max price' => $this->max_price,
        ];
    }
}
