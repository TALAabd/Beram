<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ApiController;
use App\Http\Requests\FeatureRequest;
use App\Http\Resources\FeatureResource;
use App\Services\FeatureService;

class FeatureController extends Controller
{
    public function __construct(private FeatureService $featureService)
    {
    }

    public function getAll()
    {
        $features = $this->featureService->getAll();
        return $this->successResponse(
            $this->resource($features, FeatureResource::class),
            'dataFetchedSuccessfully'
        );
    }

    public function find($featureId)
    {
        $feature = $this->featureService->find($featureId);

        return $this->successResponse(
            $this->resource($feature, FeatureResource::class),
            'dataFetchedSuccessfully'
        );
    }

    public function create(FeatureRequest $request)
    {
        $validatedData = $request->validated();
        $feature = $this->featureService->create($validatedData);

        return $this->successResponse(
            $this->resource($feature, FeatureResource::class),
            'dataAddedSuccessfully'
        );
    }

    public function update(FeatureRequest $request, $featureId)
    {
        $validatedData = $request->validated();
        $this->featureService->update($validatedData, $featureId);

        return $this->successResponse(
            null,
            'dataUpdatedSuccessfully'
        );
    }

    public function delete($featureId)
    {
        $this->featureService->delete($featureId);

        return $this->successResponse(
            null,
            'dataDeletedSuccessfully'
        );
    }
}
