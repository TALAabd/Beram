<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ApiController;
use App\Http\Requests\FeatureRequest;
use App\Http\Resources\FeatureResource;
use App\Services\FeatureService;
use Illuminate\Http\Request;

class FeatureController extends Controller
{
    public function __construct(private FeatureService $featureService)
    {
        $this->middleware('permission:trip_features_manager',['only' => ['create','update','delete']]);
    }

    public function getAll(Request $request)
    {
        $features = $this->featureService->getAll($request);
        return $this->successResponse(
            $this->resource($features, FeatureResource::class),
            'dataFetchedSuccessfully'
        );
    }

    public function find($featureId, Request $request)
    {
        $feature = $this->featureService->find($featureId, $request);

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
