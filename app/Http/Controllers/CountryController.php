<?php

namespace App\Http\Controllers;

use App\Http\Requests\CountryRequest;
use App\Http\Resources\CountryResource;
use App\Http\Resources\CityResource;
use App\Http\Services\CountryService;

class CountryController extends Controller
{
    public function __construct(private CountryService $countryService)
    {
        $this->middleware('permission:countries_manager', ['only' => ['getAll', 'find','create', 'update', 'delete','getAllCitiesByCountry']]);
        $this->middleware('permission:cities_manager', ['only' => ['getAllCitiesByCountry']]);
    }

    public function getAll()
    {
        $countries = $this->countryService->getAll();
        return $this->successResponse(
            $this->resource($countries, CountryResource::class),
            'dataFetchedSuccessfully'
        );
    }

    public function find($countryId)
    {
        $country = $this->countryService->find($countryId);
        return $this->successResponse(
            $this->resource($country, CountryResource::class),
            'dataFetchedSuccessfully'
        );
    }

    public function create(CountryRequest $request)
    {
        $validatedData = $request->validated();
        $country = $this->countryService->create($validatedData);

        return $this->successResponse(
            $this->resource($country, CountryResource::class),
            'dataAddedSuccessfully'
        );
    }

    public function update(CountryRequest $request, $countryId)
    {
        $validatedData = $request->validated();
        $this->countryService->update($validatedData, $countryId);

        return $this->successResponse(
            null,
            'dataUpdatedSuccessfully'
        );
    }

    public function delete($countryId)
    {
        $this->countryService->delete($countryId);

        return $this->successResponse(
            null,
            'dataDeletedSuccessfully'
        );
    }

    public function getAllCitiesByCountry($countryId)
    {
        $cities = $this->countryService->getCitiesByCountry($countryId);
        return $this->successResponse(
            $this->resource($cities, CityResource::class),
            'dataFetchedSuccessfully'
        );
    }
}
