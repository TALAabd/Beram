<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CityRequest extends FormRequest
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
            'name'    => ['required', 'string', 'max:255'],
            'map_lng' => ['required'],
            'map_lat' => ['required'],
        ];

        switch ($this->method()) {
            case 'POST': {
                    $rules += [
                        'country_id' => ['required', 'integer'],
                    ];
                    return $rules;
                }
            case 'PUT': {
                    return $rules;
                }
            default:
                break;
        }
        return $rules;
    }
}
