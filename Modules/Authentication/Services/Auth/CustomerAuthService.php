<?php

namespace Modules\Authentication\Services\Auth;

use Modules\Authentication\Models\Customer;
use Modules\Authentication\Models\VerificationCodes;
use App\Traits\ModelHelper;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Modules\Authentication\Services\CustomerService;

class CustomerAuthService
{
    public function __construct(private CustomerService $CustomerService)
    {
    }

    use ModelHelper;

    public function login($validatedData)
    {
        $customer = Customer::where('phone', $validatedData['phone'])->first();

        if (!$customer) {
            throw new Exception(__('messages.credentialsError'), 401);
        }

        if (!$token = Auth::guard('customer')->attempt([
            'phone'    => $validatedData['phone'],
            'password' => $validatedData['password']
        ])) {
            throw new Exception(__('messages.incorrect_password'), 401);
        }

        if ($customer->status != 1) {
            throw new Exception(__('messages.blockedUser'), 401);
        }

        if (isset($validatedData['fcm_token'])) {
            $customer->fcm_token = $validatedData['fcm_token'];
            $customer->save();
        }
        return $token;
    }
    public function login2($validatedData)
    {
        $customer = Customer::where('email', $validatedData['email'])->first();

        if (!$customer) {
            throw new Exception(__('messages.credentialsError'), 401);
        }

        if (!$token = Auth::guard('customer')->attempt([
            'email'    => $validatedData['email'],
            'password' => $validatedData['password']
        ])) {
            throw new Exception(__('messages.incorrect_password'), 401);
        }

        if ($customer->status != 1) {
            throw new Exception(__('messages.blockedUser'), 401);
        }

        if (isset($validatedData['fcm_token'])) {
            $customer->fcm_token = $validatedData['fcm_token'];
            $customer->save();
        }

        return $token;
    }
    public function SiteRegister($validatedData)
    {
        $attemptedData = [
            'email' => $validatedData['email'],
            'password' => $validatedData['password']
        ];

        $validatedData['password'] = Hash::make($validatedData['password']);

        DB::beginTransaction();
        $customer = Customer::create($validatedData);
        DB::commit();
        return $this->login2($attemptedData);
    }

    public function register($validatedData)
    {
        $attemptedData = [
            'phone'    => $validatedData['phone'],
            'password' => $validatedData['password']
        ];

        $validatedData['password'] = Hash::make($validatedData['password']);

        DB::beginTransaction();

        $customer = Customer::create($validatedData);

        DB::commit();
        return $this->login($attemptedData);
    }


    public function changePassword($validatedData)
    {
        /**
         * @var $customer=Modules\Authentication\Models\Customer
         */

        $customer = Auth::guard('customer')->user();

        DB::beginTransaction();

        $customer->update(['password' => Hash::make($validatedData['new_password'])]);

        DB::commit();
    }


    public function reset_password_request($validatedData)
    {
        /**
         * @var $customer=Modules\Authentication\Models\Customer
         */
        DB::beginTransaction();

        $this->customer_existed($validatedData);
        $code = rand(100000, 999999);

        $response = VerificationCodes::updateOrCreate(
            [
                'verification_by' => $validatedData['verification_by'],
                'identity' => $validatedData['phone_or_email'],
            ],
            [
                'code' => $code,
                'created_at' => now(),
            ]
        );
        //Send code to phone number or email
        DB::commit();
        return $response;
    }

    public function check_identity($validatedData)
    {
        /**
         * @var $customer=Modules\Authentication\Models\Customer
         */
        $this->customer_registered($validatedData);
        DB::beginTransaction();
        $code = rand(100000, 999999);
        $response = VerificationCodes::updateOrCreate(
            [
                'verification_by' => $validatedData['verification_by'],
                'identity' => $validatedData['phone_or_email'],
            ],
            [
                'code' => $code,
                'created_at' => now(),
            ]
        );
        //Send code to phone number or email
        DB::commit();
        return $response;
    }

    public function otp_verification_submit($validatedData)
    {
        DB::beginTransaction();

        $password_reset = VerificationCodes::where('code', $validatedData['code'])
            ->where('identity', $validatedData['phone_or_email'])
            ->first();
        if (!isset($password_reset)) {
            throw new Exception(__('auth.verificationCodeIsWrong'), 401);
        }
        $password_reset->delete();
        DB::commit();
    }


    public function reset_password_submit($validatedData)
    {
        DB::beginTransaction();
        $customer = $this->customer_existed($validatedData);
        $customer->update(['password' => Hash::make($validatedData['new_password'])]);
        DB::commit();
    }


    public function customer_existed($validatedData)
    {
        if ($validatedData['verification_by'] == 'phone')
            $customer = Customer::where('phone', $validatedData['phone_or_email'])->first();
        else
            $customer = Customer::where('email', $validatedData['phone_or_email'])->first();
        if (!$customer)
            throw new Exception(__('messages.credentialsError'), 401);
        else
            return $customer;
    }


    public function  customer_registered($validatedData)
    {
        if ($validatedData['verification_by'] == 'phone')
            $customer = Customer::where('phone', $validatedData['phone_or_email'])->first();
        else
            $customer = Customer::where('email', $validatedData['phone_or_email'])->first();
        if ($customer)
            throw new Exception(__('messages.ThephoneNumberOrEmailAlreadyExists'), 401);
    }


    public function createOrUpdateFcmToken($request)
    {
        DB::beginTransaction();

        $customer = $this->CustomerService->find(Auth::guard('customer')->user()->id);
        $customer->fcm_token = $request->fcm_token;
        $customer->save();

        DB::commit();

        return true;
    }

    public function logout()
    {
        Auth::guard('customer')->logout();
    }
}
