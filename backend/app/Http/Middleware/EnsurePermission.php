<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class EnsurePermission
{
    public function handle(Request $request, Closure $next, string $permission)
    {
        $user = $request->user();

        if (! $user || ! $user->role) {
            throw new AccessDeniedHttpException('Permission check failed: no user role.');
        }

        $role = $user->role;
        $isAllowed = $role->permissions()
            ->where('permission', $permission)
            ->where('is_allowed', true)
            ->exists();

        if (! $isAllowed) {
            throw new AccessDeniedHttpException('Permission denied.');
        }

        return $next($request);
    }
}
