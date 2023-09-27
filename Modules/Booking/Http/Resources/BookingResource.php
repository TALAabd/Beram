<?php

namespace Modules\Booking\Http\Resources;

use App\Http\Resources\TripResource;
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
        $actionMethod = $request->route()->getActionMethod();
        return match ($actionMethod) {
            'getRecentBookings' => $this->getRecentBookings($request),
            'getAllByCustomer' => $this->getAllByCustomer($request),
            'getAllTripsByCustomer' => $this->getAllTripsByCustomer($request),
            default            => $this->allData($request),
        };
    }

    public function getAllByCustomer($request)
    {
        if ($this->service_type == 'trip') {
            return [
                'id'              => $this->id,
                'booking_code'    => $this->booking_code,
                'check_in_date'   => $this->bookable->date,
                'period'          => $this->bookable->period,
                'customer_notes'  => $this->customer_notes,
                'booking_notes'   => $this->booking_notes,
                'status'          => $this->status,
                'total_price'     => $this->total_price,
                'is_confirmed'    => $this->is_confirmed,
                'created_at'      => $this->created_at,
                'trip_id'         => $this->bookable->id,
                'trip_name'       => $this->bookable->name,
                'status'          => $this->status,
            ];
        } elseif ($this->service_type == 'hotel') {
            return [
                'id'              => $this->id,
                'booking_code'    => $this->booking_code,
                'check_in_date'   => $this->check_in_date,
                'check_out_date'  => $this->check_out_date,
                'customer_notes'  => $this->customer_notes,
                'booking_notes'   => $this->booking_notes,
                'status'          => $this->status,
                'total_price'     => $this->total_price,
                'is_confirmed'    => $this->is_confirmed,
                'created_at'      => $this->created_at,
                'hotel_id'        => $this->bookable->id,
                'hotel_name'      => $this->bookable->name,
                'room_id'         => $this->roomBookings[0]->room->id,
                'room_name'       => $this->roomBookings[0]->room->title,
                'status'          => $this->status,

            ];
        }
    }

    public function getAllTripsByCustomer($request)
    {

        return [
            'id'              => $this->id,
            'booking_code'    => $this->booking_code,
            'check_in_date'   => $this->bookable->date,
            'period'          => $this->bookable->period,
            'customer_notes'  => $this->customer_notes,
            'booking_notes'   => $this->booking_notes,
            'status'          => $this->status,
            'total_price'     => $this->total_price,
            'is_confirmed'    => $this->is_confirmed,
            'created_at'      => $this->created_at,
            'trip_id'         => $this->bookable->id,
            'trip_name'       => $this->bookable->name,
            'status'          => $this->status,
        ];
    }

    public function allData($request)
    {
        if ($this->service_type == 'hotel') {
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
                'room_details'    => $this->resource($this->roomBookings, HotelRoomsBookingResource::class),
            ];
        } elseif ($this->service_type == 'trip') {
            return [
                'id'              => $this->id,
                'booking_code'    => $this->booking_code,
                'service_type'    => __('objects.' . $this->service_type),
                'check_in_date'   => $this->bookable->date,
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
                'trip_details'   => $this->resource($this->bookable, TripResource::class),
            ];
        }
    }

    public function getRecentBookings($request)
    {
        if ($this->service_type == 'hotel') {
            return [
                'id'              => $this->id,
                'booking_code'    => $this->booking_code,
                'service_type'    => __('objects.' . $this->service_type),
                'check_in_date'   => $this->check_in_date,
                // 'check_out_date'  => $this->check_out_date,
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
                'service_id'      => $this->bookable_id,
                'service_name'    => $this->bookable->name,
            ];
        } elseif ($this->service_type == 'trip') {
            return [
                'id'              => $this->id,
                'booking_code'    => $this->booking_code,
                'service_type'    => __('objects.' . $this->service_type),
                'check_in_date'   => $this->bookable->date,
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
                'service_id'      => $this->bookable_id,
                'service_name'    => $this->bookable->name,
            ];
        }
    }
}
