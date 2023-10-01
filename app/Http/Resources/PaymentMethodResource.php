<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentMethodResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // $media_urls =  $this->getMedia('payment_method')->map(function ($media) {
        //     return $media->getFullUrl();
        // })->toArray();

        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'status'     => $this->status,
            'media_url' => $this->getFirstMediaUrl('payment_method'),
        ];
    }
}
