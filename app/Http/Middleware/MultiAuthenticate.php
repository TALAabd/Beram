<?php

namespace App\Http\Middleware;
use Closure;
use Exception;
use Illuminate\Support\Facades\Auth;

class MultiAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string[]  ...$guards
     * @return mixed
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function handle($request, Closure $next, ...$guards)
    {
        if (Auth::guard('user')->check() || Auth::guard('customer')->check()) {
            $allowed = false;
            foreach ($guards as $guard) {
                if (Auth::guard('user')->check() && $guard == 'user') {
                    $allowed = true;
                }
                if (Auth::guard('customer')->check() && $guard == 'customer') {
                    $allowed = true;
                }
            }
            if (!$allowed) {
                 throw new Exception(__('messages.unauthorized'), 401);
            }
        } else {
             throw new Exception(__('messages.unauthenticated'), 401);
        }

        return $next($request);
    }
}
