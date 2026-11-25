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
        $user = $request->user();

        // User must be logged in.
        if (!$user) {
            return redirect()->route('login');
        }

        // Check whether the user has the required permission.
        if (!$user->hasPermission($permission)) {
            abort(403, 'You are not authorized to access this page.');
        }

        return $next($request);
    }
}
