<?php

namespace Modules\Resturant\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Resturant\Http\Requests\MenuRequest;
use Modules\Resturant\Http\Resources\MealResource;
use Modules\Resturant\Http\Resources\MenuResource;
use Modules\Resturant\Http\Services\MenuService;

class MenuController extends Controller
{
    public function __construct(private MenuService $menuService)
    {
        $this->middleware('permission:menus_manager');
    }

    public function index()
    {
        $menuses = $this->menuService->getAll();
        return $this->successResponse(
            $this->resource($menuses, MenuResource::class),
            'dataFetchedSuccessfully'
        );
    }

    public function show($menuId)
    {
        $menu = $this->menuService->find($menuId);

        return $this->successResponse(
            $this->resource($menu, MenuResource::class),
            'dataFetchedSuccessfully'
        );
    }

    public function store(MenuRequest $request)
    {
        $validatedData = $request->validated();
        $menu = $this->menuService->create($validatedData);
        if ($request->file('image') && $request->file('image')->isValid()) {
            $menu->addMedia($request->file('image'))->toMediaCollection('thumbnail');
        }
        return $this->successResponse(
            $this->resource($menu, MenuResource::class),
            'dataAddedSuccessfully'
        );
    }

    public function update(MenuRequest $request, $menuId)
    {
        $validatedData = $request->validated();
        $this->menuService->update($validatedData, $menuId);

        return $this->successResponse(
            null,
            'dataUpdatedSuccessfully'
        );
    }

    public function destroy($menuId)
    {
        $this->menuService->delete($menuId);

        return $this->successResponse(
            null,
            'dataDeletedSuccessfully'
        );
    }

    public function getMeals($menuId)
    {
        $meals = $this->menuService->getMealsByMenu($menuId);
        return $this->successResponse(
            $this->resource($meals, MealResource::class),
            'dataFetchedSuccessfully'
        );
    }

    public function getMedia($menuId)
    {
        $media = $this->menuService->getAllMedia($menuId);
        return $this->successResponse(
            $media,
            'mediaFetchedSuccessfully'
        );
    }


    public function addMedia(Request $request, $menuId)
    {
        DB::beginTransaction();
        $this->menuService->addMedia($menuId, $request);
        DB::commit();
        return $this->successResponse(
            null,
            'dataAddedSuccessfully'
        );
    }

    public function deleteMedia($menuId, $mediaId)
    {
        $this->menuService->deleteMediaForId($menuId, $mediaId);
        return $this->successResponse(
            null,
            'dataDeletedSuccessfully'
        );
    }
}
