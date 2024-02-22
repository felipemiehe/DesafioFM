<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;

class JWTAuthMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        $token = $request->cookie('jwt');

        if (!$token) {
            return response()->json(['message' => 'Acesso não autorizado'], 401);
        }

        $token = str_replace("jwt=", "", $token);

        try {
            $decoded = $this->decodeToken($token);
        } catch (Exception $e) {
            logger($e);
            return response()->json(['message' => 'Acesso não autorizado'], 401);
        }

        if (!$this->userHasPermission($decoded, $role)) {
            return response()->json(['message' => 'Acesso não autorizado'], 403);
        }

        return $next($request);
    }

    protected function decodeToken($token)
    {
        $secret = env('JWT_SECRET');
        return JWT::decode($token, new Key($secret, 'HS256'));
    }

    protected function userHasPermission($user, $role)
    {
        if (!isset($user->roles)) {
            return false;
        }

        $userRoles = array_column($user->roles, 'roles');

        return in_array($role, $userRoles);
    }
}
