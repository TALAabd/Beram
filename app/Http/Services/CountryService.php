<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\DB;
use App\Repositories\CountryRepository;

class CountryService
{
    public function __construct(private CountryRepository $countryRepository)
    {
    }
  
    public function getAll()
    {
        return $this->countryRepository->getAll();
    }

    public function find($countryId)
    {
        return $this->countryRepository->find($countryId);
    }

    public function create($validatedData)
    {
        DB::beginTransaction();      

        $country = $this->countryRepository->create($validatedData);

        DB::commit();

        return $country;
    }

    public function update($validatedData, $countryId)
    {
        $country = $this->countryRepository->find($countryId);

        DB::beginTransaction();

        $this->countryRepository->update($validatedData, $country);

        DB::commit();

        return true;
    }

    public function delete($countryId)
    {
        $country = $this->countryRepository->find($countryId);

        DB::beginTransaction();

        $this->countryRepository->delete($country);

        DB::commit();

        return true;
    }

    public function getCitiesByCountry($countryId)
    {
        DB::beginTransaction();
        $country = $this->countryRepository->find($countryId);
        $cities = $this->countryRepository->getAllCitiesByCountry($country);
        DB::commit();
        return $cities;
    }
}
