<?php

namespace Modules\Authentication\RepositoryInterface;


interface UserRepositoryInterface
{
    public function getUsers();
    public function createUser($attributes);
    public function updateUser($userId, $attributes);
    public function findUserById($userId);
    public function showUser($userId);
    public function deleteUser($userId);
}
