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
        return match ($this->route()->getActionMethod()) {
            'update' => $this->update(),
            'create' => $this->tripCreate(),
            default => []
        };
    }
    public function tripCreate()
    {
        return [
            'name'             => 'required|string|max:255',
            'provider_id'      => 'required|exists:users,id',
            'description'      => 'required',
            'status'           => '',
            'period'           => '',
            'starting_city_id' => 'nullable|exists:cities,id',
            'price'            => '',
            'image'            => '',
            'contact'          => '',
            'date'             => '',
            'cities'           => '',
            'feature'          => '',
            'lang'             => ''
        ];
    }

    public function update()
    {
        return [
            'name'             => 'required|string|max:255',
            'provider_id'      => 'nullable|exists:users,id',
            'description'      => 'required',
            'status'           => '',
            'period'           => '',
            'starting_city_id' => 'exists:cities,id',
            'price'            => '',
            'image'            => '',
            'contact'          => '',
            'date'             => '',
            'cities'           => '',
            'feature'          => '',
            'lang'             => ''
        ];
    }
}
