<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsManager
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user() || !$request->user()->isManager()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Forbidden.'], 403);
            }
            abort(403, 'Access denied. Admin or Manager role required.');
        }

        return $next($request);
    }
}
