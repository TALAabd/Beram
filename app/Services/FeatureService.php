<?php

namespace App\Services;

use App\Models\Feature;
use Illuminate\Support\Facades\DB;
use App\Repositories\FeatureRepository;

class FeatureService
{
    // public function __construct(private FeatureRepository $featureRepository)
    // {
    // }

    public function getAll()
    {
        return Feature::get();
    }

    public function find($featureId)
    {
        return Feature::find($featureId);
    }

    public function create($validatedData)
    {
        DB::beginTransaction();
        $feature = new Feature();
        $feature->setTranslation('name','en', $validatedData['name']);
        $feature->setTranslation('name','ar', $validatedData['name']);
        $feature->save();
        DB::commit();

        return $feature;
    }

    public function update($validatedData, $featureId)
    {
        $feature = Feature::find($featureId);
        // $lang = app()->getLocale();
        DB::beginTransaction();
        $feature->setTranslation('name', $validatedData['lang'], $validatedData['name']);
        // $feature->setTranslation('name', $lang, $validatedData['name']);
        $feature->save();

        DB::commit();

        return true;
    }

    public function delete($featureId)
    {
        $feature = Feature::find($featureId);

        // DB::beginTransaction();

        $feature->delete();

        // DB::commit();

        return true;
    }
}
