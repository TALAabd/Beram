<?php


namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Traits\ResourceHelper;

class CoreAttributeResource extends JsonResource
{

    use ResourceHelper;
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
            'getAllAttributeWithTerms' => $this->AttributeWithTermsResource(),
             default => $this->defaultResource(),
        };
    }

    public function defaultResource()
    {
        $locale = app()->getLocale();
        return [
            'lang'       => $locale,
            'id'         => $this->id,
            'name'       => $this->getTranslation('name', $locale) ?? '',
            'position'   => $this->position,
            'icon'       => $this->icon,
            'created_at' => $this->created_at
        ];
    }

    public function AttributeWithTermsResource()
    {
        $locale = app()->getLocale();
        return [
            'lang'       => $locale,
            'id'         => $this->id,
            'name'       => $this->getTranslation('name', $locale) ?? '',
            'position'   => $this->position,
            'icon'       => $this->icon,
            'created_at' => $this->created_at,
            'core_terms' => $this->resource($this->core_terms, CoreTermResource::class),
        ];
    }

}
