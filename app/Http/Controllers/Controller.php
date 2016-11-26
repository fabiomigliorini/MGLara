<?php

namespace MGLara\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

use Carbon\Carbon;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Converte os campos com string de data em um array para Carbon
     * 
     * @param array $array_dados
     * @param array $campos_data
     * @param string $formato da Data
     */
    public static function datasParaCarbon ($array_dados = [], $campos_data = [], $formato = null)
    {
        foreach ($campos_data as $campo) {
            if (!empty($array_dados[$campo])) {
                if (!($array_dados[$campo] instanceof Carbon)) {
                    $array_dados[$campo] = new Carbon($array_dados[$campo], $formato);
                }
            }
        }
        return $array_dados;
    }
    
    /**
     * Decide se vai utilizar filtro Padrao, da Sessao ou do Request
     * 
     * @param Request $request
     * @param string $chave
     * @param array $filtro_padrao
     * @param array $campos_data
     * @return array
     */
    public static function filtroEstatico(Request $request, $chave = null, array $filtro_padrao = [], array $campos_data = [])
    {
        $chave = !empty($chave)?$chave:str_replace('/', '.', $request->route()->getPath());
        
        $filtro_request = $request->all();

        // Se veio request GET com filtro
        if (count($filtro_request)) {
            
            // Retorno sera o que veio no request
            $retorno = $filtro_request;
            
            // Limpa os valores armazenados anteriormente para aquela chave
            $filtros = $request->session()->get($chave);
            if (!empty($filtros)) {
                foreach ($filtros as $filtro => $valor) {
                    $request->session()->forget("$chave.$filtro");
                }
            }
            
            // Percorre todos parametros armazenando na sessão, com o prefixo da chave
            foreach ($filtro_request as $filtro => $valor) {
                $request->session()->put("$chave.$filtro", $valor);
            }
            
        } else {
            //dd($request->session()->all());
            // Busca se ja existe filtro na sessao
            $retorno = $request->session()->get($chave);
            
            // Se nao existia, utiliza filtro padrao
            if (empty($retorno)) {
                $retorno = $filtro_padrao;
            }
        }
        
        // Converte as datas
        $retorno = self::datasParaCarbon($retorno, $campos_data);
        
        return $retorno;

    }
    
}
