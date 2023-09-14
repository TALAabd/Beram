<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UserCurrencyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */

    public function handle(Request $request, Closure $next)
    {
        if (!$request->has('currency')) {
            session()->put('currency', 'USD');
        } else {
            $currency = $request->input('currency');
            session()->put('currency', $currency);
        }
        return $next($request);
    }

    // public function handle(Request $request, Closure $next) {
    //     if (!$request->get('currency')) {
    //       $clientIP = $request->getClientIp();
    //       $localCurrency = geoip($clientIP)->getAttribute('currency');
    //       $request->getSession()->put([
    //         'currency' => $localCurrency,
    //       ]);
    //       return $next($request);
    //     }
    //     return $next($request);
    //   }


}
