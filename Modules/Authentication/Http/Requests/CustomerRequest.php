<?php

namespace Modules\Authentication\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            //'email' => 'required|string|email|unique:customers,email|max:255',
            //'phone' => 'required|string|unique:customers,phone|max:255',
            'gender' => 'nullable|string|in:male,female',
            'address' => 'nullable|string|max:255',
            'birthday' => 'nullable|date',
            //'password' => 'nullable|string|min:6',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'zip_code' => 'nullable|integer',
            'last_login_at' => 'nullable|date',
            'bio' => 'nullable|string',
        ];
    }
}
