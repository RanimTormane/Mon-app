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

    // Middleware pour vérifier le rôle
public function handle($request, Closure $next, ...$guards)
{  Log::info('Authentication failed', ['email' => $request->email]);
    $user = JWTAuth::parseToken()->authenticate();

    if ($user->role !== 'Admin') {
        return response()->json(['error' => 'Access denied'], 403);
    }

    return $next($request);
}

}
