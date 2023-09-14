<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ApiController;
use App\Http\Requests\SettingRequest;
use App\Http\Resources\SettingResource;
use App\Http\Resources\ConfigResource;
use App\Services\SettingService;

class SettingController extends Controller
{
    public function __construct(private SettingService $settingService)
    {
    }

    public function getAll()
    {
        $settings = $this->settingService->getAll();
        return $this->successResponse(
            $this->resource($settings, ConfigResource::class),
            'dataFetchedSuccessfully'
        );
    }

    public function find($settingId)
    {
        $setting = $this->settingService->find($settingId);

        return $this->successResponse(
            $this->resource($setting, SettingResource::class),
            'dataFetchedSuccessfully'
        );
    }

    public function create(SettingRequest $request)
    {
        $validatedData = $request->validated();
        $setting = $this->settingService->create($validatedData);

        return $this->successResponse(
            $this->resource($setting, SettingResource::class),
            'dataAddedSuccessfully'
        );
    }

    public function update(SettingRequest $request, $settingId)
    {
        $validatedData = $request->validated();
        $this->settingService->update($validatedData, $settingId);

        return $this->successResponse(
            null,
            'dataUpdatedSuccessfully'
        );
    }

    public function delete($settingId)
    {
        $this->settingService->delete($settingId);

        return $this->successResponse(
            null,
            'dataDeletedSuccessfully'
        );
    }
}
