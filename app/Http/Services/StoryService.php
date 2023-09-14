<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\DB;
use App\Traits\ModelHelper;
use App\Models\Story;

class StoryService
{
    use ModelHelper;

    public function getAll()
    {
        return Story::all();
    }

    public function find($storyId)
    {
        return $this->findByIdOrFail(Story::class,'story', $storyId);
    }

    public function create($validatedData)
    {
        DB::beginTransaction();

        $story = Story::create($validatedData);

        DB::commit();

        return $story;
    }

    public function update($validatedData, $storyId)
    {
        $story = $this->find($storyId);

        DB::beginTransaction();

        $story->update($validatedData);

        DB::commit();

        return true;
    }

    public function delete($storyId)
    {
        $story = $this->find($storyId);

        DB::beginTransaction();

        $story->delete();

        DB::commit();

        return true;
    }

    public function getAllItemsByStoryId($storyId)
    {
        $story = $this->find($storyId);
        return $story;
    }
}
