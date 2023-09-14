<?php

namespace Modules\Resturant\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Hotels\Http\Resources\AttributeTermsResource;
use Modules\Resturant\Http\Requests\ResturantRequest;
use Modules\Resturant\Http\Resources\MenuResource;
use Modules\Resturant\Http\Resources\ResturantResource;
use Modules\Resturant\Http\Resources\TableResource;
use Modules\Resturant\Http\Services\ResturantService;

class ResturantController extends Controller
{
    public function __construct(private ResturantService $resturantService)
    {
        $this->middleware('permission:resturants_manager');
        $this->middleware('permission:tables_manager', ['only' => ['getTables']]);
        $this->middleware('permission:menus_manager', ['only' => ['getMenus']]);
    }

    public function index(Request $request)
    {
        $resturants = $this->resturantService->getAll($request);
        return $this->successResponse(
            $this->resource($resturants, ResturantResource::class),
            'dataFetchedSuccessfully'
        );
    }

    public function store(ResturantRequest $request)
    {
        $validatedData = $request->validated();
        $resturant = $this->resturantService->create($validatedData);
        if ($request->file('image') && $request->file('image')->isValid()) {
            $resturant->addMedia($request->file('image'))->toMediaCollection('thumbnail');
        }
        return $this->successResponse(
            null,
            'dataAddedSuccessfully'
        );
    }

    public function show($resturantId)
    {
        $resturant = $this->resturantService->find($resturantId);

        return $this->successResponse(
            $this->resource($resturant, ResturantResource::class),
            'dataFetchedSuccessfully'
        );
    }

    public function update(ResturantRequest $request, $resturantId)
    {
        $validatedData = $request->validated();
        $this->resturantService->update($validatedData, $resturantId);

        return $this->successResponse(
            null,
            'dataUpdatedSuccessfully'
        );
    }

    public function destroy($resturantId)
    {
        $this->resturantService->delete($resturantId);

        return $this->successResponse(
            null,
            'dataDeletedSuccessfully'
        );
    }

    public function updateStatus($id)
    {
        DB::beginTransaction();
        $hotel = $this->resturantService->find($id);
        $hotel->updateStatus();
        DB::commit();
        return $this->successResponse(
            $this->resource($hotel, ResturantResource::class),
            'dataUpdatedSuccessfully'
        );
    }


    public function updateFeatured($id)
    {
        DB::beginTransaction();
        $hotel = $this->resturantService->find($id);
        $hotel->updateFeatured();
        DB::commit();
        return $this->successResponse(
            $this->resource($hotel, ResturantResource::class),
            'dataUpdatedSuccessfully'
        );
    }


    public function getMedia($id)
    {
        $media = $this->resturantService->getAllMedia($id);
        return $this->successResponse(
            $media,
            'MediaFetchedSuccessfully'
        );
    }


    public function addMedia(Request $request, $id)
    {
        DB::beginTransaction();
        // Check if the request has file(s)
        if ($request->file('media') && $request->file('media')->isValid()) {
            $this->resturantService->createMedia($id, $request->file('media'));
        }
        DB::commit();
        return $this->successResponse(
            null,
            'dataAddedSuccessfully'
        );
    }


    public function deleteMedia($resturantId, $mediaId)
    {
        $this->resturantService->deleteMediaForId($resturantId, $mediaId);
        return $this->successResponse(
            null,
            'dataDeletedSuccessfully'
        );
    }


    public function getTables($resturantId)
    {
        $tables = $this->resturantService->getTablesByResturant($resturantId);
        return $this->successResponse(
            $this->resource($tables, TableResource::class),
            'dataFetchedSuccessfully'
        );
    }

    public function getMenus($resturantId)
    {
        $menus = $this->resturantService->getMenusByResturant($resturantId);
        return $this->successResponse(
            $this->resource($menus, MenuResource::class),
            'dataFetchedSuccessfully'
        );
    }

    public function getAttributeTerms($resturantId)
    {
        $attributeterms = $this->resturantService->getAttributeTermsByResturant($resturantId);
        return $this->successResponse(
            $this->resource($attributeterms, AttributeTermsResource::class),
            'dataFetchedSuccessfully'
        );
    }


    public function updateTerms(Request $request, $resturantId)
    {
        $this->resturantService->updateTermsByResturant($request->termsIds, $resturantId);
        return $this->successResponse(
            null,
            'dataUpdatedSuccessfully'
        );
    }
}
