<?php

namespace Modules\Resturant\Http\Services;

use Illuminate\Support\Facades\DB;
use Modules\Resturant\Repositories\MenuRepository;

class MenuService
{
    public function __construct(private MenuRepository $menusRepository)
    {
    }

    public function getAll()
    {
        return $this->menusRepository->getAll();
    }

    public function find($menuId)
    {
        return $this->menusRepository->find($menuId);
    }

    public function create($validatedData)
    {
        DB::beginTransaction();

        $menus = $this->menusRepository->create($validatedData);

        DB::commit();

        return $menus;
    }

    public function update($validatedData, $menuId)
    {
        $menus = $this->menusRepository->find($menuId);

        DB::beginTransaction();

        $this->menusRepository->update($validatedData, $menus);

        DB::commit();

        return true;
    }

    public function delete($menuId)
    {
        $menus = $this->menusRepository->find($menuId);

        DB::beginTransaction();

        $this->menusRepository->delete($menus);

        DB::commit();

        return true;
    }

    public function getMealsByMenu($menuId)
    {
        $menu = $this->menusRepository->find($menuId);

        DB::beginTransaction();

        return $menu->meals;

        DB::commit();
    }

    public function getAllMedia($menuId)
    {
        return $this->menusRepository->getAllMedia($menuId);
    }


    public function addMedia($menuId, $request)
    {
        DB::beginTransaction();
        if ($request->file('media')->isValid()) {
            $this->menusRepository->createMedia($menuId, $request->file('media'));
        }
        DB::commit();
        return true;
    }


    public function deleteMediaForId($menuId, $mediaId)
    {
        DB::beginTransaction();
        $this->menusRepository->deleteMediaForId($menuId, $mediaId);
        DB::commit();
        return true;
    }
}
