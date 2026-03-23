<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        // Tenants belong in the resident portal, not the admin
        if ($user->isTenant()) {
            return redirect()->route('portal.index');
        }

        // super_admin and admin_property are allowed
        if ($user->isSuperAdmin() || $user->isAdminProperty() || $user->isOwner()) {
            return $next($request);
        }

        // User has no recognised admin role — send to portal as fallback
        return redirect()->route('portal.index');
    }
}
