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
        $rules = [
            'hotel_id' => 'required|integer|exists:hotels,id',
            'check_in_date' => 'required|date_format:Y-m-d H:i:s|after_or_equal:today',
            'check_out_date' => 'required|date_format:Y-m-d H:i:s|after:check_in_date',
            // 'email' => 'required|email',
            // 'first_name' => 'required|max:255',
            // 'last_name' => 'required|max:255',
            // 'phone' => 'required|max:255',
            'customer_notes' => 'nullable|string',
            // 'rooms' => 'required|array|min:1',
            'room_id' => 'required|exists:rooms,id',
            // 'rooms.*.start_date' => 'required|date|after_or_equal:today',
            // 'rooms.*.end_date' => 'required|date|after:rooms.*.start_date',
            'max_guests' => 'required|integer|min:1',
            'rooms_count' => 'required|integer|min:1',
        ];

        switch ($this->method()) {
            case 'POST': {
                    return $rules;
                }
            default:
                break;
        }
        return $rules;
    }
}
