<?php

namespace Modules\Authentication\Repositories;

use Modules\Authentication\RepositoryInterface\UserRepositoryInterface;
use Modules\Authentication\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Traits\ModelHelper;

class UserRepository implements UserRepositoryInterface
{

    use ModelHelper;
    public function getUsers()
    {
        return User::with('roles')->where('role','!=','employee')->orderBy('id','Desc')->get();
    }

    public function createUser($attributes)
    {
        $attributes['password'] = Hash::make($attributes['password']);
        $user = User::create($attributes);
        $user->assignRole($attributes['role']);
        return $user;
    }

    public function findUserById($userId)
    {
        $user = $this->findByIdOrFail(User::class, 'user', $userId);
        return $user;
    }

    public function updateUser($userId, $attributes)
    {
        $user = $this->findUserById($userId);
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
        $user->save();
        $user->syncRoles($attributes['role']);
        return $user;
    }

    public function showUser($userId)
    {
        $user = $this->findUserById($userId);
        $user->roles;
        return $user;
    }



    public function deleteUser($userId)
    {
        $user = $this->findUserById($userId);
        $user->delete();
        return true;
    }
}
