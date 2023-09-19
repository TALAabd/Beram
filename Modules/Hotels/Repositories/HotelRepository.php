<?php

namespace Modules\Hotels\Repositories;

use App\Models\CoreAttribute;
use Illuminate\Support\Facades\Auth;
use Modules\Hotels\RepositoryInterface\HotelRepositoryInterface;
use Modules\Hotels\Models\Hotel;
use Illuminate\Support\Str;
use App\Traits\ModelHelper;
use Modules\Authentication\Models\User;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class HotelRepository implements HotelRepositoryInterface
{
    use ModelHelper;

    public function all($page = null, $per_page = 5)
    {
        $hotels = (isset($page)) ? Hotel::hotels(request()->filter)->simplePaginate($per_page) :  Hotel::hotels(request()->filter)->get();
        return $hotels;
    }

    public function allFeatured()
    {
        return Hotel::where('is_featured', 1)->get();
    }

    public function allTopRated()
    {
        return Hotel::where('star_rate', [4, 5])->get();
    }
    public function recentlyHotels()
    {
        return Hotel::orderBy('created_at', 'asc')->get();
    }


    public function allByProvider()
    {
        return Auth::user()->hotels;
    }

    public function create($attributes)
    {
        $user = User::where('id', Auth::user()->id)->first();
        $attributes['slug'] = Str::slug($attributes['name'] . '-' . Str::random(6));
        $attributes['user_id'] = ($user->role == "employee") ? $user->parent->id : $user->id;
        return Hotel::create($attributes);
    }

    public function update(Hotel $hotel, $attributes)
    {
        $lang = app()->getLocale();
        $hotel->setTranslation('name', $lang, $attributes['name']);
        $hotel->setTranslation('title', $lang, $attributes['title']);
        $hotel->setTranslation('content', $lang, $attributes['content']);
        $hotel->setTranslation('address', $lang, $attributes['address']);
        $hotel->setTranslation('policy', $lang, $attributes['policy']);
        $hotel->location_id = $attributes['location_id'];
        $hotel->map_lat = $attributes['map_lat'];
        $hotel->map_lng = $attributes['map_lng'];
        $hotel->map_zoom = $attributes['map_zoom'];
        $hotel->star_rate = $attributes['star_rate'];
        $hotel->check_in_time = $attributes['check_in_time'];
        $hotel->check_out_time = $attributes['check_out_time'];
        $hotel->min_price = $attributes['min_price'];
        $hotel->max_price = $attributes['max_price'];
        $hotel->web = $attributes['web'];
        $hotel->phone = $attributes['phone'];
        $hotel->email = $attributes['email'];
        $hotel->fax = $attributes['fax'];

        $hotel->save();
        return $hotel;
    }

    public function find($hotelId)
    {
        return $this->findByIdOrFail(Hotel::class, 'hotel', $hotelId);
    }

    public function delete($hotelId)
    {
        $hotel = $this->find($hotelId);
        $hotel->delete();
    }

    public function createMedia($hotelId, $mediaFile, $type = 'media')
    {
        $hotel = $this->find($hotelId);
        if ($type == 'main') {
            $hotel->clearMediaCollection('thumbnail');
            $hotel->addMedia($mediaFile)->toMediaCollection('thumbnail');
        } elseif ($type == 'media') {
            $hotel->addMedia($mediaFile)->toMediaCollection('hotels-media');
        }
    }

    public function getAllMedia($id)
    {
        $hotel = $this->find($id);
        $media = $hotel->getMedia('hotels-media');
        $thumbnails = $media->map(function ($item) {
            return [
                'id' => $item->id,
                'url' => $item->getFullUrl()
            ];
        });
        return $thumbnails;
    }


    public function deleteMediaForId($hotelId, $mediaId)
    {
        $hotel = $this->find($hotelId);
        $media = Media::where('id', $mediaId)->first();
        $mediaItem = $hotel->getMedia($media->collection_name)->firstWhere('id', $mediaId);
        $mediaItem->delete();
        return true;
    }


    public function getAllRoomsByhotel(Hotel $hotel)
    {
        return $hotel->rooms()->filter(request()->filter)->get();
        // return $hotel->rooms;
    }


    public function getAllRoomsByhotelAvailability(Hotel $hotel)
    {
        return $hotel->rooms()->where('status', '=', 'true')->get();
    }

    public function getAttributesTermsByHotelStatus(Hotel $hotel)
    {
        $hotelsTerms = $hotel->terms->pluck('id')->toArray();
        $attributesWithHotelTerms = CoreAttribute::with('core_terms')->where('service', 'hotel')->get();
        $attributesWithHotelTerms->each(function ($attribute) use ($hotelsTerms) {
            $attribute->core_terms->each(function ($core_term) use ($hotelsTerms) {
                $core_term['status'] = in_array($core_term->id, $hotelsTerms);
            });
        });
        return $attributesWithHotelTerms;
    }

    public function getAttributesTermsByHotel(Hotel $hotel)
    {
        $hotelsTerms = $hotel->terms->pluck('id')->toArray();
        $attributesWithHotelTerms = CoreAttribute::with(['core_terms' => function ($query) use ($hotelsTerms) {
            $query->whereIn('id', $hotelsTerms)->select('id', 'name', 'core_attribute_id');
        }])->where('service', 'hotel')->get();
        return $attributesWithHotelTerms;
    }

    public function updateTermsByhotel(Hotel $hotel, $termIds)
    {
        return $hotel->terms()->sync($termIds);
    }
}
