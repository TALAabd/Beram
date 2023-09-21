<?php

namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;

class BannerResource extends JsonResource
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
            'getAppHomePageData' => $this->getAppHomePage(),
            default              => $this->allData(),
        };
    }
    public function getAppHomePage()
    {
        $media_urls =  $this->getMedia($this->banner_type)->map(function ($media) {
            return $media->getFullUrl();
        });

        $locale = app()->getLocale();
        return [
            'id'          => $this->id,
            'url_link'    => $this->url_link,
            'media_urls'  => $media_urls[0] ? $media_urls[0] : null,
            'description' => $this->description ?? '',
        ];
    }
    public function allData()
    {
        $locale = app()->getLocale();
        return [
            'id'          => $this->id,
            'banner_type' => $this->banner_type,
            'title'       => $this->getTranslation('title', $locale) ?? '',
            'url_link'    => $this->url_link,
            'service'     => $this->service,
            'created_at'  => $this->created_at,
            'media_urls'  => $this->media_urls,
            'provider_id' => $this->provider_id
        ];
    }

}
