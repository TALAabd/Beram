<?php

namespace Modules\Hotels\Repositories;

use App\Models\CoreAttribute;
use Illuminate\Support\Facades\Auth;
use Modules\Hotels\RepositoryInterface\RoomRepositoryInterface;
use Modules\Hotels\Models\Room;
use Modules\Hotels\Models\Hotel;
use App\Traits\ModelHelper;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class RoomRepository implements RoomRepositoryInterface
{
    use ModelHelper;

    public function allByHotel(Hotel $hotel)
    {
        $rooms = $hotel->rooms()->filter(request()->filter)->get();
        if (isset($rooms))
            return $rooms;
        else
            return [];
    }

    public function create($attributes)
    {
        $lang = app()->getLocale();
        $room = new Room;
        $room->setTranslation('title', $lang, $attributes['title']);
        $room->setTranslation('content', $lang, $attributes['content']);
        $room->foreign_price = $attributes['foreign_price'];
        $room->syrian_price = $attributes['syrian_price'];
        $room->number = $attributes['number'];
        $room->beds = $attributes['beds'];
        $room->size = $attributes['size'];
        $room->adults = $attributes['adults'];
        $room->children = $attributes['children'];
        $room->hotel_id = $attributes['hotel_id'];
        $room->create_user = Auth::user()->id;
        $room->save();
        return $room;
    }

    public function update(Room $room, $attributes)
    {
        $lang = app()->getLocale();
        $room->setTranslation('title', $lang, $attributes['title']);
        $room->setTranslation('content', $lang, $attributes['content']);
        $room->foreign_price = $attributes['foreign_price'];
        $room->syrian_price = $attributes['syrian_price'];
        $room->number = $attributes['number'];
        $room->beds = $attributes['beds'];
        $room->size = $attributes['size'];
        $room->adults = $attributes['adults'];
        $room->children = $attributes['children'];
        $room->update_user = Auth::user()->id;
        $room->save();
        return $room;
    }

    public function find($roomId)
    {
        return $this->findByIdOrFail(Room::class, 'room', $roomId);
    }

    public function delete($roomId)
    {
        $room = $this->find($roomId);
        $room->delete();
    }

    public function createMedia($roomId, $mediaFile, $type = 'media')
    {
        $room = $this->find($roomId);
        if ($type == 'main') {
            $room->clearMediaCollection('thumbnail');
            $room->addMedia($mediaFile)->toMediaCollection('thumbnail');
        } elseif ($type == 'media') {
            $room->addMedia($mediaFile)->toMediaCollection('rooms-media');
        }
    }

    public function getAllMedia($roomId)
    {
        $room = $this->find($roomId);
        $media = $room->getMedia('rooms-media');
        $thumbnails = $media->map(function ($item) {
            return [
                'id' => $item->id,
                'url' => $item->getFullUrl()
            ];
        });
        return $thumbnails;
    }

    public function deleteMediaForId($roomId, $mediaId)
    {
        $room  = $this->find($roomId);
        $media = Media::where('id', $mediaId)->first();
        $mediaItem = $room->getMedia($media->collection_name)->firstWhere('id', $mediaId);
        $mediaItem->delete();
    }

    public function getAttributesTermsByRoomStatus(Room $room)
    {
        $roomsTerms = $room->terms->pluck('id')->toArray();
        $attributesWithRoomTerms = CoreAttribute::with('core_terms')->where('service', 'room')->get();
        $attributesWithRoomTerms->each(function ($attribute) use ($roomsTerms) {
            $attribute->core_terms->each(function ($core_term) use ($roomsTerms) {
                $core_term['status'] = in_array($core_term->id, $roomsTerms);
            });
        });
        return $attributesWithRoomTerms;
    }

    public function getAttributesTermsByRoom(Room $room)
    {
        $roomsTerms = $room->terms->pluck('id')->toArray();
        $attributesWithRoomTerms = CoreAttribute::with(['core_terms' => function ($query) use ($roomsTerms) {
            $query->whereIn('id', $roomsTerms);
        }])->where('service', 'room')->get();
        return $attributesWithRoomTerms;
    }

    public function updateTermsByRoom(Room $room, $termIds)
    {
        return $room->terms()->sync($termIds);
    }
}
