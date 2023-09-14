<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoryRequest;
use App\Http\Resources\StoryResource;
use App\Http\Services\StoryService;

class StoryController extends Controller
{
    public function __construct(private StoryService $storyService)
    {

    }

    public function getAll()
    {
        $stories = $this->storyService->getAll();
        return $this->successResponse(
            $this->resource($stories, StoryResource::class),
            'dataFetchedSuccessfully'
        );
    }

    public function find($storyId)
    {
        $story = $this->storyService->find($storyId);

        return $this->successResponse(
            $this->resource($story, StoryResource::class),
            'dataFetchedSuccessfully'
        );
    }

    public function create(StoryRequest $request)
    {
        $validatedData = $request->validated();
        $story = $this->storyService->create($validatedData);

        return $this->successResponse(
            null,
            'dataAddedSuccessfully'
        );
    }

    public function update(StoryRequest $request, $storyId)
    {
        $validatedData = $request->validated();
        $this->storyService->update($validatedData, $storyId);

        return $this->successResponse(
            null,
            'dataUpdatedSuccessfully'
        );
    }

    public function delete($storyId)
    {
        $this->storyService->delete($storyId);
        // ***** **** *** delete all media by story details
        return $this->successResponse(
            null,
            'dataDeletedSuccessfully'
        );
    }

    public function getAllItems($storyId)
    {
        $stories = $this->storyService->getAllItemsByStoryId($storyId);
        return $this->successResponse(
            $this->resource($stories, StoryResource::class),
            'dataFetchedSuccessfully'
        );
    }
}
