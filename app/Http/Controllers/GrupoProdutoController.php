<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;

use MGLara\Http\Requests;
use MGLara\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use MGLara\Models\FamiliaProduto;
use MGLara\Models\GrupoProduto;
use MGLara\Models\SubGrupoProduto;
use Carbon\Carbon;

class GrupoProdutoController extends Controller
{
    public function __construct()
    {
        $this->middleware('parametros', ['only' => ['show']]);
    }      
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $model = new GrupoProduto();
        $parent = FamiliaProduto::findOrFail($request->get('codfamiliaproduto'));
        return view('grupo-produto.create', compact('model', 'parent'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $model = new GrupoProduto($request->all());
        $model->codfamiliaproduto = $request->get('codfamiliaproduto');
        
        if (!$model->validate())
            $this->throwValidationException($request, $model->_validator);
        
        $model->save();
        
        Session::flash('flash_success', 'Grupo Criado!');
        return redirect("grupo-produto/$model->codgrupoproduto");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        if (!$request->session()->has('grupo-produto.show'))
            $request->session()->put("grupo-produto.show.ativo", '1');
        
        $request->session()->put("grupo-produto.show.codgrupoproduto", $id);
        $parametros = $request->session()->get('grupo-produto.show');               
            
        $model = GrupoProduto::findOrFail($id);
        $subgrupos = SubGrupoProduto::search($parametros);
        return view('grupo-produto.show', compact('model','subgrupos'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $model = GrupoProduto::findOrFail($id);
        return view('grupo-produto.edit',  compact('model'));
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
        $model = GrupoProduto::findOrFail($id);
        $model->fill($request->all());
        
        if (!$model->validate())
            $this->throwValidationException($request, $model->_validator);
        
        $model->save();
        
        Session::flash('flash_success', "Grupo '{$model->grupoproduto}' Atualizado!");
        return redirect("grupo-produto/$id");   
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $i
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            GrupoProduto::find($id)->delete();
            $ret = ['resultado' => true, 'mensagem' => 'Grupo excluído com sucesso!'];
        }
        catch(\Exception $e){
            $ret = ['resultado' => false, 'mensagem' => 'Erro ao excluir grupo!', 'exception' => $e];
        }
        return json_encode($ret);
    }    

    public function inativo(Request $request)
    {
        $model = GrupoProduto::find($request->get('codgrupoproduto'));
        if($request->get('acao') == 'ativar')
        {
            $model->inativo = null;
            $msg = "Grupo '{$model->grupoproduto}' Reativado!";
        }
        else
        {
            $model->inativo = Carbon::now();
            $msg = "Grupo '{$model->grupoproduto}' Inativado!";
        }
        
        $model->save();
        Session::flash('flash_success', $msg);
    }
    
    public function listagemJson(Request $request)
    {
        if($request->get('codfamiliaproduto')) {
            $model = GrupoProduto::where('codfamiliaproduto', $request->get('codfamiliaproduto'))
                ->grupoproduto($request->get('q'))
                ->select('codgrupoproduto as id', 'grupoproduto', 'inativo')
                ->get();
            return response()->json(['items' => $model]);       
        } elseif($request->get('id')) {
            $id = numeroLimpo($request->get('id'));
            $model = GrupoProduto::where('codgrupoproduto', $id)->select('codgrupoproduto as id', 'grupoproduto')->first();
            return response()->json($model);
        }
    } 
}
