<?php

namespace App\Http\Middleware;

use Closure;

class ListenPort
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$ports)
    {

        if (in_array($request->getPort(), $ports)) {
            return $next($request);
        }
        abort(403);

    }
}
