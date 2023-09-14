<?php

namespace Modules\Resturant\Http\Services;

use Illuminate\Support\Facades\DB;
use Modules\Resturant\Repositories\MealRepository;

class MealService
{
    public function __construct(private MealRepository $mealRepository)
    {
    }

    public function getAll()
    {
        return $this->mealRepository->getAll();
    }

    public function find($mealId)
    {
        return $this->mealRepository->find($mealId);
    }

    public function create($validatedData)
    {
        DB::beginTransaction();

        $meal = $this->mealRepository->create($validatedData);

        DB::commit();

        return $meal;
    }

    public function update($validatedData, $mealId)
    {
        $meal = $this->mealRepository->find($mealId);

        DB::beginTransaction();

        $this->mealRepository->update($validatedData, $meal);

        DB::commit();

        return true;
    }

    public function delete($mealId)
    {
        $meal = $this->mealRepository->find($mealId);

        DB::beginTransaction();

        $this->mealRepository->delete($meal);

        DB::commit();

        return true;
    }

    public function getAllMedia($mealId)
    {
        return $this->mealRepository->getAllMedia($mealId);
    }


    public function addMedia($mealId, $request)
    {
        DB::beginTransaction();
        if ($request->file('media')->isValid()) {
            $this->mealRepository->createMedia($mealId, $request->file('media'));
        }
        DB::commit();
        return true;
    }


    public function deleteMediaForId($mealId, $mediaId)
    {
        DB::beginTransaction();
        $this->mealRepository->deleteMediaForId($mealId, $mediaId);
        DB::commit();
        return true;
    }
}
