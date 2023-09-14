<?php


namespace Modules\Authentication\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'name' => $this->name,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'address' => $this->address,
            'phone' => $this->phone,
            'birthday' => $this->birthday,
            'city' => $this->city,
            'state' => $this->state,
            'country' => $this->country,
            // 'zip_code' => $this->zip_code,
            'last_login_at' => $this->last_login_at,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'roles' => $this->roles,
            'role' => $this->role,
            'media_urls' => $this->media_urls,
        ];
    }
}

