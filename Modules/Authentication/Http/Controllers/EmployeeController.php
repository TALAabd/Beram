<?php

namespace  Modules\Authentication\Http\Controllers;

use Modules\Authentication\Http\Requests\EmployeeRequest;
use Modules\Authentication\RepositoryInterface\EmployeeRepositoryInterface;
use App\Http\Controllers\Controller;
use Modules\Authentication\Http\Resources\EmployeeResource;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    protected $employeeRepository;

    public function __construct(EmployeeRepositoryInterface $employeeRepository)
    {
        $this->middleware('permission:employees_manager', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);
        $this->employeeRepository = $employeeRepository;
    }

    public function index()
    {
        $users = $this->employeeRepository->getEmployees();
        return $this->successResponse(
            $this->resource($users, EmployeeResource::class),
            'dataFetchedSuccessfully'
        );
    }


    public function show($employee)
    {
        $user = $this->employeeRepository->showEmployee($employee);
        return $this->successResponse(
            $this->resource($user, EmployeeResource::class),
            'dataFetchedSuccessfully'
        );
    }


    public function store(EmployeeRequest $request)
    {
        $validatedData = $request->validated();
        DB::beginTransaction();
        $user = $this->employeeRepository->createEmployee($validatedData);
        DB::commit();
        return $this->successResponse(
            $this->resource($user, EmployeeResource::class),
            'dataAddedSuccessfully'
        );
    }

    public function update(EmployeeRequest $request, $employee)
    {
        $validatedData = $request->validated();
        DB::beginTransaction();
        $user = $this->employeeRepository->updateEmployee($employee, $validatedData);
        DB::commit();
        return $this->successResponse(
            $this->resource($user, EmployeeResource::class),
            'dataUpdatedSuccessfully'
        );
    }


    public function destroy($employee)
    {
        DB::beginTransaction();
        $this->employeeRepository->deleteEmployee($employee);
        DB::commit();
        return $this->successResponse(
            null,
            'dataDeletedSuccessfully'
        );
    }


    public function updateStatus($employee)
    {
        DB::beginTransaction();
        $user = $this->employeeRepository->findEmployeeById($employee);
        $user->updateStatus();
        DB::commit();
        return $this->successResponse(
            $this->resource($user, EmployeeResource::class),
            'dataUpdatedSuccessfully'
        );
    }


    public function getEmployeePermissions()
    {
        DB::beginTransaction();
        $permissions = $this->employeeRepository->getEmployeePermissions();
        DB::commit();
        return $this->successResponse(
            $permissions,
            'dataFetchedSuccessfully'
        );
    }
}
