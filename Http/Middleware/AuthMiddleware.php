<?php

namespace Innerent\People\Http\Middleware;

use App\Http\Middleware\Authenticate;
use Closure;

class AuthMiddleware extends Authenticate
{
    public function handle($request, Closure $next, ...$guards)
    {
        $request->headers->set('authorization', 'Bearer ' . $request->cookie('laravel_token'));

        $this->authenticate($request, $guards);

        return $next($request);
    }
}
