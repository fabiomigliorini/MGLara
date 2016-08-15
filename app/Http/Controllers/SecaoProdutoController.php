<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;

use MGLara\Http\Requests;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use MGLara\Http\Controllers\Controller;
use MGLara\Models\SecaoProduto;
use MGLara\Models\FamiliaProduto;
use Carbon\Carbon;

class SecaoProdutoController extends Controller
{
    public function __construct()
    {
        $this->middleware('parametros', ['only' => ['index', 'show']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {

        if (!$request->session()->has('secao-produto.index')) 
            $request->session()->put('secao-produto.index.ativo', '1');
        
        $parametros = $request->session()->get('secao-produto')['index'];
            
        $model = SecaoProduto::search($parametros)->orderBy('secaoproduto', 'ASC')->paginate(20);
        return view('secao-produto.index', compact('model'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $model = new SecaoProduto();
        return view('secao-produto.create', compact('model'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $model = new SecaoProduto($request->all());
        
        if (!$model->validate())
            $this->throwValidationException($request, $model->_validator);
        
        $model->save();
        Session::flash('flash_success', 'Seção Criada!');
        return redirect("secao-produto/$model->codsecaoproduto");    
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        if (!$request->session()->has('secao-produto.show'))
            $request->session()->put("secao-produto.show.ativo", '1');
        
        $request->session()->put("secao-produto.show.codsecaoproduto", $id);
        $parametros = $request->session()->get('secao-produto')['show'];        
        
        $model = SecaoProduto::find($id);
        $familias = FamiliaProduto::search($parametros);
        return view('secao-produto.show', compact('model', 'familias'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $model = SecaoProduto::findOrFail($id);
        return view('secao-produto.edit',  compact('model'));
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
        $model = SecaoProduto::findOrFail($id);
        $model->fill($request->all());

        if (!$model->validate())
            $this->throwValidationException($request, $model->_validator);

        $model->save();
        
        Session::flash('flash_success', "Seção '{$model->secaoproduto}' Atualizada!");
        return redirect("secao-produto/$model->codsecaoproduto"); 
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
            SecaoProduto::find($id)->delete();
            $ret = ['resultado' => true, 'mensagem' => 'Seção excluída com sucesso!'];
        }
        catch(\Exception $e){
            $ret = ['resultado' => false, 'mensagem' => 'Erro ao excluir seção!', 'exception' => $e];
        }
        return json_encode($ret);
    }    

    public function inativo(Request $request)
    {
        $model = SecaoProduto::find($request->get('codsecaoproduto'));
        if($request->get('acao') == 'ativar')
        {
            $model->inativo = null;
            $msg = "Seção '{$model->secaoproduto}' Reativada!";
        }
        else
        {
            $model->inativo = Carbon::now();
            $msg = "Seção '{$model->secaoproduto}' Inativada!";
        }
        
        $model->save();
        Session::flash('flash_success', $msg);
    }    
    
}
