<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WalletResource extends JsonResource
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
            'id'                   => $this->id,
            'provider_id'          => $this->provider_id,
            'provider_first_name'  => $this->provider->first_name,
            'provider_last_name'   => $this->provider->last_name,
            'role'                 => $this->provider->role,
            'amount'               => $this->amount,
            'status'               => $this->status,
        ];
    }
}
