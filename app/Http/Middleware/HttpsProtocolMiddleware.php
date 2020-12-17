<?php

namespace App\Http\Middleware;

use Closure;

class HttpsProtocolMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
       if( ($request->header('x-forwarded-proto') <> 'https') && app()->environment('production')) {
        return redirect()->secure($request->getRequestUri());
    }
    if (!$request->secure() && app()->environment('production')) {
        return redirect()->secure($request->getRequestUri());
    }

    return $next($request);
    }
}
