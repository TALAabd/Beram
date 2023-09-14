<?php

namespace Modules\Authentication\Repositories;

use Modules\Authentication\RepositoryInterface\CustomersRepositoryInterface;
use Modules\Authentication\Models\Customer;
use App\Traits\ModelHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomerRepository implements CustomersRepositoryInterface
{
    use ModelHelper;
    public function getCustomers()
    {
        $customers = Customer::get();
        return $customers;
    }

    public function findCustomerById($customerId)
    {
        $customer = $this->findByIdOrFail(Customer::class, 'customer', $customerId);
        return $customer;
    }

    public function showCustomer($customerId)
    {
        $customer = $this->findCustomerById($customerId);
        return $customer;
    }

    public function deleteCustomer($customerId)
    {
        $customer = $this->findCustomerById($customerId);
        $customer->delete();
        return true;
    }

    public function updateCustomer($validatedData)
    {
        $customer = $this->findCustomerById(Auth::guard('customer')->user()->id);
        $customer->update($validatedData);
        return $customer;
    }

}
