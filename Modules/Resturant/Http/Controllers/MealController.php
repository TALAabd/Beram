<?php


namespace Modules\Resturant\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Resturant\Http\Requests\MealRequest;
use Modules\Resturant\Http\Resources\MealResource;
use Modules\Resturant\Http\Services\MealService;

class MealController extends Controller
{
    public function __construct(private MealService $mealService)
    {
        $this->middleware('permission:meals_manager');
    }

    public function index()
    {
        $meals = $this->mealService->getAll();
        return $this->successResponse(
            $this->resource($meals, MealResource::class),
            'dataFetchedSuccessfully'
        );
    }

    public function show($mealId)
    {
        $meal = $this->mealService->find($mealId);

        return $this->successResponse(
            $this->resource($meal, MealResource::class),
            'dataFetchedSuccessfully'
        );
    }

    public function store(MealRequest $request)
    {
        $validatedData = $request->validated();
        $meal = $this->mealService->create($validatedData);
        if ($request->file('image') && $request->file('image')->isValid()) {
            $meal->addMedia($request->file('image'))->toMediaCollection('thumbnail');
        }
        return $this->successResponse(
            $this->resource($meal, MealResource::class),
            'dataAddedSuccessfully'
        );
    }

    public function update(MealRequest $request, $mealId)
    {
        $validatedData = $request->validated();
        $this->mealService->update($validatedData, $mealId);

        return $this->successResponse(
            null,
            'dataUpdatedSuccessfully'
        );
    }

    public function destroy($mealId)
    {
        $this->mealService->delete($mealId);

        return $this->successResponse(
            null,
            'dataDeletedSuccessfully'
        );
    }

    public function getMedia($menuId)
    {
        $media = $this->mealService->getAllMedia($menuId);
        return $this->successResponse(
            $media,
            'mediaFetchedSuccessfully'
        );
    }


    public function addMedia(Request $request, $menuId)
    {
        DB::beginTransaction();
        $this->mealService->addMedia($menuId, $request);
        DB::commit();
        return $this->successResponse(
            null,
            'dataAddedSuccessfully'
        );
    }

    public function deleteMedia($menuId, $mediaId)
    {
        $this->mealService->deleteMediaForId($menuId, $mediaId);
        return $this->successResponse(
            null,
            'dataDeletedSuccessfully'
        );
    }
}
