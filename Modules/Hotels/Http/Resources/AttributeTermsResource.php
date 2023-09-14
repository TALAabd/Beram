<?php


namespace Modules\Hotels\Http\Resources;

use App\Http\Resources\CoreTermResource;
use Illuminate\Http\Resources\Json\JsonResource;

class AttributeTermsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $locale = app()->getLocale();
        return [
            'id' => $this->id,
            'name' => $this->getTranslation('name', $locale) ?? '',
            'position' => $this->position,
            'service' => $this->service,
            'created_at' => $this->created_at,
            'core_terms' => CoreTermResource::collection($this->core_terms),
        ];
    }
}
