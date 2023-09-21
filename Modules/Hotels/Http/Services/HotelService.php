<?php

namespace Modules\Hotels\Http\Services;

use Modules\Hotels\RepositoryInterface\HotelRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Modules\Hotels\Models\Hotel;
use Modules\Hotels\Models\Room;
use Illuminate\Database\Eloquent\Collection;
use App\Traits\MapHelper;

class HotelService
{
    use MapHelper;

    private $hotelRepository;

    public function __construct(
        HotelRepositoryInterface $hotelRepository
    ) {
        $this->hotelRepository = $hotelRepository;
    }

    public function getHotels($request)
    {
        if (isset(request()->filter['userLat']) && isset(request()->filter['userLng'])) {
            return $this->getNearlyHotels(request()->filter['userLat'], request()->filter['userLng']);
        }
        $page = (isset($request->page)) ? $request->page :  null;
        return $this->hotelRepository->all($page, $request->per_page);
    }

    public function getFeaturedHotels()
    {
        return $this->hotelRepository->allFeatured();
    }

    public function getTopRatedHotels()
    {
        return $this->hotelRepository->allTopRated();
    }
    public function recentlyHotels()
    {
        return $this->hotelRepository->recentlyHotels();
    }

    public function createHotel($validatedRequest)
    {
        DB::beginTransaction();
        $hotel = $this->hotelRepository->create($validatedRequest);
        DB::commit();
        return $hotel;
    }

    public function showHotelDetails($hotelId)
    {
        $hotel = $this->hotelRepository->find($hotelId);
        return $hotel;
    }

    public function updateHotelDetails($hotelId, $validatedRequest)
    {
        DB::beginTransaction();
        $hotel = $this->hotelRepository->find($hotelId);
        DB::commit();
        return $this->hotelRepository->update($hotel, $validatedRequest);
    }

    public function deleteHotel($hotelId)
    {
        DB::beginTransaction();
        $this->hotelRepository->delete($hotelId);
        DB::commit();
        return true;
    }

    public function getRoomsByhotel($request)
    {
        DB::beginTransaction();
        // if ($request->id == null) {
            // $rooms = Room::filter($request)->get();
            $rooms = Room::filter($request);
        // } 
        // else {
        //     $hotel = $this->hotelRepository->find($request->id);
        //     $rooms$hotel->rooms()->filter(request()->filter);
        //     $rooms = $this->hotelRepository->getAllRoomsByhotel($hotel,$request);
        // }
        DB::commit();
        return $rooms;
    }

    public function getRooms($hotelId)
    {
        DB::beginTransaction();
        if ($hotelId == null) {
            $rooms = Room::get();
        }
        //  else {
        //     $hotel = $this->hotelRepository->find($hotelId);
        //     $rooms = $this->hotelRepository->getAllRoomsByhotel($hotel, $request);
        // }
        DB::commit();
        return $rooms;
    }

    public function getAllMedia($hotelId)
    {
        return $media = $this->hotelRepository->getAllMedia($hotelId);
    }

    public function getAttributeTermsByHotel($hotelId)
    {
        DB::beginTransaction();
        $hotel = $this->hotelRepository->find($hotelId);
        $terms = $this->hotelRepository->getAttributesTermsByHotelStatus($hotel);
        DB::commit();
        return $terms;
    }

    public function showHotelDetailsWithTerms($hotelId)
    {
        $hotel = $this->hotelRepository->find($hotelId);
        $attributesWithTerms = $this->hotelRepository->getAttributesTermsByHotel($hotel);
        $hotel['attributes'] = $attributesWithTerms;
        return $hotel;
    }

    public function updateTermsByhotel($termIds, $hotelId)
    {
        DB::beginTransaction();
        $hotel = $this->hotelRepository->find($hotelId);
        $terms = $this->hotelRepository->updateTermsByhotel($hotel, $termIds);
        DB::commit();
        return $terms;
    }

    public function getNearlyHotels($latitudeFrom, $longitudeFrom)
    {
        $hotels  = Hotel::all();
        $nearlyHotels = new Collection();
        $hotels->each(function ($hotel) use ($latitudeFrom, $longitudeFrom, &$nearlyHotels) {
            $dist = $this->haversineDistance($latitudeFrom, $longitudeFrom, $hotel->map_lat, $hotel->map_lng);
            $hotel->distance = $dist;  // Add a new property to the hotel object to hold the distance
            $nearlyHotels->push($hotel);
        });
        $nearlyHotels = $nearlyHotels->sortBy('distance')->take(10);  // Sort the nearly hotels by distance and take top 10
        return $nearlyHotels;
    }

    public function count(): int
    {
        return DB::table('hotels')->count();
    }
}
