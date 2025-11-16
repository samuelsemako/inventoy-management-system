<?php

namespace App\Http\Middleware;
use Closure;
class ForceJsonResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $request->headers->set('Accept', 'application/json');
        return $next($request);
    }
}