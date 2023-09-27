<?php

namespace App\Repositories;

use App\RepositoryInterface\CoreAttributeRepositoryInterface;
use App\Models\CoreAttribute;
use Illuminate\Support\Str;
use App\Traits\ModelHelper;

class CoreAttributeRepository implements CoreAttributeRepositoryInterface
{

    use ModelHelper;
    public function getAttributes()
    {
        if (isset(request()->filter['service'])) {
            return CoreAttribute::where('service', request()->filter['service'])->get();
        }
        return CoreAttribute::get();
    }

    public function createAttribute($attributes)
    {
        $lang = app()->getLocale();
        $coreAttribute = new CoreAttribute;
        $coreAttribute->setTranslation('name', $lang, $attributes['name']);
        $coreAttribute->position = $attributes['position'];
        $coreAttribute->slug = Str::slug($attributes['name'] . '-' . Str::random(6));
        $coreAttribute->service = $attributes['service'];
        if (isset($attributes['icon']))
            $coreAttribute->icon = $attributes['icon'];
        $coreAttribute->save();
        return $coreAttribute;
    }

    public function updateAttribute(CoreAttribute $coreAttribute, $attributes)
    {
        $lang = app()->getLocale();
        $coreAttribute->setTranslation('name', $lang, $attributes['name']);
        $coreAttribute->slug = Str::slug($attributes['name'] . '-' . Str::random(6));
        $coreAttribute->position = $attributes['position'];
        $coreAttribute->service = $attributes['service'] ?? $coreAttribute->service;
        $coreAttribute->icon = $attributes['icon'] ?? $coreAttribute->icon;
        $coreAttribute->save();
        return $coreAttribute;
    }


    public function findAttributeById($CoreAttributeId)
    {
        return $this->findByIdOrFail(CoreAttribute::class, 'coreAttribute', $CoreAttributeId);
    }


    public function deleteAttribute($CoreAttributeId)
    {
        $CoreAttribute = $this->findAttributeById($CoreAttributeId);
        $CoreAttribute->delete();
        return true;
    }


    public function getAllTermsByAttribute(CoreAttribute $attribute)
    {
        return $attribute->core_terms;
    }
}
