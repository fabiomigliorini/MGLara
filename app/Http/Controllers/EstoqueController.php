<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;

use MGLara\Http\Requests;
use MGLara\Http\Controllers\Controller;
use MGLara\Jobs\EstoqueCalculaCustoMedio;
use MGLara\Models\EstoqueMes;

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
