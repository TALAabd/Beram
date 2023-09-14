<?php

namespace App\Http\Controllers;

use App\Http\Services\BannerService;
use App\Http\Resources\BannerResource;
use App\Http\Requests\BannerRequest;

class BannerController extends Controller
{
    public function __construct(private BannerService $bannerService)
    {
        $this->middleware('permission:banners_manager', ['only' => ['find', 'create', 'update', 'deleteMedia','delete']]);
    }

    public function getAll()
    {
        $banners = $this->bannerService->getAll();
        return $this->successResponse(
            $this->resource($banners, BannerResource::class),
            'dataFetchedSuccessfully'
        );
    }

    public function find($bannerId)
    {
        $banner = $this->bannerService->find($bannerId);

        return $this->successResponse(
            $this->resource($banner, BannerResource::class),
            'dataFetchedSuccessfully'
        );
    }

    public function create(BannerRequest $request)
    {
        $validatedData = $request->validated();
        $banner = $this->bannerService->create($validatedData);
        foreach ($request->images as $image) {
                $banner->addMedia($image['name'])->toMediaCollection($banner->banner_type);
        }
        return $this->successResponse(
            $this->resource($banner, BannerResource::class),
            'dataAddedSuccessfully'
        );
    }

    public function update(BannerRequest $request, $bannerId)
    {
        $validatedData = $request->validated();
        $banner= $this->bannerService->update($validatedData, $bannerId);
        if(isset($request->images))
        {
            foreach ($request->images as $image) {
                $banner->addMedia($image['name'])->toMediaCollection($banner->banner_type);
            }
        }
        return $this->successResponse(
            null,
            'dataUpdatedSuccessfully'
        );
    }

    public function deleteMedia($bannerId, $mediaId)
    {
        $this->bannerService->deleteMediaForId($bannerId, $mediaId);
        return $this->successResponse(
            null,
            'dataDeletedSuccessfully'
        );
    }

    public function getBannersSection()
    {
        $bannersSection=$this->bannerService->getBannersSection();
        return $this->successResponse(
            $bannersSection,
            'dataFetchedSuccessfully'
        );
    }

    public function delete($bannerId)
    {
        $this->bannerService->delete($bannerId);

        return $this->successResponse(
            null,
            'dataDeletedSuccessfully'
        );
    }
}
