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
        // Usa como chave o nome da rota
        $key = $request->route()->getName();
        //dd($request->route()->getActionName());
        
        // Pega todos os parametros que vieram no request
        $parameters = $request->all();

        // Percorre todos parametros armazenando na sessão, com o prefixo da chave
        if (count($parameters)) {
            foreach ($parameters as $parametro => $value) {
                Session::put("$key.$parametro", $value);
            }
        }

        // Continua 
        return $next($request);
    }
}
