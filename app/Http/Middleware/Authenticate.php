<?php

namespace App\Http\Middleware;

use App\Exceptions\AuthFailedException;
use App\Exceptions\BusinessException;
use App\Utils\CodeResponse;
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
        if ($request->expectsJson() || in_array('cms', $guards)) {
            throw new AuthFailedException();
        }
        parent::unauthenticated($request, $guards);
    }
}
