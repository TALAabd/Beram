<?php

namespace App\Http\Controllers;

use App\Http\Requests\CoreTermRequest;
use App\Http\Resources\CoreTermResource;
use App\Http\Services\CoreTermService;

class CoreTermController extends Controller
{

    public function __construct(private CoreTermService $core_termService)
    {
        $this->middleware('permission:terms_manager', ['only' => ['index', 'store', 'update', 'destroy']]);
    }


    public function show($core_termId)
    {
        $core_term = $this->core_termService->find($core_termId);
        return $this->successResponse(
            $this->resource($core_term, CoreTermResource::class),
            'dataFetchedSuccessfully'
        );
    }

    public function store(CoreTermRequest $request)
    {
        $validatedData = $request->validated();
        $core_term = $this->core_termService->create($validatedData);
        return $this->successResponse(
            $this->resource($core_term, CoreTermResource::class),
            'dataAddedSuccessfully'
        );
    }

    public function update(CoreTermRequest $request, $core_termId)
    {
        $validatedData = $request->validated();
        $core_term = $this->core_termService->update($validatedData, $core_termId);
        return $this->successResponse(
            $this->resource($core_term, CoreTermResource::class),
            'dataUpdatedSuccessfully'
        );
    }

    public function destroy($core_termId)
    {
        $this->core_termService->delete($core_termId);
        return $this->successResponse(
            null,
            'dataDeletedSuccessfully'
        );
    }
}
