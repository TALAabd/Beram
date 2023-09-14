<?php

namespace  Modules\Authentication\Http\Controllers;

use Modules\Authentication\RepositoryInterface\CustomersRepositoryInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Authentication\Http\Resources\CustomerResource;
use Illuminate\Support\Facades\DB;
use Modules\Authentication\Http\Requests\CustomerRequest;

class CustomerController extends Controller
{
    protected $customerRepository;

    public function __construct(CustomersRepositoryInterface $customerRepository)
    {
        $this->middleware('permission:customers_manager', ['only' => ['index', 'show', 'destroy']]);
        $this->customerRepository = $customerRepository;
    }

    public function index()
    {
        $users = $this->customerRepository->getCustomers();
        return $this->successResponse(
            $this->resource($users, CustomerResource::class),
            'dataFetchedSuccessfully'
        );
    }

    public function show($customer)
    {
        $user = $this->customerRepository->showCustomer($customer);
        return $this->successResponse(
            $this->resource($user, CustomerResource::class),
            'dataFetchedSuccessfully'
        );
    }

    public function update(CustomerRequest $request)
    {
        $validatedData = $request->validated();
        $user = $this->customerRepository->updateCustomer($validatedData);
        return $this->successResponse(
            $this->resource($user, CustomerResource::class),
            'dataFetchedSuccessfully'
        );
    }

    public function destroy($customer)
    {
        DB::beginTransaction();
        $this->customerRepository->deleteCustomer($customer);
        DB::commit();
        return $this->successResponse(
            null,
            'dataDeletedSuccessfully'
        );
    }

    public function updateStatus($customerId)
    {
        DB::beginTransaction();
        $user = $this->customerRepository->findCustomerById($customerId);
        $user->updateStatus();
        DB::commit();
        return $this->successResponse(
            $this->resource($user, CustomerResource::class),
            'dataUpdatedSuccessfully'
        );
    }
}
