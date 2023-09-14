<?php

namespace Modules\Authentication\RepositoryInterface;

interface CustomersRepositoryInterface
{
    public function getCustomers();
    public function findCustomerById($customerId);
    public function showCustomer($customersId);
    public function deleteCustomer($customersId);
    public function updateCustomer($validatedData);

}
