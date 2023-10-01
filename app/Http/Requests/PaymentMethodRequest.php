<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentMethodRequest extends FormRequest
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
        if (request()->routeIs('payment_methods.addMedia')) {
            return [
                'image' => 'image|mimes:jpeg,jpg,png',
            ];
        }

        return [
            'name'   => '',
            'status' => ''
        ];
    }
}
