<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;

use MGLara\Http\Requests;
use MGLara\Http\Controllers\Controller;
use MGLara\Models\EstoqueSaldo;
use MGLara\Models\EstoqueMes;

class EstoqueSaldoController extends Controller
{

    /**
     * Redireciona para último EstoqueMes encontrado
     *
     * @param  bigint  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $ems = EstoqueMes::where('codestoquesaldo', $id)
               ->orderBy('mes', 'DESC')
               ->take(1)
               ->get();
        return redirect("estoque-mes/{$ems[0]->codestoquemes}");
    }
    
    public function zera($id)
    {
        $model = EstoqueSaldo::findOrFail($id);
        return json_encode($model->zera());
    }

}
