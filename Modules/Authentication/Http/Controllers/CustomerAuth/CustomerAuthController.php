<?php

namespace Modules\Authentication\Http\Controllers\CustomerAuth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Authentication\Http\Requests\Auth\CustomerAuthRequest;
use Modules\Authentication\Http\Resources\CustomerResource;
use Modules\Authentication\Services\Auth\CustomerAuthService;
use Illuminate\Support\Facades\Auth;

class CustomerAuthController extends Controller
{
    public function __construct(private CustomerAuthService $customerAuthService)
    {
    }

    public function login(CustomerAuthRequest $request)
    {
        $validatedData = $request->validated();
        $token = $this->customerAuthService->login($validatedData);
        return $this->successResponse(
            $this->resource(Auth::guard('customer')->user(), CustomerResource::class),
            'userSuccessfullySignedIn',
            200,
            $token
        );
    }
    public function login2(CustomerAuthRequest $request)
    {
        $validatedData = $request->validated();
        $token = $this->customerAuthService->login2($validatedData);
        $user  = Auth::guard('customer')->user();
        $user->token = $token;
        return $this->successResponse(
            $this->resource($user, CustomerResource::class),
            'userSuccessfullySignedIn',
            200,
            $token
        );
    }
    public function SiteRegister(CustomerAuthRequest $request)
    {
        $validator = $request->validated();
        $token = $this->customerAuthService->SiteRegister($validator);
        $user  = Auth::guard('customer')->user();
        $user->token = $token;
        return $this->successResponse(
            $this->resource($user, CustomerResource::class),
            'userSuccessfullyRegistered',
            200

        );
    }
    public function register(CustomerAuthRequest $request)
    {
        $validatedData = $request->validated();
        $token = $this->customerAuthService->register($validatedData);

        return $this->successResponse(
            $this->resource(Auth::guard('customer')->user(), CustomerResource::class),
            'userSuccessfullyRegistered',
            200,
            $token
        );
    }

    public function logout(CustomerAuthRequest $request)
    {
        $this->customerAuthService->logout();

        return $this->successResponse(
            null,
            'userSuccessfullySignedOut'
        );
    }

    public function check_identity(CustomerAuthRequest $request)
    {
        $validatedData = $request->validated();
        $response = $this->customerAuthService->check_identity($validatedData);
        return $this->successResponse(
            $response,
            "requestedSuccessfully"
        );
    }

    public function changePassword(CustomerAuthRequest $request)
    {
        $validatedData = $request->validated();
        $this->customerAuthService->changePassword($validatedData);

        return $this->successResponse(
            null,
            'passwordChangedSuccessfully'
        );
    }

    public function saveFcmToken(Request $request)
    {
        $this->customerAuthService->createOrUpdateFcmToken($request);
        return $this->successResponse(
            null,
            'requestedSuccessfully'
        );
    }

    public function checkToken()
    {
        return $this->successResponse(
            null,
            'validToken'
        );
    }
}
