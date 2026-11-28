<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    public function handle(
        Request $request,
        Closure $next,
        string $permission
    ): Response {
        // Admin and branch staff authentication uses the web guard.
        $user = $request->user('web');

        // Do not redirect to a login route that does not exist.
        if (!$user) {
            abort(401, 'Please log in with an authorized staff account.');
        }

        // Verify the required permission.
        if (!$user->hasPermission($permission)) {
            abort(403, 'You are not authorized to access this page.');
        }

        return $next($request);
    }
}
