<?php

namespace MGLara\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ParametrosGet
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $key = $request->route()->getName();
        
        $parameters = $request->all();

        if (count($parameters)) {
            foreach ($parameters as $parametro => $value)
            {
                Session::put("$key.$parametro", $value);
            }
        }

        return $next($request);
    }
}
