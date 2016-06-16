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
        $em = EstoqueMes::findOrFail($id);
        $this->dispatch(new EstoqueCalculaCustoMedio($em));
        return response()->json(['response' => 'Agendado']);
    }
    
    public function geraMovimentoNegocioProdutoBarra(Request $request, $id)
    {
        $npb = NegocioProdutoBarra::findOrFail($id);
        $this->dispatch(new EstoqueGeraMovimentoNegocioProdutoBarra($npb));
        return response()->json(['response' => 'Agendado']);
    }

    public function geraMovimentoNegocio(Request $request, $id)
    {
        $npb = Negocio::findOrFail($id);
        $this->dispatch(new EstoqueGeraMovimentoNegocio($npb));
        return response()->json(['response' => 'Agendado']);
    }
    
    public function geraMovimentoProduto(Request $request, $id)
    {
        $prod = Produto::findOrFail($id);
        $this->dispatch(new EstoqueGeraMovimentoProduto($prod));
        return response()->json(['response' => 'Agendado']);
    }
    
    public function geraMovimentoPeriodo(Request $request)
    {
        //dd($request->final);
        
        $inicial = Carbon::createFromFormat('d/m/Y H:i:s', $request->inicial); // 1975-05-21 22:00:00
        $final = Carbon::createFromFormat('d/m/Y H:i:s', $request->final); // 1975-05-21 22:00:00
        
        $this->dispatch(new EstoqueGeraMovimentoPeriodo($inicial, $final));
        
        return response()->json(['response' => 'Agendado']);
        
        dd($final);

        dd($request);
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
