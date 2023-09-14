<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CurrencyRequest extends FormRequest
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
        return [
            'name'          => ['required', 'string', 'max:255'],
            'code'          => ['required', 'string', 'max:255'],
            'symbol'        => ['required', 'string', 'max:255'],
            'format'        => ['nullable', 'string', 'max:255'],
            'exchange_rate' => ['required', 'numeric', 'max:255'],
            'active'        => ['nullable', 'boolean', 'max:255'],
        ];
    }
}
