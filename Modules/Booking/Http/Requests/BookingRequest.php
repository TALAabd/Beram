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
            'update'     => $this->update(),
            'updateTrip' => $this->updateTrip(),
            'search'     => $this->search(),
            'createGuestBooking'  => $this->createGuestBooking(),
            'changeStatusBooking' => $this->changeStatusBooking(),
            'createTripBooking'   => $this->createTripBooking(),
            'saveBookingFile'     => $this->saveBookingFile(),
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
    public function updateTrip()
    {
        return [
            'total_price'   => 'required|numeric',
            'total_guests'  => 'required|numeric',
            'booking_notes' => 'required|string',
            'date'          => ''
        ];
    }

    public function createTripBooking()
    {
        return [
            'trip_id'        => 'required|integer|exists:trips,id',
            'total_guests'   => 'required|numeric',
            'customer_notes' => 'nullable|string',
        ];
    }

    public function createGuestBooking()
    {
        return [
            'first_name'     => 'nullable|string|max:255',
            'last_name'      => 'nullable|string|max:255',
            'nationality'    => 'nullable|in:Syrian,Foreign',
            'email'          => 'nullable|string|email|unique:customers,email|max:255',
            'phone'          => 'nullable|string|unique:customers,phone|max:255',
            'customer_id'    => 'nullable|integer|exists:customers,id',
            'trip_id'        => 'required|integer|exists:trips,id',
            'total_guests'   => 'required|numeric',
            'customer_notes' => 'nullable|string',
            'payment_id'     => ''
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

    public function saveBookingFile()
    {
        return [
            'file' => 'required|file',
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
