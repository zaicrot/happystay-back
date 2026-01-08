<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Exceptions\HttpResponseException;

class Authenticate extends Middleware
{
    /**
     * For API requests, return a 401 response instead of redirecting to a login route.
     */
    protected function redirectTo($request): ?string
    {
        return null;
    }

    /**
     * Always return a JSON 401 instead of redirecting to a login route.
     */
    protected function unauthenticated($request, array $guards)
    {
        throw new HttpResponseException(response()->json([
            'message' => 'Unauthenticated.',
        ], 401));
    }
}
