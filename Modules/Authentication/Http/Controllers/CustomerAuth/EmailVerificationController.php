<?php

namespace Modules\Authentication\Http\Controllers\CustomerAuth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Authentication\Http\Requests\Auth\CustomerAuthRequest;

class EmailVerificationController extends Controller
{
    public function check_email(CustomerAuthRequest $request)
    {
    }

    public function verify_email(CustomerAuthRequest $request)
    {
    }
}
