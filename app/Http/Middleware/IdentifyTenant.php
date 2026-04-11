<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IdentifyTenant
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Get the tenant ID (e.g., from the authenticated user)
        $tenantId = $request->user()?->library_id;
        if (!$tenantId) {
            return response()->json(['error' => 'Tenant not identified'], 403);
        }
        // 2. Store it in a "TenantManager" or the Service Container
        // This makes the ID accessible anywhere in the app without passing it around.
        app()->instance('current_tenant_id', $tenantId);
        return $next($request);
    }
}
