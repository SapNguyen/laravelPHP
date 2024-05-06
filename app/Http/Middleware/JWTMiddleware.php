<?php

namespace App\Http\Middleware;

use Closure;
// use JWTAuth;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth as FacadesJWTAuth;

class JWTMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $user = FacadesJWTAuth::parseToken()->authenticate();
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}
