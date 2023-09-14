<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\DB;
use App\Repositories\CityRepository;

class CityService
{
    public function __construct(private CityRepository $cityRepository)
    {
    }

    public function getAll()
    {
        return $this->cityRepository->getAll();
    }

    public function find($cityId)
    {
        return $this->cityRepository->find($cityId);
    }

    public function create($validatedData)
    {
        DB::beginTransaction();

        $city = $this->cityRepository->create($validatedData);

        DB::commit();

        return $city;
    }

    public function update($validatedData, $cityId)
    {
        $city = $this->cityRepository->find($cityId);

        DB::beginTransaction();

        $this->cityRepository->update($validatedData, $city);

        DB::commit();

        return true;
    }

    public function delete($cityId)
    {
        $city = $this->cityRepository->find($cityId);

        DB::beginTransaction();

        $this->cityRepository->delete($city);

        DB::commit();

        return true;
    }

    public function createMedia($cityId, $mediaFile)
    {
        DB::beginTransaction();

        $city = $this->find($cityId);
        $city->addMedia($mediaFile)->toMediaCollection('thumbnail');

        DB::commit();
    }

    public function deleteMediaForId($cityId, $mediaId)
    {
        DB::beginTransaction();

        $city = $this->find($cityId);
        $mediaItem = $city->getMedia('thumbnail')->firstWhere('id', $mediaId);
        $mediaItem->delete();

        DB::commit();

        return true;
    }

    public function getCitiesBestLocation()
    {
        return $this->cityRepository->getCitiesBest();
    }

    public function count(): int
    {
        return DB::table('cities')->where('best_location','=',1)->count();
    }
    
}
