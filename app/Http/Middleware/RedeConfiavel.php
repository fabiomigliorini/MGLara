<?php

namespace MGLara\Http\Middleware;

use Closure;
use Auth;
class RedeConfiavel
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
        if (!$this->verificaRedeConfiavel($request->ip())) {
            if(!Auth::user()){
                return redirect()->guest('auth/login');
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
