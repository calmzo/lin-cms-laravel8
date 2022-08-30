<?php

namespace App\Http\Middleware;

use App\Exceptions\Token\ForbiddenException;
use App\Lib\Authenticator\Authenticator;
use Closure;
use Illuminate\Http\Request;

class Permission
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
        $auth = (new Authenticator($request))->check();

        if (!$auth) {
            throw new ForbiddenException();
        }
        return $next($request);
    }
}
