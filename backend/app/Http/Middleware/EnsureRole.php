<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class EnsureRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = $request->user();

        if (! $user || ! $user->role) {
            throw new AccessDeniedHttpException('User role is required.');
        }

        $matches = empty($roles) || in_array($user->role->slug, $roles, true);

        if (! $matches) {
            throw new AccessDeniedHttpException('Insufficient role permissions.');
        }

        return $next($request);
    }
}
