<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StoryResource extends JsonResource
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
            'id' => $this->id,
            'title' => $this->title,
            'provider_id' => $this->provider_id,
            'provider_name' => $this->provider->name,
            'created_at' => $this->created_at,
            'created_at' => $this->created_at,
            'items' => StoryItemResource::collection($this->items),
        ];
    }
}
