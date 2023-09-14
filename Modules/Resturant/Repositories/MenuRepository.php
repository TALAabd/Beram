<?php

namespace Modules\Resturant\Repositories;

use Modules\Resturant\Models\Menu;
use App\Traits\ModelHelper;

class MenuRepository
{
    use ModelHelper;

    public function getAll()
    {
        return Menu::all();
    }

    public function find($menuId)
    {
        return $this->findByIdOrFail(Menu::class,'menu', $menuId);
    }

    public function create($validatedData)
    {
        return Menu::create($validatedData);
    }

    public function update($validatedData, Menu $menu)
    {
        return $menu->update($validatedData);
    }

    public function delete(Menu $menu)
    {
        return $menu->delete();
    }

    public function createMedia($menuId, $mediaFile)
    {
        $table = $this->find($menuId);
        $table->addMedia($mediaFile)->toMediaCollection('menus-media');
    }

    public function getAllMedia($menuId)
    {
        $table = $this->find($menuId);
        $media = $table->getMedia('menus-media');
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
        $mediaItem = $table->getMedia('menus-media')->firstWhere('id', $mediaId);
        $mediaItem->delete();
    }
}
