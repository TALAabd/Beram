<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoryItemRequest;
use App\Http\Resources\StoryItemResource;
use App\Http\Services\StoryItemService;

class StoryItemController extends Controller
{
    public function __construct(private StoryItemService $story_itemService)
    {
    }

    public function find($story_itemId)
    {
        $story_item = $this->story_itemService->find($story_itemId);

        return $this->successResponse(
            $this->resource($story_item, StoryItemResource::class),
            'dataFetchedSuccessfully'
        );
    }

    public function create(StoryItemRequest $request)
    {
        $validatedData = $request->validated();
        $story_item = $this->story_itemService->create($validatedData);
        if ($request->file('image') && $request->file('image')->isValid()) {
            $story_item->addMedia($request->file('image'))->toMediaCollection('image');
        }
        if ($request->file('video') && $request->file('video')->isValid()) {
            $story_item->addMedia($request->file('video'))->toMediaCollection('video');
        }
        return $this->successResponse(
            null,
            'dataAddedSuccessfully'
        );
    }

    public function update(StoryItemRequest $request, $story_itemId)
    {
        $validatedData = $request->validated();
        $this->story_itemService->update($validatedData, $story_itemId);

        return $this->successResponse(
            null,
            'dataUpdatedSuccessfully'
        );
    }

    public function delete($story_itemId)
    {
        $this->story_itemService->delete($story_itemId);

        return $this->successResponse(
            null,
            'dataDeletedSuccessfully'
        );
    }
}
