<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ApiController;
use App\Http\Requests\TripRequest;
use App\Http\Resources\TripResource;
use App\Services\TripService;

class TripController extends Controller
{
    public function __construct(private TripService $tripService)
    {
    }

    public function getAll()
    {
        $trips = $this->tripService->getAll();
        return $this->successResponse(
            $this->resource($trips, TripResource::class),
            'dataFetchedSuccessfully'
        );
    }

    public function find($tripId)
    {
        $trip = $this->tripService->find($tripId);

        return $this->successResponse(
            $this->resource($trip, TripResource::class),
            'dataFetchedSuccessfully'
        );
    }

    public function create(TripRequest $request)
    {
        $validatedData = $request->validated();
        $trip = $this->tripService->create($validatedData);
        if ($request->file('image') && $request->file('image')->isValid()) {
            $trip->addMedia($request->file('image'))->toMediaCollection('trip');
        }
        return $trip;
        
    }

    public function update(TripRequest $request, $tripId)
    {
        $validatedData = $request->validated();
        $this->tripService->update($validatedData, $tripId);

        return $this->successResponse(
            null,
            'dataUpdatedSuccessfully'
        );
    }

    public function delete($tripId)
    {
        $this->tripService->delete($tripId);

        return $this->successResponse(
            null,
            'dataDeletedSuccessfully'
        );
    }
}
