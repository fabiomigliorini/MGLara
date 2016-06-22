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
        $key = $request->route()->uri();//->getName();
        
        // Retrieve all request parameters
        $parameters = $request->all();

        // IF  no request parameters found
        // AND the session key exists (i.e. an previous URL has been saved)
        if (!count($parameters) && Session::has($key)) {
            // THEN redirect to the saved URL
            //return redirect(Session::get($key));
        }

        if (count($parameters)) {
            foreach ($parameters as $parametro => $value)
            {
                Session::put("$key.$parametro", $value);
            }
        
        }
        
        // IF there are request parameters
        //if (count($parameters)) {
            // THEN save them in the session
            //Session::put($key, $request->fullUrl());
        //}

        // Process and return the request
        return $next($request);
    }
}
