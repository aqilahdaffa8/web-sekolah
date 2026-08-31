<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Restrict access to users that have AT LEAST ONE of the given permissions.
 *
 * Usage:
 *   ->middleware('permission:manage_users')
 *   ->middleware('permission:manage_users,manage_roles')  // OR
 */
class CheckPermission
{
    public function handle(Request $request, Closure $next, string ...$permissions): Response
    {
        $user = $request->user();

        if (! $user) {
            return response()->json([
                'message' => 'Unauthenticated.',
                'code'    => 401,
            ], 401);
        }

        $user->loadMissing('roles.permissions');

        foreach ($permissions as $permission) {
            if ($user->hasPermission(trim($permission))) {
                return $next($request);
            }
        }

        return response()->json([
            'message' => 'Forbidden. Required permission: ' . implode(' or ', $permissions),
            'code'    => 403,
        ], 403);
    }
}
