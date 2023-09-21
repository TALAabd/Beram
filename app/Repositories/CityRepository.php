<?php

namespace App\Repositories;

use App\Models\City;
use App\Traits\ModelHelper;

class CityRepository
{
    use ModelHelper;

    public function getAll()
    {
        return City::orderBy('id','Desc')->get();
    }

    public function getCitiesBest()
    {
        return City::where('best_location','=',1)->orderBy('id','Desc')->get();
    }

    public function find($cityId)
    {
        return $this->findByIdOrFail(City::class,'city', $cityId);
    }

    public function create($validatedData)
    {
        return City::create($validatedData);
    }

    public function update($validatedData, City $city)
    {
        return $city->update($validatedData);
    }

    public function delete(City $city)
    {
        return $city->delete();
    }
}
