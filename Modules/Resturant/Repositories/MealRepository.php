<?php

namespace Modules\Resturant\Repositories;

use Modules\Resturant\Models\Meal;
use App\Traits\ModelHelper;

class MealRepository
{
    use ModelHelper;

    public function getAll()
    {
        return Meal::all();
    }

    public function find($mealId)
    {
        return $this->findByIdOrFail(Meal::class,'meal', $mealId);
    }

    public function create($validatedData)
    {
        return Meal::create($validatedData);
    }

    public function update($validatedData, Meal $meal)
    {
        return $meal->update($validatedData);
    }

    public function delete(Meal $meal)
    {
        return $meal->delete();
    }

    public function createMedia($menuId, $mediaFile)
    {
        $table = $this->find($menuId);
        $table->addMedia($mediaFile)->toMediaCollection('meals-media');
    }

    public function getAllMedia($menuId)
    {
        $table = $this->find($menuId);
        $media = $table->getMedia('meals-media');
        $thumbnails = $media->map(function ($item) {
            return [
                'id' => $item->id,
                'url' => $item->getFullUrl()
            ];
        });
        return $thumbnails;
    }

    public function deleteMediaForId($menuId, $mediaId)
    {
        $table = $this->find($menuId);
        $mediaItem = $table->getMedia('meals-media')->firstWhere('id', $mediaId);
        $mediaItem->delete();
    }
}
