<?php

namespace Modules\Authentication\Services;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelHelper;
use Modules\Authentication\Models\User;

class UserService
{
    use ModelHelper;

    public function getAll()
    {
        return User::all();
    }

    public function find($userId)
    {
        return $this->findByIdOrFail(User::class,'user', $userId);
    }

    public function create($validatedData)
    {
        DB::beginTransaction();

        $user = User::create($validatedData);

        DB::commit();

        return $user;
    }

    public function update($validatedData, $userId)
    {
        $user = $this->find($userId);

        DB::beginTransaction();

        $user->update($validatedData);

        DB::commit();

        return true;
    }

    public function delete($userId)
    {
        $user = $this->find($userId);

        DB::beginTransaction();

        $user->delete();

        DB::commit();

        return true;
    }
}
