<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AboutResource extends JsonResource
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
            'getPrivacy' => $this->getPrivacy($request),
            'getTerms'   => $this->getTerms($request),
            default      => $this->allData($request),
        };
    }
    public function allData($request)
    {
        return [
            'id'         => $this->id ?? null,
            'title'      => $this->title ?? null,
            'content'    => $this->content ?? null,
            'image'      => $this->media_urls ?? null,
        ];
    }
    public function getPrivacy($request)
    {
        return [
            'privacy' => $this->privacy ?? null,
        ];
    }
    public function getTerms($request)
    {
        return [
            'terms' => $this->terms ?? null,
        ];
    }
}
