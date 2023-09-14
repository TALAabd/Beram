<?php

use Exception;
use Throwable;
class UserIsAlreadyMember extends Exception
{
    public function render($request, Throwable $exception)
    {
        return response()->json([
            'status'   => 'error',
            'message'  => 'User is already a member',
        ], 409);
        
    }
}
