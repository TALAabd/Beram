<?php

namespace Modules\Hotels\RepositoryInterface;

use Modules\Hotels\Models\Hotel;
use Modules\Hotels\Models\Room;

interface RoomRepositoryInterface
{
    public function allByHotel(Hotel $hotel);
    public function create($attributes);
    public function update(Room $room, $attributes);
    public function find($roomId);
    public function delete($roomId);
    public function createMedia(Room $room, $mediaFile, $type);
    public function getAllMedia($id);
    public function deleteMediaForId($roomId,$mediaId);
    public function updateTermsByRoom(Room $room,$termIds);
    public function getAttributesTermsByRoom(Room $room);
    public function getAttributesTermsByRoomStatus(Room $room);
}
