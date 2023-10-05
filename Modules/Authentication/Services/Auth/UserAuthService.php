<?php

namespace Modules\Authentication\Services\Auth;

use Modules\Authentication\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Traits\ModelHelper;
use Exception;
use Modules\Authentication\Models\Customer;
use Spatie\Permission\Models\Role;

class UserAuthService extends Controller
{
    use ModelHelper;

    public function login($validatedData)
    {
        $user = User::where('email', $validatedData['email'])->first();
        if (!$user) {
            throw new Exception(__('messages.credentialsError'), 401);
        }

        $attemptedData = [
            'email'    => $user->email,
            'password' => $validatedData['password']
        ];

        if (!$token = Auth::guard('user')->attempt($attemptedData)) {
            throw new Exception(__('messages.incorrect_password'), 401);
        }

        if ($user->status != 1) {
            throw new Exception(__('messages.blockedUser'), 401);
        }

        $roles = $user->roles;
        foreach ($roles as $role)
            $role->permissions;

        return [
            'roles' => $roles,
            'token' => $token
        ];
    }

    public function userPermissions($validatedData)
    {
        $user = Auth::guard('user')->user();
        if (!$user) {
            throw new Exception(__('messages.credentialsError'), 401);
        }

        $roles = $user->roles;
        foreach ($roles as $role)
            $role->permissions;

        return $roles;
    }

    public function getTripProviders()
    {
        $providers = User::where('role', 'provider')->orWhere('role', 'trip_provider')->get(['id', 'first_name', 'last_name', 'role']);
        $data = [];
        foreach ($providers as $provider) {
            $data[] = [
                'id'         => $provider->id,
                'first_name' => $provider->first_name,
                'last_name'  => $provider->last_name,
                'role'       => $provider->role,
            ];
        }
        return $data;
    }
    

    public function getHotelProviders()
    {
        $providers = User::where('role', 'provider')->orWhere('role', 'hotel_provider')->get(['id', 'first_name', 'last_name', 'role']);
        $data = [];
        foreach ($providers as $provider) {
            $data[] = [
                'id'         => $provider->id,
                'first_name' => $provider->first_name,
                'last_name'  => $provider->last_name,
                'role'       => $provider->role,
            ];
        }
        return $data;
    }

    public function userRole($validatedData)
    {

        return Role::get();
    }

    public function changePassword($validatedData)
    {
        /**
         * @var $user=App\Models\Employee
         */
        $user = Auth::guard('user')->user();

        DB::beginTransaction();

        $user->update(['password' => Hash::make($validatedData['new_password'])]);

        DB::commit();
    }

    public function logout()
    {
        Auth::guard('user')->logout();
    }
}
