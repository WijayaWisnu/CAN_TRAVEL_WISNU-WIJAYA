<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckApiKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response {
    $apiKey = $request->header('X-API-KEY');
    if ($apiKey !== env('API_KEY_SECRET')) {
        return response()->json([
            'status' => 'error',
            'message' => 'Unauthorized: Invalid API Key'
        ], 401);
    }
    return $next($request);
}
}
