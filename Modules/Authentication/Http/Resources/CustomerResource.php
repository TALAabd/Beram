<?php

namespace Modules\Authentication\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
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
            'token' =>$this->token,
            'id' => $this->id,
            // 'name' => $this->name,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,
            // 'gender' => $this->gender,
            // 'address' => $this->address,
            // 'birthday' => $this->birthday,
            // 'city' => $this->city,
            // 'state' => $this->state,
            // 'country' => $this->country,
            // 'zip_code' => $this->zip_code,
            // 'last_login_at' => $this->last_login_at,
            // 'bio' => $this->bio,
            'nationality'=>$this->nationality,
        
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
