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
        $locale = app()->getLocale();
        if ($this->banner_type == 'section_2') {
            $media_urls =  $this->getMedia($this->banner_type)->map(function ($media) {
                return $media->getFullUrl();
            })->toArray();

            return [
                'id'          => $this->id,
                'url_link'    => $this->url_link,
                'media_urls'  => array_slice($media_urls, 0, 3),
                'description' => $this->description ?? '',
              
            ];
        } else {
            $media_urls =  $this->getMedia($this->banner_type)->map(function ($media) {
                return $media->getFullUrl();
            });

            return [
                'id'          => $this->id,
                'url_link'    => $this->url_link,
                'media_urls'  => $media_urls[0],
            ];
        }

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
            'provider_id' => $this->provider_id,
            'description' => $this->description ?? '',
        ];
    }
}
