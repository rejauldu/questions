<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CacheRoute
{
    public function handle(Request $request, Closure $next, $minutes = 60): Response
    {
        $response = $next($request);

        // Set the cache headers
        $response->headers->set('Cache-Control', 'public, max-age=' . ($minutes * 60));
        $response->headers->set('Pragma', 'cache');
        
        return $response;
    }
}