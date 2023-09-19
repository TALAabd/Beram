<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CoreAttributeRequest extends FormRequest
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
            'name'     => ['required', 'string', 'max:255'],
            'position' => ['required', 'integer', 'max:255'],
            'icon'     => ['string', 'max:255'],
        ];

        switch ($this->method()) {
            case 'POST': {
                    $rules += [
                        'service' => ['required', 'string', 'max:255'],
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
