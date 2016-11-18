<?php

namespace MGLara\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class RedeConfiavel
{
    
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }
    
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$this->verificaRedeConfiavel($request->ip())) {
            if ($this->auth->guest()) {
                if ($request->ajax()) {
                    return response('Unauthorized.', 401);
                } else {
                    return redirect()->guest('auth/login');
                }
            }
        }        
        return $next($request);
    }
    
    public function verificaRedeConfiavel ($ip)
    {
        // Pega redes confiaveis da variavel de ambiente
        // formato : INICIOFAIXA1-FIMFAIXA1;INICIOFAIXA2-FIMFAIXA2;...INICIOFAIXAN-FIMFAIXAN
        $redes = explode(';', env('REDESCONFIAVEIS'));
        $ip = ip2long($ip);
        
        foreach ($redes as $rede) {
            $rede = explode('-', $rede);
            $inicial = ip2long($rede[0]);
            $final = ip2long($rede[1]);
            if ($ip >= $inicial && $ip <= $final) {
                return true;
            }
        }
        return false;
        
    }    
}
