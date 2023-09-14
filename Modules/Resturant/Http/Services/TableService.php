<?php

namespace Modules\Resturant\Http\Services;

use Illuminate\Support\Facades\DB;
use Modules\Resturant\Repositories\TableRepository;
use Modules\Resturant\Repositories\ResturantRepository;

class TableService
{
    public function __construct(
        private TableRepository $tableRepository,
        private ResturantRepository $resturantRepository
    ) {
    }


    public function find($tableId)
    {
        return $this->tableRepository->find($tableId);
    }

    public function create($validatedData)
    {
        DB::beginTransaction();
        $resturant = $this->resturantRepository->find($validatedData['resturant_id']);
        if (isset($resturant)) {
            $table = $this->tableRepository->create($validatedData);
        }
        DB::commit();

        return $table;
    }

    public function update($validatedData, $tableId)
    {
        $table = $this->tableRepository->find($tableId);

        DB::beginTransaction();

        $this->tableRepository->update($validatedData, $table);

        DB::commit();

        return true;
    }

    public function delete($tableId)
    {
        $table = $this->tableRepository->find($tableId);

        DB::beginTransaction();

        $this->tableRepository->delete($table);

        DB::commit();

        return true;
    }

    public function updateActiveStatus($tableId)
    {
        DB::beginTransaction();
        $room = $this->tableRepository->find($tableId);
        if (isset($room))
            $room->updateStatus();
        DB::commit();
        return $room;
    }

    public function getAllMedia($tableId)
    {
        return $this->tableRepository->getAllMedia($tableId);
    }


    public function addMedia($tableId, $request)
    {
        DB::beginTransaction();
        if ($request->file('media')->isValid()) {
            $this->tableRepository->createMedia($tableId, $request->file('media'));
        }
        DB::commit();
        return true;
    }


    public function deleteMediaForId($tableId, $mediaId)
    {
        DB::beginTransaction();
        $this->tableRepository->deleteMediaForId($tableId, $mediaId);
        DB::commit();
        return true;
    }
}
