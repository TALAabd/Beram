<?php

namespace  Modules\Authentication\Http\Controllers;

use Modules\Authentication\Http\Requests\UserRequest;
use Modules\Authentication\RepositoryInterface\UserRepositoryInterface;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Authentication\Http\Resources\UserResource;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        if (Auth::guard('user')->user()) {
            $this->middleware('permission:users_manager', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);
        }
        $this->userRepository = $userRepository;
    }

    public function index()
    {
        $users = $this->userRepository->getUsers();
        return $this->successResponse(
            $this->resource($users, UserResource::class),
            'dataFetchedSuccessfully'
        );
    }


    public function show($id)
    {
        $user = $this->userRepository->showUser($id);
        return $this->successResponse(
            $this->resource($user, UserResource::class),
            'dataFetchedSuccessfully'
        );
    }


    public function store(UserRequest $request)
    {
        $validatedData = $request->validated();
        DB::beginTransaction();
        $user = $this->userRepository->createUser($validatedData);
        if ($request->file('image')->isValid()) {
            $user->addMedia($request->file('image'))->toMediaCollection('thumbnail');
        }
        DB::commit();
        return $this->successResponse(
            $this->resource($user, UserResource::class),
            'dataAddedSuccessfully'
        );
    }


    public function update(UserRequest $request, $id)
    {
        $validatedData = $request->validated();
        DB::beginTransaction();
        $user = $this->userRepository->updateUser($id, $validatedData);
        DB::commit();
        return $this->successResponse(
            $this->resource($user, UserResource::class),
            'dataUpdatedSuccessfully'
        );
    }


    public function destroy($id)
    {
        DB::beginTransaction();
        $this->userRepository->deleteUser($id);
        DB::commit();
        return $this->successResponse(
            null,
            'dataDeletedSuccessfully'
        );
    }


    public function updateStatus($id)
    {
        DB::beginTransaction();
        $user = $this->userRepository->findUserById($id);
        $user->updateStatus();
        DB::commit();
        return $this->successResponse(
            $this->resource($user, UserResource::class),
            'dataUpdatedSuccessfully'
        );
    }
}
