<?php

namespace Modules\Booking\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Hotels\Http\Resources\RoomResource;
use App\Traits\ResourceHelper;
use Illuminate\Support\Facades\Auth;

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
            'rooms_count'  => $this->max_guests,
            'booking_id'   => $this->booking_id,
            'room_id'      => $this->room_id,
            'created_at'   => $this->created_at,
            'room'         => $this->resource($this->room, RoomResource::class),
        ];

        if (Auth::guard('customer')->check()) {
            $resource += ['review' => $this->booking->bookable->reviews()->where('author_id', '=', Auth::guard('customer')->user()->id)->get()];
        }
        return $resource;
    }
}
