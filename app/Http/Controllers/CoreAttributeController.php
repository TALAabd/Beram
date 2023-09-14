<?php

namespace App\Http\Controllers;

use App\Http\Requests\CoreAttributeRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\CoreTermResource;
use App\Http\Resources\CoreAttributeResource;
use App\Http\Services\CoreAttributeService;

class CoreAttributeController extends Controller
{

    public function __construct(private CoreAttributeService $attributeService)
    {
        $this->middleware('permission:attributes_manager', ['only' => ['index', 'store', 'update', 'destroy','getAllTermsByattribute']]);
        $this->middleware('permission:terms_manager', ['only' => ['getAllTermsByattribute']]);
    }


    public function index()
    {
        $attributes = $this->attributeService->getAttributes();
        return $this->successResponse(
            $this->resource($attributes, CoreAttributeResource::class),
            'dataFetchedSuccessfully'
        );
    }


    public function store(CoreAttributeRequest $request)
    {
        $validatedData = $request->validated();
        $attribute =  $this->attributeService->createAttribute($validatedData);
        return $this->successResponse(
            $this->resource($attribute, CoreAttributeResource::class),
            'dataAddedSuccessfully'
        );
    }


    public function show($attributeId)
    {
        $attribute = $this->attributeService->find($attributeId);
        return $this->successResponse(
            $this->resource($attribute, CoreAttributeResource::class),
            'dataFetchedSuccessfully'
        );
    }

    public function update(CoreAttributeRequest $request, $attributeId)
    {
        $validatedData = $request->validated();
        $attribute = $this->attributeService->updateAttribute($attributeId, $validatedData);
        return $this->successResponse(
            $this->resource($attribute, CoreAttributeResource::class),
            'dataUpdatedSuccessfully'
        );
    }


    public function destroy($attributeId)
    {
        $this->attributeService->deleteAttribute($attributeId);
        return $this->successResponse(
            null,
            'dataDeletedSuccessfully'
        );
    }


    public function getAllTermsByattribute($attributeId)
    {
        $terms = $this->attributeService->getTermsByAttribute($attributeId);
        return $this->successResponse(
            $this->resource($terms, CoreTermResource::class),
            'dataFetchedSuccessfully'
        );
    }

    public function getAllAttributeWithTerms()
    {
        $AttributesWithTerms = $this->attributeService->getAttributes();
        return $this->successResponse(
            $this->resource($AttributesWithTerms, CoreAttributeResource::class),
            'dataFetchedSuccessfully'
        );
    }
}
