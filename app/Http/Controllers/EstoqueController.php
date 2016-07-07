<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;

use MGLara\Http\Requests;
use MGLara\Http\Controllers\Controller;
use Carbon\Carbon;

use MGLara\Jobs\EstoqueCalculaCustoMedio;
use MGLara\Jobs\EstoqueGeraMovimentoNegocioProdutoBarra;
use MGLara\Jobs\EstoqueGeraMovimentoNegocio;
use MGLara\Jobs\EstoqueGeraMovimentoProduto;
use MGLara\Jobs\EstoqueGeraMovimentoProdutoVariacao;
use MGLara\Jobs\EstoqueGeraMovimentoPeriodo;

use MGLara\Models\EstoqueMes;
use MGLara\Models\NegocioProdutoBarra;
use MGLara\Models\Negocio;
use MGLara\Models\Produto;

class EstoqueController extends Controller
{
    
    /**
     * Calcula Custo Médio do Estoque
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function calculaCustoMedio(Request $request, $id)
    {
        $this->dispatch((new EstoqueCalculaCustoMedio($id))->onQueue('urgent'));
        return response()->json(['response' => 'Agendado']);
    }
    
    public function geraMovimentoNegocioProdutoBarra(Request $request, $id)
    {
        $this->dispatch((new EstoqueGeraMovimentoNegocioProdutoBarra($id))->onQueue('high'));
        return response()->json(['response' => 'Agendado']);
    }

    public function geraMovimentoNegocio(Request $request, $id)
    {
        //Delay de 2 segundos pra aguardar transação do Yii
        $this->dispatch((new EstoqueGeraMovimentoNegocio($id))->onQueue('medium')->delay(2));
        return response()->json(['response' => 'Agendado']);
    }
    
    public function geraMovimentoProduto(Request $request, $id)
    {
        $this->dispatch((new EstoqueGeraMovimentoProduto($id))->onQueue('low'));
        return response()->json(['response' => 'Agendado']);
    }
    
    public function geraMovimentoProdutoVariacao(Request $request, $id)
    {
        $this->dispatch((new EstoqueGeraMovimentoProdutoVariacao($id))->onQueue('low'));
        return response()->json(['response' => 'Agendado']);
    }
    
    public function geraMovimentoPeriodo(Request $request)
    {
        $inicial = Carbon::createFromFormat('d/m/Y H:i:s', $request->inicial); // 1975-05-21 22:00:00
        $final = Carbon::createFromFormat('d/m/Y H:i:s', $request->final); // 1975-05-21 22:00:00

        $this->dispatch((new EstoqueGeraMovimentoPeriodo($inicial, $final))->onQueue('low'));
        
        return response()->json(['response' => 'Agendado']);
    }
}
