<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as MiddlewareAuthenticate;
use Illuminate\Http\Request;

class Authenticate extends MiddlewareAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    protected function redirectTo($request)
    {
        // API routes never redirect
        if ($request->is('api/*')) {
            return null;
        } 
    }
}
