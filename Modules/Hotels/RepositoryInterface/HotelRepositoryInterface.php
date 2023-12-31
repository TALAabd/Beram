<?php

namespace Modules\Hotels\RepositoryInterface;

use Illuminate\Http\Request;
use Modules\Hotels\Models\Hotel;

interface HotelRepositoryInterface
{
    public function all();
    public function allByProvider();
    public function create($attributes);
    public function update(Hotel $hotel, $attributes);
    public function find($hotelId);
    public function delete($hotelId);
    public function createMedia(Hotel $hotel,$mediaFile, $type);
    public function allFeatured();
    public function allTopRated();
    public function recentlyHotels();
    public function getAllMedia($id);
    public function deleteMediaForId($hotelId,$mediaId);
    public function getAllRoomsByhotel(Hotel $hotel, Request $request);
    public function getAllRoomsByhotelAvailability(Hotel $hotel);
    public function updateTermsByhotel(Hotel $hotel,$termIds);
    public function getAttributesTermsByHotelStatus(Hotel $hotel);
    public function getAttributesTermsByHotel(Hotel $hotel);
    public function getNearestHotel($request);
    
}
