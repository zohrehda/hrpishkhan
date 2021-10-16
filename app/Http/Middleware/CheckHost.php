<?php

namespace App\Http\Middleware;

use Closure;

class CheckHost
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


        if ($_SERVER['HTTP_HOST']!=config('app.host'))
        {
            header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad Request');
            exit;
        }

        return $next($request);
    }
}
