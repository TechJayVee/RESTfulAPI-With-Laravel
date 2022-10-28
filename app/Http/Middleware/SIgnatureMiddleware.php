<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SIgnatureMiddleware
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
        $response = $next($request, $headerName = 'X-name');

        $response->headers->set($headerName, config('app.name'));

        return $response;
    }
}
