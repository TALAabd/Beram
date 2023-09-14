<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\CityResource;

class CountryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
         $actionMethod = $request->route()->getActionMethod();
        return match ($actionMethod) {
            'getAll'    => $this->setting($request),
            default     => $this->allData($request),
        };
    }
    
    public function setting($request)
    {
        
        $data = [];
        if (isset($this->cities)) {
            $data = $this->cities->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name
                ];
            });
        }

        $locale = app()->getLocale();
        return [
            'id'      => $this->id,
            'name'    => $this->getTranslation('name', $locale) ?? '',
            'city'    => $data
        ];
     }

    public function allData($request)
    {
        $locale = app()->getLocale();
        return [
            'id'         => $this->id,
            'name'       =>  $this->getTranslation('name', $locale) ?? '',
            'created_at' => $this->created_at
        ];
     }
}
