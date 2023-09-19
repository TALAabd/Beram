<?php

namespace Modules\Hotels\Http\Services;

use Modules\Hotels\RepositoryInterface\RoomRepositoryInterface;
use Modules\Hotels\RepositoryInterface\HotelRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Exception;

class RoomService
{
    private $hotelRepository;
    private $roomRepository;

    public function __construct(
        RoomRepositoryInterface $roomRepository,
        HotelRepositoryInterface $hotelRepository
    ) {
        $this->hotelRepository = $hotelRepository;
        $this->roomRepository = $roomRepository;
    }

    public function create($validatedRequest)
    {
        DB::beginTransaction();
        $hotel = $this->hotelRepository->find($validatedRequest['hotel_id']);
        if (isset($hotel)) {
            $room = $this->roomRepository->create($validatedRequest);
        }
        DB::commit();
        return $room;
    }

    public function show($roomId)
    {
        $room = $this->roomRepository->find($roomId);
        return $room;
    }

    public function update($roomId, $validatedRequest)
    {
        DB::beginTransaction();
        $room = $this->roomRepository->find($roomId);
        $room = $this->roomRepository->update($room, $validatedRequest);
        DB::commit();
        return $room;
    }

    public function delete($roomId)
    {
        DB::beginTransaction();
        $this->roomRepository->delete($roomId);
        DB::commit();
        return true;
    }


    public function updateActiveStatus($roomId)
    {
        DB::beginTransaction();
        $room = $this->roomRepository->find($roomId);
        if (isset($room))
            $room->updateStatus();
        DB::commit();
        return $room;
    }

    public function getAllMedia($roomId)
    {
        return $this->roomRepository->getAllMedia($roomId);
    }


    public function addMedia($roomId, $request)
    {
        DB::beginTransaction();
        if ($request->file('media')->isValid()) {
            $this->roomRepository->createMedia($roomId, $request->file('media'), $request->type);
        }
        DB::commit();
        return true;
    }


    public function deleteMediaForId($roomId, $mediaId)
    {
        DB::beginTransaction();
        $this->roomRepository->deleteMediaForId($roomId, $mediaId);
        DB::commit();
        return true;
    }


    public function getAttributeTermsByRoom($roomId)
    {
        DB::beginTransaction();
        $room = $this->roomRepository->find($roomId);
        $terms = $this->roomRepository->getAttributesTermsByRoomStatus($room);
        DB::commit();
        return $terms;
    }


    public function showRoomDetailsWithTerms($roomlId)
    {
        $room = $this->roomRepository->find($roomlId);
        $attributesWithTerms = $this->roomRepository->getAttributesTermsByRoom($room);
        $room['attributes']=$attributesWithTerms;
        return $room;
    }



    public function updateTermsByRoom($termIds, $roomId)
    {
        DB::beginTransaction();
        $room = $this->roomRepository->find($roomId);
        $terms = $this->roomRepository->updateTermsByRoom($room, $termIds);
        DB::commit();
        return $terms;
    }


    public function count(): int
    {
        return DB::table('rooms')->count();
    }

}
