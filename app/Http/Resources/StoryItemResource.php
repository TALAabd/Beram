<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StoryItemResource extends JsonResource
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
            'story_id' => $this->story_id,
            'story_type' => $this->story_type,
            'description' => $this->description,
            'image' => $this->hasMedia('image') ? $this->getMedia('image')->first()->original_url : null,
            'video' => $this->hasMedia('video') ? $this->getMedia('video')->first()->original_url : null,
            'created_at' => $this->created_at
        ];
    }
}
