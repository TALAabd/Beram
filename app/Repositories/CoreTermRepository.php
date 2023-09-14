<?php

namespace App\Repositories;

use App\RepositoryInterface\CoreTermRepositoryInterface;
use App\Models\CoreTerm;
use App\Traits\ModelHelper;
use Illuminate\Support\Str;

class CoreTermRepository implements CoreTermRepositoryInterface
{
    use ModelHelper;

    public function find($core_termId)
    {
        return $this->findByIdOrFail(CoreTerm::class,'core_term', $core_termId);
    }

    public function create($validatedData)
    {
        $lang = app()->getLocale();
        $coreTerm = new CoreTerm;
        $coreTerm->setTranslation('name', $lang, $validatedData['name']);
        $coreTerm->setTranslation('content', $lang, $validatedData['content']);
        $coreTerm->slug = Str::slug($validatedData['name'] . '-' . Str::random(6));
        $coreTerm->core_attribute_id = $validatedData['core_attribute_id'];
        $coreTerm->save();
        return $coreTerm;
    }

    public function update(CoreTerm $core_term,$validatedData)
    {
        $lang = app()->getLocale();
        $core_term->setTranslation('name', $lang, $validatedData['name']);
        $core_term->setTranslation('content', $lang, $validatedData['content']);
        $core_term->slug = Str::slug($validatedData['name'] . '-' . Str::random(6));
        $core_term->core_attribute_id = $validatedData['core_attribute_id'] ?? $core_term->core_attribute_id;
        $core_term->save();
        return $core_term;
    }

    public function delete(CoreTerm $core_term)
    {
        return $core_term->delete();
    }

}
