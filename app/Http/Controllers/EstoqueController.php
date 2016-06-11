<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;

use MGLara\Http\Requests;
use MGLara\Http\Controllers\Controller;
use MGLara\Jobs\EstoqueCalculaCustoMedio;
use MGLara\Jobs\EstoqueGeraMovimentoNegocioProdutoBarra;
use MGLara\Jobs\EstoqueGeraMovimentoNegocio;
use MGLara\Jobs\EstoqueGeraMovimentoProduto;

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
