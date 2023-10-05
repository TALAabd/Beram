<?php

namespace Modules\Booking\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HotelRoomsBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return match ($this->route()->getActionMethod()) {
            'HotelGuestBooking'  => $this->HotelGuestBooking(),
            'createHotelBooking' => $this->createHotelBooking(),
        };
    }
    public function HotelGuestBooking()
    {
        return [
            'hotel_id'       => 'required|integer|exists:hotels,id',
            'check_in_date'  => 'required|date_format:Y-m-d H:i:s|after_or_equal:today',
            'check_out_date' => 'required|date_format:Y-m-d H:i:s|after:check_in_date',
            'first_name'     => 'nullable|string|max:255',
            'last_name'      => 'nullable|string|max:255',
            'nationality'    => 'nullable|in:Syrian,Foreign',
            'email'          => 'nullable|string|email|unique:customers,email|max:255',
            'phone'          => 'nullable|string|unique:customers,phone|max:255',
            'customer_id'    => 'nullable|integer|exists:customers,id',
            'payment_id'     => '',
            'customer_notes' => 'nullable|string',
            'room_id'        => 'required|exists:rooms,id',
            'max_guests'     => 'required|integer|min:1',
            'rooms_count'    => 'required|integer|min:1',
        ];
    }
    public function createHotelBooking()
    {
        return [
            'hotel_id'       => 'required|integer|exists:hotels,id',
            'check_in_date'  => 'required|date_format:Y-m-d H:i:s|after_or_equal:today',
            'check_out_date' => 'required|date_format:Y-m-d H:i:s|after:check_in_date',
            'customer_notes' => 'nullable|string',
            'payment_id'     => '',
            'room_id'        => 'required|exists:rooms,id',
            'max_guests'     => 'required|integer|min:1',
            'rooms_count'    => 'required|integer|min:1',
        ];
    }
}
