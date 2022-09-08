<?php

namespace App\Http\Middleware;

use App\Exceptions\AuthFailedException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('login');
        }
    }

    protected function unauthenticated($request, array $guards)
    {
        if ($request->expectsJson() || in_array('admin', $guards) || in_array('api', $guards)) {
            throw new AuthFailedException();
        }
        parent::unauthenticated($request, $guards);
    }
}
