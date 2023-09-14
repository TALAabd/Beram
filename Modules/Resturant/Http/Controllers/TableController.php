<?php

namespace Modules\Resturant\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Resturant\Http\Requests\TableRequest;
use Modules\Resturant\Http\Resources\TableResource;
use Modules\Resturant\Http\Services\TableService;

class TableController extends Controller
{
    public function __construct(private TableService $tableService)
    {
        $this->middleware('permission:tables_manager');
    }

    public function store(TableRequest $request)
    {
        $validatedData = $request->validated();
        $table = $this->tableService->create($validatedData);
        if ($request->file('image') && $request->file('image')->isValid()) {
            $table->addMedia($request->file('image'))->toMediaCollection('thumbnail');
        }
        return $this->successResponse(
            $this->resource($table, TableResource::class),
            'dataAddedSuccessfully'
        );
    }

    public function show($tableId)
    {
        $table = $this->tableService->find($tableId);

        return $this->successResponse(
            $this->resource($table, TableResource::class),
            'dataFetchedSuccessfully'
        );
    }

    public function update(TableRequest $request, $tableId)
    {
        $validatedData = $request->validated();
        $this->tableService->update($validatedData, $tableId);

        return $this->successResponse(
            null,
            'dataUpdatedSuccessfully'
        );
    }

    public function destroy($tableId)
    {
        $this->tableService->delete($tableId);

        return $this->successResponse(
            null,
            'dataDeletedSuccessfully'
        );
    }

    public function updateStatus($tableId)
    {
        DB::beginTransaction();
        $room = $this->tableService->updateActiveStatus($tableId);
        DB::commit();
        return $this->successResponse(
            $this->resource($room, TableResource::class),
            'dataUpdatedSuccessfully'
        );
    }

    public function getMedia($tableId)
    {
        $media = $this->tableService->getAllMedia($tableId);
        return $this->successResponse(
            $media,
            'MediaFetchedSuccessfully'
        );
    }


    public function addMedia(Request $request, $tableId)
    {
        DB::beginTransaction();
        $this->tableService->addMedia($tableId, $request);
        DB::commit();
        return $this->successResponse(
            null,
            'dataAddedSuccessfully'
        );
    }

    public function deleteMedia($tableId, $mediaId)
    {
        $this->tableService->deleteMediaForId($tableId, $mediaId);
        return $this->successResponse(
            null,
            'dataDeletedSuccessfully'
        );
    }
}
