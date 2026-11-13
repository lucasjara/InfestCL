<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PublicApiHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Security headers
        $response->headers->set(
            'X-Content-Type-Options',
            'nosniff'
        );

        $response->headers->set(
            'X-Frame-Options',
            'DENY'
        );

        $response->headers->set(
            'Referrer-Policy',
            'no-referrer'
        );

        // Cache policy
        if (
            in_array($request->method(), ['GET', 'HEAD'])
            && $response->isSuccessful()
        ) {
            $maxAge = max(
                0,
                (int) config('api.cache_max_age', 60)
            );

            $response->headers->set(
                'Cache-Control',
                "public, max-age={$maxAge}, s-maxage={$maxAge}"
            );
        } else {
            $response->headers->set(
                'Cache-Control',
                'no-store, max-age=0'
            );
        }

        return $response;
    }
}