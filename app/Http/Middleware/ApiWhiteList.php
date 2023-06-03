<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiWhiteList
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
        $allowedHost = explode(",", env('CONSUMER_HOSTNAMES'));
        if (!in_array($request->getClientIp(), $allowedHost)) {
            abort(403, "You are restricted to access the site.");
        }
        return $next($request);
    }
}
