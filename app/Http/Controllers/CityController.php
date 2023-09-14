<?php

namespace App\Http\Controllers;

use App\Http\Requests\CityRequest;
use App\Http\Resources\CityResource;
use App\Http\Services\CityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CityController extends Controller
{
    public function __construct(private CityService $cityService)
    {
        $this->middleware('permission:cities_manager', ['only' => ['find', 'create', 'update', 'delete','addMedia','deleteMedia','updateBestLocation']])->except('getBestLocation');
    }

    public function getAll()
    {
        $cities = $this->cityService->getAll();
        return $this->successResponse(
            $this->resource($cities, CityResource::class),
            'dataFetchedSuccessfully'
        );
    }

    public function find($cityId)
    {
        $city = $this->cityService->find($cityId);

        return $this->successResponse(
            $this->resource($city, CityResource::class),
            'dataFetchedSuccessfully'
        );
    }

    public function create(CityRequest $request)
    {
        $validatedData = $request->validated();
        $city = $this->cityService->create($validatedData);
        // if ($request->file('image') && $request->file('image')->isValid()) {
        //     $city->addMedia($request->file('image'))->toMediaCollection('thumbnail');
        // }
        return $this->successResponse(
            $this->resource($city, CityResource::class),
            'dataAddedSuccessfully'
        );
    }

    public function update(CityRequest $request, $cityId)
    {
        $validatedData = $request->validated();
        $this->cityService->update($validatedData, $cityId);

        return $this->successResponse(
            null,
            'dataUpdatedSuccessfully'
        );
    }

    public function delete($cityId)
    {
        $this->cityService->delete($cityId);

        return $this->successResponse(
            null,
            'dataDeletedSuccessfully'
        );
    }

    public function addMedia(Request $request, $id)
    {
        DB::beginTransaction();
        // Check if the request has file(s)
        if ($request->file('media') && $request->file('media')->isValid()) {
            $this->cityService->createMedia($id, $request->file('media'));
        }
        DB::commit();
        return $this->successResponse(
            null,
            'dataAddedSuccessfully'
        );
    }

    public function deleteMedia($hotelId, $mediaId)
    {
        $this->cityService->deleteMediaForId($hotelId, $mediaId);
        return $this->successResponse(
            null,
            'dataDeletedSuccessfully'
        );
    }

    public function updateBestLocation($id)
    {
        DB::beginTransaction();
        $city = $this->cityService->find($id);
        $city->bestLocationStatus();
        DB::commit();
        return $this->successResponse(
            $this->resource($city, CityResource::class),
            'dataUpdatedSuccessfully'
        );
    }

    public function getBestLocation()
    {
        $cities = $this->cityService->getCitiesBestLocation();
        return $this->successResponse(
            $this->resource($cities, CityResource::class),
            'dataFetchedSuccessfully'
        );
    }

}
