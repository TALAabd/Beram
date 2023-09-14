<?php

namespace Modules\Hotels\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HotelRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $rules = [
            'name'           => 'required|string|max:255',
            'title'          => 'required|string|max:255',
            'content'        => 'required|string',
            'location_id'    => 'required|integer|exists:cities,id',
            'address'        => 'required|string|max:255',
            'map_lat'        => 'required|string|max:20',
            'map_lng'        => 'required|string|max:20',
            'map_zoom'       => 'required|integer',
            'policy'         => 'required|string',
            'star_rate'      => 'required|integer|min:1|max:5',
            'check_in_time'  => 'required|string|max:255',
            'check_out_time' => 'required|string|max:255',
            'web'            => 'nullable|url|max:255',
            'fax'            => 'nullable|string|max:20',
            'email'          => 'nullable|email|max:255',
            'phone'          => 'nullable|string|max:20',
            'min_price'      => 'required|numeric',
            'max_price'      => 'required|numeric',
        ];
        switch ($this->method()) {
            case 'POST': {
                    $rules += [
                        'image' => ['file', 'required'],
                    ];
                    return $rules;
                }
            case 'PUT': {
                    return $rules;
                }
            default:
                break;
        }
    }
}
