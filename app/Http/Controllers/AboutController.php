<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ApiController;
use App\Http\Requests\AboutRequest;
use App\Http\Resources\AboutResource;
use App\Services\AboutService;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function __construct(private AboutService $aboutService)
    {
    }

    public function getAll(Request $request)
    {
        $abouts = $this->aboutService->getAll($request);
        return $this->successResponse(
            $this->resource($abouts, AboutResource::class),
            'dataFetchedSuccessfully'
        );
    }
    public function getPrivacy(Request $request)
    {
        $abouts = $this->aboutService->getPrivacy($request);
        return $this->successResponse(
            $this->resource($abouts, AboutResource::class),
            'dataFetchedSuccessfully'
        );
    }
    public function find($aboutId)
    {
        $about = $this->aboutService->find($aboutId);

        return $this->successResponse(
            $this->resource($about, AboutResource::class),
            'dataFetchedSuccessfully'
        );
    }

    public function create(AboutRequest $request)
    {
        $validatedData = $request->validated();
        $about = $this->aboutService->create($validatedData);

        return $this->successResponse(
            $this->resource($about, AboutResource::class),
            'dataAddedSuccessfully'
        );
    }

    public function update(AboutRequest $request, $aboutId)
    {
        $validatedData = $request->validated();
        $about = $this->aboutService->update($validatedData, $aboutId);

        if (isset($validatedData['image'])) {
            $about->clearMediaCollection('about');
            $about->addMedia($validatedData['image'])->toMediaCollection('about');
        }
        return $this->successResponse(
            null,
            'dataUpdatedSuccessfully'
        );
    }
    public function updatePrivacy(AboutRequest $request)
    {
        $validatedData = $request->validated();
        $about = $this->aboutService->updatePrivacy($validatedData);

        return $this->successResponse(
            null,
            'dataUpdatedSuccessfully'
        );
    }

    public function delete($aboutId)
    {
        $this->aboutService->delete($aboutId);

        return $this->successResponse(
            null,
            'dataDeletedSuccessfully'
        );
    }
}
