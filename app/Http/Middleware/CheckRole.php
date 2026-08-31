<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Restrict access to users that carry AT LEAST ONE of the given roles.
 *
 * Usage in routes:
 *   ->middleware('role:Super Admin')
 *   ->middleware('role:Hubin,Super Admin')   // OR logic
 */
class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            return response()->json([
                'message' => 'Unauthenticated.',
                'code'    => 401,
            ], 401);
        }

        // Load roles once (eager-loaded by Sanctum auth guard)
        $user->loadMissing('roles');

        foreach ($roles as $role) {
            if ($user->hasRole(trim($role))) {
                return $next($request);
            }
        }

        return response()->json([
            'message' => 'Forbidden. Required role: ' . implode(' or ', $roles),
            'code'    => 403,
        ], 403);
    }
}
