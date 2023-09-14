<?php

namespace Modules\Authentication\RepositoryInterface;


interface EmployeeRepositoryInterface
{
    public function getEmployees();
    public function createEmployee($attributes);
    public function updateEmployee($employeeId, $attributes);
    public function findEmployeeById($employeeId);
    public function showEmployee($employeeId);
    public function deleteEmployee($employeeId);
    public function getEmployeePermissions();
}
