<?php

namespace Modules\Resturant\Http\Services;

use Illuminate\Support\Facades\DB;
use Modules\Resturant\Repositories\ResturantRepository;

class ResturantService
{
    public function __construct(private ResturantRepository $resturantRepository)
    {
    }

    public function getAll($request)
    {
        $page = (isset($request->page)) ? $request->page :  null;
        return $this->resturantRepository->getAll($page, $request->per_page);
    }

    public function find($resturantId)
    {
        return $this->resturantRepository->find($resturantId);
    }

    public function create($validatedData)
    {
        DB::beginTransaction();

        $resturant = $this->resturantRepository->create($validatedData);

        DB::commit();

        return $resturant;
    }

    public function update($validatedData, $resturantId)
    {
        $resturant = $this->resturantRepository->find($resturantId);

        DB::beginTransaction();

        $this->resturantRepository->update($validatedData, $resturant);

        DB::commit();

        return true;
    }

    public function delete($resturantId)
    {
        $resturant = $this->resturantRepository->find($resturantId);

        DB::beginTransaction();

        $this->resturantRepository->delete($resturant);

        DB::commit();

        return true;
    }

    public function getAllMedia($resturantId)
    {
        $resturant = $this->find($resturantId);
        $media = $resturant->getMedia('resturants-media');
        $thumbnails = $media->map(function ($item) {
            return [
                'id' => $item->id,
                'url' => $item->getFullUrl()
            ];
        });
        return $thumbnails;
    }

    public function createMedia($resturantId, $mediaFile)
    {
        $resturant = $this->find($resturantId);
        $resturant->addMedia($mediaFile)->toMediaCollection('resturants-media');
    }

    public function deleteMediaForId($resturantId, $mediaId)
    {
        $resturant = $this->find($resturantId);
        $mediaItem = $resturant->getMedia('resturants-media')->firstWhere('id', $mediaId);
        $mediaItem->delete();
        return true;
    }

    public function getTablesByResturant($resturantId)
    {
        DB::beginTransaction();
        $resturant = $this->resturantRepository->find($resturantId);
        $tables = $this->resturantRepository->getAllTablesByResturant($resturant);
        DB::commit();
        return $tables;
    }

    public function getMenusByResturant($resturantId)
    {
        DB::beginTransaction();
        $resturant = $this->resturantRepository->find($resturantId);
        $menus = $this->resturantRepository->getAllMenusByResturant($resturant);
        DB::commit();
        return $menus;
    }

    public function getAttributeTermsByResturant($resturantId)
    {
        DB::beginTransaction();
        $resturant = $this->resturantRepository->find($resturantId);
        $terms = $this->resturantRepository->getAttributesTermsByResturant($resturant);
        DB::commit();
        return $terms;
    }

    public function updateTermsByResturant($termIds, $resturantId)
    {
        DB::beginTransaction();
        $resturant = $this->resturantRepository->find($resturantId);
        $terms = $this->resturantRepository->updateTermsByResturant($resturant, $termIds);
        DB::commit();
        return $terms;
    }
}
