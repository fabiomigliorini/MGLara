<?php

namespace MGLara\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class VerificaPermissao
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    protected $auth;
    
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    public function handle($request, Closure $next, $permission = null)
    {
        if (!$this->auth->guest()) {
            if ($request->user()->can($permission)) {
                return $next($request);
            }else{
               return view('errors.403')->with('message', $request->path());
            }
        }
        
        return $request->ajax ? response('Unauthorized.', 401) : redirect()->guest('auth/login');
    }

}