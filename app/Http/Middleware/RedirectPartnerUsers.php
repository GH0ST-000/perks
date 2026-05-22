<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectPartnerUsers
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user?->isPartner() && ! $request->routeIs('partner.*') && ! $request->routeIs('logout')) {
            return redirect()->route('partner.dashboard');
        }

        return $next($request);
    }
}
