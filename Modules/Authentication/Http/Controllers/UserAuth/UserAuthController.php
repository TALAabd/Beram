<?php

namespace Modules\Authentication\Http\Controllers\UserAuth;

use App\Http\Controllers\Controller;
use Modules\Authentication\Http\Resources\UserResource;
use Modules\Authentication\Http\Requests\Auth\UserAuthRequest;
use Modules\Authentication\Services\Auth\UserAuthService;
use Illuminate\Support\Facades\Auth;
use Modules\Authentication\Models\User;
use Illuminate\Validation\Validator;

class UserAuthController extends Controller
{
    public function __construct(private UserAuthService $userAuthService)
    {
    }

    public function login(UserAuthRequest $request)
    {
        $validatedData = $request->validated();
        $details = $this->userAuthService->login($validatedData);
        return $this->successResponse(
            ['roles' => $details],
            'userSuccessfullySignedIn',
            200,
            ''
        );
    }

    public function userPermissions(UserAuthRequest $request)
    {
        $validatedData = $request->validated();
        $details = $this->userAuthService->userPermissions($validatedData);
        return $this->successResponse(
            ['roles' => $details],
            'success',
            200
        );
    }

    public function getTripProviders()
    {
        $providers = $this->userAuthService->getTripProviders();
        return $this->successResponse(
            ['providers' => $providers],
            'success',
            200
        );
    }

    public function getHotelProviders()
    {
        $providers = $this->userAuthService->getHotelProviders();
        return $this->successResponse(
            ['providers' => $providers],
            'success',
            200
        );
    }

    public function userRole(UserAuthRequest $request)
    {
        $validatedData = $request->validated();
        $details = $this->userAuthService->userRole($validatedData);
        return $this->successResponse(
            ['roles' => $details],
            'success',
            200
        );
    }


    public function changePassword(UserAuthRequest $request)
    {
        $validatedData = $request->validated();
        $this->userAuthService->changePassword($validatedData);

        return $this->successResponse(
            null,
            'passwordChangedSuccessfully'
        );
    }

    public function getProfileDetails()
    {
        $user = Auth::guard('user')->user();

        $userRoles = User::where('email', $user->email)->first();
        $roles = $userRoles->roles;

        $user->roles = $roles;
        return $this->successResponse(
            $this->resource($user, UserResource::class),
            'dataFetchedSuccessfully'
        );
    }


    public function logout()
    {
        $this->userAuthService->logout();

        return $this->successResponse(
            null,
            'userSuccessfullySignedOut'
        );
    }
}
