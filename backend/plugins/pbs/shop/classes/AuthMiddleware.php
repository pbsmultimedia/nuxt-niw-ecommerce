<?php

namespace Pbs\Shop\Classes;

use Closure;
use Pbs\Shop\Classes\JWTAuth;
use Winter\User\Models\User;

class AuthMiddleware
{
    public function handle($request, Closure $next)
    {
        $token = $request->bearerToken();
        // print_r($request);

        if (!$token) {
            return response()->json(['error' => 'Token not provided'], 401);
        }

        $decoded = JWTAuth::decodeToken($token);

        if (!$decoded) {
            return response()->json(['error' => 'Invalid or expired token'], 401);
        }

        $user = User::find($decoded->sub);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Attach user to request
        $request->merge(['user' => $user]);

        return $next($request);
    }
}
