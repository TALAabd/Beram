<?php

namespace Modules\Resturant\Repositories;

use Modules\Resturant\Models\Table;
use App\Traits\ModelHelper;

class TableRepository
{
    use ModelHelper;

    public function getAll()
    {
        return Table::all();
    }

    public function find($tableId)
    {
        return $this->findByIdOrFail(Table::class,'table', $tableId);
    }

    public function create($validatedData)
    {
        return Table::create($validatedData);
    }

    public function update($validatedData, Table $table)
    {
        return $table->update($validatedData);
    }

    public function delete(Table $table)
    {
        return $table->delete();
    }

    public function createMedia($tableId, $mediaFile)
    {
        $table = $this->find($tableId);
        $table->addMedia($mediaFile)->toMediaCollection('tables-media');
    }

    public function getAllMedia($tableId)
    {
        $table = $this->find($tableId);
        $media = $table->getMedia('tables-media');
        $thumbnails = $media->map(function ($item) {
            return [
                'id' => $item->id,
                'url' => $item->getFullUrl()
            ];
        });
        return $thumbnails;
    }

    public function deleteMediaForId($tableId, $mediaId)
    {
        $table = $this->find($tableId);
        $mediaItem = $table->getMedia('tables-media')->firstWhere('id', $mediaId);
        $mediaItem->delete();
    }
}
