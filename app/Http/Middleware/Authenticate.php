<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth; // Correct facade
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Log;


class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('login');
    }


public function handle($request, Closure $next, ...$guards)
{
    try {
        Log::info('Trying to authenticate user with JWT token');

        $user = JWTAuth::parseToken()->authenticate();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

       /* if ($user->role !== 'Admin') {
            return response()->json(['error' => 'Access denied'], 403);
        }*/

        return $next($request);

    } catch (JWTException $e) {
        Log::error('JWT Exception: ' . $e->getMessage());
        return response()->json(['error' => 'Token not provided or invalid'], 401);
    }
}

}
