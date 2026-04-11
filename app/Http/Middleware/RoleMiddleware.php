<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles  <-- This "splat" operator captures all roles from the route
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        // 1. Check if user is logged in
        // 2. Check if the user's role (Enum value) exists in the $roles array
        if (!$user || !in_array($user->role->value, $roles)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. This action requires specific permissions.'
            ], 403);
        }

        return $next($request);
    }
}
