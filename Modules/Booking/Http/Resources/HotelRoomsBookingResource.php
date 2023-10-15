<?php

namespace Modules\Booking\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Hotels\Http\Resources\RoomResource;
use App\Traits\ResourceHelper;
use Illuminate\Support\Facades\Auth;
use Modules\Hotels\Http\Resources\HotelResource;

class HotelRoomsBookingResource extends JsonResource
{
    use ResourceHelper;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $resource = [
            'id'           => $this->id,
            'start_date'   => $this->start_date,
            'end_date'     => $this->end_date,
            'price'        => $this->price,
            'max_guests'   => $this->max_guests,
            'guest_data'   => $this->booking->guest,
            'rooms_count'  => $this->max_guests,
            'booking_id'   => $this->booking_id,
            'room_id'      => $this->room_id,
            'created_at'   => $this->created_at,
            'room'         => $this->resource($this->room, RoomResource::class),
            'hotel'        => $this->resource($this->room->hotel, HotelResource::class),
        ];

        if (Auth::guard('customer')->check()) {
            $resource += ['review' => $this->booking->bookable->reviews()->where('author_id', '=', Auth::guard('customer')->user()->id)->get()];
        }
        return $resource;
    }
}
