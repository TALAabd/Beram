<?php

namespace Modules\Authentication\Repositories;

use Modules\Authentication\RepositoryInterface\EmployeeRepositoryInterface;
use Modules\Authentication\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Traits\ModelHelper;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;

class EmployeeRepository implements EmployeeRepositoryInterface
{

    use ModelHelper;
    public function getEmployees()
    {
        $user = Auth::guard('user')->user();
        $employees = User::where('id', $user->id)->first()->children;
        return $employees;
    }

    public function createEmployee($attributes)
    {
        $user = Auth::guard('user')->user();

        $attributes['password'] = Hash::make($attributes['password']);
        $attributes['role'] = 'employee';
        $attributes['parent_id'] = $user->id;
        $user = User::create($attributes);

        $user->givePermissionTo($attributes['permissions']);

        return $user;
    }

    public function findEmployeeById($userId)
    {
        $user = $this->findByIdOrFail(User::class, 'user', $userId);
        return $user;
    }

    public function updateEmployee($userId, $attributes)
    {
        $user = $this->findEmployeeById($userId);
        $user->name = $attributes['name'];
        $user->first_name = $attributes['first_name'];
        $user->last_name = $attributes['last_name'];
        $user->email = $attributes['email'];
        if (isset($attributes['password']))
            $user->password = bcrypt($attributes['password']);
        $user->address = $attributes['address'];
        $user->phone = $attributes['phone'];
        $user->birthday = $attributes['birthday'];
        $user->city = $attributes['city'];
        $user->state = $attributes['state'];
        $user->country = $attributes['country'];
        $user->zip_code = $attributes['zip_code'];
        $attributes['role'] = 'employee';
        $user->save();
        $user->syncPermissions($attributes['permissions']);
        return $user;
    }


    public function showEmployee($userId)
    {
        $user = $this->findEmployeeById($userId);
        $permissions = [];
        $Employee_Permissions = User::Employee_Permissions;
        for ($i = 0; $i < count($Employee_Permissions); $i++) {
            $permissions[] = [
                'name' => $Employee_Permissions[$i],
                'status' => $user->hasPermissionTo($Employee_Permissions[$i]),
            ];
        }
        $user->permissions = $permissions;
        return $user;
    }



    public function deleteEmployee($userId)
    {
        $user = $this->findEmployeeById($userId);
        $user->delete();
        return true;
    }

    public function getEmployeePermissions()
    {
        return Permission::whereIn('name', User::Employee_Permissions)->get();
    }
}
