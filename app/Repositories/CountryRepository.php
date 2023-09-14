<?php

namespace App\Repositories;

use App\Models\Country;
use App\Traits\ModelHelper;

class CountryRepository
{
    use ModelHelper;

    public function getAll()
    {
        return Country::all();
    }

    public function find($countryId)
    {   
        $country = $this->findByIdOrFail(Country::class,'country', $countryId);
        // $country = Country::where('id', $countryId)->first();
        return $country;
    }

    public function create($validatedData)
    {
        return Country::create($validatedData);
    }

    public function update($validatedData, Country $country)
    {
        return $country->update($validatedData);
    }

    public function delete(Country $country)
    {
        return $country->delete();
    }

    public function getAllCitiesByCountry(Country $country)
    {
         return $country->cities;
        //  dd($country->cities);
    }

}
