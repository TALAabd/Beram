<?php

namespace Modules\Booking\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Hotels\Http\Resources\HotelResource;
use App\Traits\ResourceHelper;

class BookingResource extends JsonResource
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
        return [
            'id'              => $this->id,
            'booking_code'    => $this->booking_code,
            'service_type'    => __('objects.' . $this->service_type),
            'check_in_date'   => $this->check_in_date,
            'check_out_date'  => $this->check_out_date,
            'total_guests'    => $this->total_guests,
            'email'           => $this->email,
            'first_name'      => $this->first_name,
            'last_name'       => $this->last_name,
            'phone'           => $this->phone,
            'customer_notes'  => $this->customer_notes,
            'booking_notes'   => $this->booking_notes,
            'status'          => $this->status,
            'total_price'     => $this->total_price,
            'is_confirmed'    => $this->is_confirmed,
            'created_at'      => $this->created_at,
            'hotel_details'   => $this->resource($this->bookable, HotelResource::class),
        ];
    }
}
