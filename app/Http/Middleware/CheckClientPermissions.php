<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Clients;
class CheckClientPermissions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $client = Clients::where('instagram_id', $request->input('instagram_id'))->first();

        if (!$client || !in_array($permission, json_decode($client->permissions ?? '[]', true))) {
            return response()->json(['error' => 'Accès refusé'], 403);
        }

        return $next($request);

    }
}
