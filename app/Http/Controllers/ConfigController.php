<?php

namespace App\Http\Controllers;

use App\Http\Resources\ConfigResource;
use App\Http\Services\ConfigService;

class ConfigController extends Controller
{
    public function __construct(private ConfigService $configService)
    {
    }

    public function getAppHomePageData()
    {
        $homePageData = $this->configService->getAppHomePageData();
        return $this->successResponse(
            $this->resource($homePageData, ConfigResource::class),
            'dataFetchedSuccessfully'
        );
    }

    public function getControlPanelStatistics()
    {
        $statisticsData = $this->configService->getControlPanelStatistics();
        return $this->successResponse(
             $this->resource($statisticsData, ConfigResource::class),
            'dataFetchedSuccessfully'
        );
    }
}
