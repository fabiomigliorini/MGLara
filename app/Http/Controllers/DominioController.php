<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;

use MGLara\Http\Requests;
use MGLara\Http\Controllers\Controller;

use MGLara\Models\Dominio\ArquivoEstoque;
use MGLara\Models\Filial;

class DominioController extends Controller
{
    public function estoque(Request $request)
    {
        $ret['mes'] = $request->mes;
        $ret['codfilial'] = $request->codfilial;
        $ret['resultado'] = true;
        
        if (empty($request->mes))
        {
            $ret['resultado'] = false;
            $ret['erros'][] = 'Mês não Informado!';
        }
        
        if (empty($request->codfilial))
        {
            $ret['resultado'] = false;
            $ret['erros'][] = 'Filial não Informada!';
        }
        
        if ($ret['resultado'])
        {
            $mes = \Carbon\Carbon::createFromFormat('d/m/Y H:i:s', "01/$request->mes 00:00:00");
            $filial = Filial::findOrFail($request->codfilial);
            $arquivo = new ArquivoEstoque($mes, $filial);
            $arquivo->processa();
            $ret['resultado'] = $arquivo->grava();
        }
        
        return json_encode($ret);
        
    }
    
    public function index()
    {
        return view('dominio.index');
    }
}
