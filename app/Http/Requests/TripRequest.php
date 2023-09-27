<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TripRequest extends FormRequest
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
            'create' => $this->destroy(),
            default => []
        };
    }
    public function update()
    {
        return [
            'name'        => 'required|string|max:255',
            'provider_id' => 'required|exists:users,id',
            'description' => '',
            'period'      => 'required',
            'starting_city_id' => 'required|exists:cities,id',
            'price' => 'required',
            'image' => '',
            'contact' => 'required',
            'date' => 'required',
            'cities' => '',
            'feature' => ''
        ];
    }
}
