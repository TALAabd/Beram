<?php

namespace Modules\Booking\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class BookingRequest extends FormRequest
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
        return match ($this->getFunctionName()) {
            'update' => $this->update(),
            'search' => $this->search(),
            'changeStatusBooking' => $this->changeStatusBooking(),
            'DELETE' => $this->destroy(),
            default => []
        };
    }

    public function update()
    {
        return [
            'check_in_date' => 'required|date',
            'check_out_date' => 'required|date',
            'total_price' => 'required|numeric',
            'total_guests' => 'required|numeric',
            'booking_notes' => 'required|string',
        ];
    }

    public function search()
    {
        return [
            'booking_code' => 'required',
            'last_name'    => 'required',
            'first_name'   => 'required',
        ];
    }


    public function changeStatusBooking()
    {
        return [
            'status' => 'required|in:Pending,Confirmed,Cancelled',
        ];
    }

    public function destroy()
    {
        return [];
    }

    public function getFunctionName() :string
    {
        $action = $this->route()->getAction();
        $controllerAction = $action['controller'];
        list($controller, $method) = explode('@', $controllerAction);
        return $method;
    }
}
