<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\DB;
use App\Traits\ModelHelper;
use App\Models\StoryItem;

class StoryItemService
{
    use ModelHelper;

    public function getAll()
    {
        return StoryItem::all();
    }

    public function find($story_itemId)
    {
        return $this->findByIdOrFail(StoryItem::class,'story_item', $story_itemId);
    }

    public function create($validatedData)
    {
        DB::beginTransaction();

        $story_item = StoryItem::create($validatedData);

        DB::commit();

        return $story_item;
    }

    public function update($validatedData, $story_itemId)
    {
        $story_item = $this->find($story_itemId);

        DB::beginTransaction();

        $story_item->update($validatedData);

        DB::commit();

        return true;
    }

    public function delete($story_itemId)
    {
        $story_item = $this->find($story_itemId);

        DB::beginTransaction();

        $story_item->delete();

        DB::commit();

        return true;
    }
}
