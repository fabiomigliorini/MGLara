<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use MGLara\Http\Controllers\Controller;

use MGLara\Models\ProdutoBarra;
use MGLara\Models\Produto;

class ProdutoBarraController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $model = new ProdutoBarra();
        $produto = Produto::findOrFail($request->codproduto);
        return view('produto-barra.create', compact('model', 'produto'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $model = new ProdutoBarra($request->all());
        $model->codproduto = $request->input('codproduto');
        
        if ($model->codprodutoembalagem == 0)
            $model->codprodutoembalagem = null;
        
        if (!$model->validate())
            $this->throwValidationException($request, $model->_validator);
        
        $model->save();
        Session::flash('flash_success', "Código de Barras '{$model->barras}' criado!");
        return redirect("produto/$model->codproduto");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $model = ProdutoBarra::findOrFail($id);
        $produto = $model->Produto;
        return view('produto-barra.edit',  compact('model', 'produto'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $model = ProdutoBarra::findOrFail($id);
        $model->fill($request->all());
        
        if ($model->codprodutoembalagem == 0)
            $model->codprodutoembalagem = null;
        
        if (!$model->validate())
            $this->throwValidationException($request, $model->_validator);
        
        $model->save();
        Session::flash('flash_success', "Código de Barras '{$model->barras}' atualizado!");
        return redirect("produto/$model->codproduto");     
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            ProdutoBarra::find($id)->delete();
            $ret = ['resultado' => true, 'mensagem' => 'Código de Barras excluído com sucesso!'];
        }
        catch(\Exception $e){
            $ret = ['resultado' => false, 'mensagem' => 'Erro ao excluir código de barras!', 'exception' => $e];
        }
        return json_encode($ret);
    }

}
