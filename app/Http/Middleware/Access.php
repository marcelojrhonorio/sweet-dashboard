<?php

namespace App\Http\Middleware;

use Closure;

class Access
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
        if (!\Session::has('email') && !\Session::has('api_key')) {
            return redirect()->route('login');
        }

        return $next($request);
    }
}
