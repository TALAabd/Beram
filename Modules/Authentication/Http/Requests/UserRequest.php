<?php

namespace Modules\Authentication\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        $userId = $this->route('user');
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'birthday' => ['required', 'date'],
            'city' => ['required', 'string', 'max:255'],
            'state' => ['required', 'string', 'max:255'],
            'country' => ['required', 'string', 'max:255'],
            'zip_code' => ['required', 'integer'],
            'role' => ['required','in:provider,administrator,Hotel_provider,Trip_provider']
        ];

        switch ($this->method()) {
            case 'POST': {
                    $rules += [
                        'phone' => ['required', 'string','unique:users,phone'],
                        'email' => ['required', 'email', 'unique:users,email', 'max:255'],
                        'password' => ['required', 'string', 'min:8']
                    ];
                    return $rules;
                }
            case 'PUT': {
                    $rules += [
                        'phone' => ['required', 'string','unique:users,phone,' . $userId],
                        'email' => ['required', 'email', 'unique:users,email,' . $userId, 'max:255'],
                        'password' => ['string', 'min:8']
                    ];
                    return $rules;
                }
            default:
                break;
        }
        return $rules;
    }
}
