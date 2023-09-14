<?php

namespace Modules\Authentication\Http\Controllers\CustomerAuth;

use App\Http\Controllers\Controller;
use Modules\Authentication\Http\Requests\Auth\CustomerAuthRequest;
use Modules\Authentication\Services\Auth\CustomerAuthService;


class ForgotPassword extends Controller
{

    public function __construct(private CustomerAuthService $customerAuthService)
    {
    }

    public function reset_password_request(CustomerAuthRequest $request)
    {
        $validatedData = $request->validated();
        $response = $this->customerAuthService->reset_password_request($validatedData);
        return $this->successResponse(
            $response,
            "requestedSuccessfully"
        );
    }

    public function otp_verification_submit(CustomerAuthRequest $request)
    {
        $validatedData = $request->validated();
        $response = $this->customerAuthService->otp_verification_submit($validatedData);
        return $this->successResponse(
            $response,
           'VerificationCompletedSuccessfully'
        );
    }


    public function reset_password_submit(CustomerAuthRequest $request)
    {
        $validatedData = $request->validated();
        $this->customerAuthService->reset_password_submit($validatedData);
        return $this->successResponse(
            null,
            'passwordChangedSuccessfully'
        );
    }
}
