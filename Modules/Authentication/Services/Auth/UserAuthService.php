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
