<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role): Response
    {
        if (!$request->user()) {

            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        if ($request->user()->role != $role) {

            return response()->json([
                'message' => 'Forbidden Access'
            ], 403);
        }

        return $next($request);
    }
}