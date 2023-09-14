<?php

namespace App\Http\Controllers;

use App\Http\Requests\BusinessSettingRequest;
use App\Http\Resources\BusinessSettingResource;
use App\Http\Services\BusinessSettingService;

class BusinessSettingController extends Controller
{
    public function __construct(private BusinessSettingService $business_settingService)
    {
    }

    public function getAll()
    {
        $business_settings = $this->business_settingService->getAll();
        return $this->successResponse(
            $this->resource($business_settings, BusinessSettingResource::class),
            'dataFetchedSuccessfully'
        );
    }

    public function find($business_settingId)
    {
        $business_setting = $this->business_settingService->find($business_settingId);

        return $this->successResponse(
            $this->resource($business_setting, BusinessSettingResource::class),
            'dataFetchedSuccessfully'
        );
    }

    public function create(BusinessSettingRequest $request)
    {
        $validatedData = $request->validated();
        $business_setting = $this->business_settingService->create($validatedData);
        if (isset($request->images)) {
            foreach ($request->images as $image) {
                $business_setting->addMedia($image['name'])->toMediaCollection($business_setting->type);
            }
        }
        return $this->successResponse(
            $this->resource($business_setting, BusinessSettingResource::class),
            'dataAddedSuccessfully'
        );
    }

    public function update(BusinessSettingRequest $request, $business_settingId)
    {
        $validatedData = $request->validated();
        $this->business_settingService->update($validatedData, $business_settingId);

        return $this->successResponse(
            null,
            'dataUpdatedSuccessfully'
        );
    }

    public function delete($business_settingId)
    {
        $this->business_settingService->delete($business_settingId);

        return $this->successResponse(
            null,
            'dataDeletedSuccessfully'
        );
    }
}
