<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Authentication\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelHelper;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    use ModelHelper;

    public function __construct()
    {
        $this->middleware('permission:roles_manager', ['only' => ['index', 'show', 'edit', 'update']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::get();
        return $this->successResponse(
            $roles,
            'dataFetchedSuccessfully'
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:roles,name',
        ]);
        DB::beginTransaction();
        $role = Role::create(['name' => $request->input('name')]);
        $data = [
            'id' => $role->id,
            'name' => $role->name,
            'permissions' => $role->permissions,
        ];
        DB::commit();
        return $this->successResponse(
            $data,
            'dataAddedSuccessfully'
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function edit($id)
    {
        DB::beginTransaction();
        $role = $this->findByIdOrFail(Role::class, 'role', $id);
        $permissions = Permission::get();
        $rolePermissions = $role->permissions->pluck('id')->toArray();
        $permissions = $permissions->map(function ($permission) use ($rolePermissions) {
            $permission->status = in_array($permission->id, $rolePermissions);
            return $permission;
        });
        DB::commit();
        return $this->successResponse(
            $permissions,
            'dataFetchedSuccessfully'
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'permission' => 'required|string',
            'status' => 'required|boolean',
        ]);
        DB::beginTransaction();
        $role = $this->findByIdOrFail(Role::class, 'role', $id);
        if ($request->input('status') == true)
            $role->givePermissionTo($request->input('permission'));
        else
            $role->revokePermissionTo($request->input('permission'));
        DB::commit();
        return $this->successResponse(
            $role,
            'dataUpdatedSuccessfully'
        );
    }

    public function getpermissions(Request $request)
    {
        DB::beginTransaction();
        $user = Auth::user();
        $user = User::where('id', $user->id)->first();
        $permissions=($user->role=="employee") ? $user->permissions : $user->getPermissionsViaRoles();
        DB::commit();
        return $this->successResponse(
            $permissions,
            'dataFetchedSuccessfully'
        );
    }
}
