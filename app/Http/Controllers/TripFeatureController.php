<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ApiController;
use App\Http\Requests\TripFeatureRequest;
use App\Http\Resources\TripFeatureResource;
use App\Services\TripFeatureService;

class TripFeatureController extends Controller
{
    public function __construct(private TripFeatureService $trip_featureService)
    {
    }

    public function getAll()
    {
        $trip_features = $this->trip_featureService->getAll();
        return $this->successResponse(
            $this->resource($trip_features, TripFeatureResource::class),
            'dataFetchedSuccessfully'
        );
    }

    public function find($trip_featureId)
    {
        $trip_feature = $this->trip_featureService->find($trip_featureId);

        return $this->successResponse(
            $this->resource($trip_feature, TripFeatureResource::class),
            'dataFetchedSuccessfully'
        );
    }

    public function create(TripFeatureRequest $request)
    {
        $validatedData = $request->validated();
        $trip_feature = $this->trip_featureService->create($validatedData);

        return $this->successResponse(
            $this->resource($trip_feature, TripFeatureResource::class),
            'dataAddedSuccessfully'
        );
    }

    public function update(TripFeatureRequest $request, $trip_featureId)
    {
        $validatedData = $request->validated();
        $this->trip_featureService->update($validatedData, $trip_featureId);

        return $this->successResponse(
            null,
            'dataUpdatedSuccessfully'
        );
    }

    public function delete($trip_featureId)
    {
        $this->trip_featureService->delete($trip_featureId);

        return $this->successResponse(
            null,
            'dataDeletedSuccessfully'
        );
    }
}
