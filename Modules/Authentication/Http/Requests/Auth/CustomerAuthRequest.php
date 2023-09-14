<?php

namespace Modules\Authentication\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CustomerAuthRequest extends FormRequest
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
            'register' => $this->register(),
            'SiteRegister' => $this->SiteRegister(),
            'reset_password_request' => $this->check_identity(),
            'otp_verification_submit' => $this->otp_verification_submit(),
            'reset_password_submit' => $this->reset_password_submit(),
            'changePassword' => $this->changePassword(),
            'check_identity'  => $this->check_identity(),
            'login'  => $this->login(),
            'login2'  => $this->login2(),
            default => []
        };
    }
    public function SiteRegister()
    {
        return [
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:customers,email'],
            'phone' => ['required', 'numeric', 'unique:customers,phone'],
            'password' => ['required', 'string', 'min:6','confirmed'],
            'password_confirmation' => ['required', 'string', 'min:6'],
        ];
    }
    public function login()
    {
        return [
            'phone'  => 'required|string|min:9|max:10',
            'password'      => 'required|string|min:6|max:30'
        ];
    }
    public function login2()
    {
        return [
            'email'  => 'required|email',
            'password'      => 'required|string|min:6|max:30'
        ];
    }

    public function register()
    {
        return [
            // 'name' => ['required', 'string'],
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:customers,email'],
            'phone' => ['required', 'numeric', 'unique:customers,phone'],
            // 'gender' => ['in:male,female'],
            // 'address' => ['nullable', 'string'],
            // 'birthday' => ['nullable', 'date'],
            'password' => ['required', 'string', 'min:6','confirmed'],
            'password_confirmation' => ['required', 'string', 'min:6'],
            // 'city' => ['nullable', 'string', 'max:255'],
            // 'state' => ['nullable', 'string', 'max:255'],
            // 'country' => ['nullable', 'string', 'max:255'],
            // 'zip_code' => ['nullable', 'integer'],
            // 'bio' => ['nullable', 'string'],
        ];
    }

    public function check_identity()
    {
        return [
            'verification_by' => ['required', 'in:email,phone'],
            'phone_or_email' => [
                'required',
                function ($attribute, $value, $fail) {
                    $identityType = request('identity_type');
                    if ($identityType == 'phone' && !is_numeric($value)) {
                        $fail(__('messages.mustBeValidPhoneNumber.'));
                    } elseif ($identityType == 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                        $fail(__('messages.mustBeValidEmailAddress.'));
                    }
                },
            ],
        ];
    }

    public function otp_verification_submit()
    {
        return [
            'verification_by' => ['required', 'in:email,phone'],
            'phone_or_email' => [
                'required',
                function ($attribute, $value, $fail) {
                    $identityType = request('verification_by');
                    if ($identityType == 'phone' && !is_numeric($value)) {
                        $fail(__('messages.mustBeValidPhoneNumber.'));
                    } elseif ($identityType == 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                        $fail(__('messages.mustBeValidEmailAddress.'));
                    }
                },
            ],
            'code' => ['required', 'numeric', 'min:100000', 'max:999999'],
        ];
    }


    public function changePassword()
    {
        return [
            'old_password' => ['required', 'string', 'min:6', 'max:60', function ($attribute, $value, $fail) {
                if (!Hash::check($value, Auth::guard('customer')->user()->password)) {
                    return $fail(__('messages.currentPasswordIncorrect'));
                }
            }],
            'new_password' => ['required', 'string', 'min:6', 'max:60', 'confirmed'],
            'new_password_confirmation' => ['required', 'string', 'min:6', 'max:60'],
        ];
    }

    public function reset_password_submit()
    {
        return [
            'verification_by' => ['required', 'in:email,phone'],
            'phone_or_email' => [
                'required',
                function ($attribute, $value, $fail) {
                    $identityType = request('verification_by');
                    if ($identityType == 'phone' && !is_numeric($value)) {
                        $fail(__('messages.mustBeValidPhoneNumber.'));
                    } elseif ($identityType == 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                        $fail(__('messages.mustBeValidEmailAddress.'));
                    }
                },
            ],
            'new_password' => ['required', 'string', 'min:6', 'max:60', 'confirmed'],
            'new_password_confirmation' => ['required', 'string', 'min:6', 'max:60'],
        ];
    }

    public function getFunctionName(): string
    {
        $action = $this->route()->getAction();
        $controllerAction = $action['controller'];
        list($controller, $method) = explode('@', $controllerAction);
        return $method;
    }
}
