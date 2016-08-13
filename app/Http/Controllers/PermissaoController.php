<?php

namespace MGLara\Http\Controllers;

use MGLara\Http\Controllers\Controller;
use MGLara\Models\Permissao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

class PermissaoController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('permissao:permissao.consulta', ['only' => ['index', 'show']]);
        $this->middleware('permissao:permissao.inclusao', ['only' => ['create', 'store']]);
        $this->middleware('permissao:permissao.alteracao', ['only' => ['edit', 'update']]);
        $this->middleware('permissao:permissao.exclusao', ['only' => ['delete', 'destroy']]);
        
        $this->middleware('parametros', ['only' => ['index']]);
    }
    
    public function index(Request $request) {
        
        if (!$request->session()->has('permissao.index')) {
            $request->session()->put('permissao.index', []);
        }

        $parametros = $request->session()->get('permissao.index');
        
        $model = Permissao::search($parametros)->orderBy('permissao', 'ASC')->paginate(20);
        return view('permissao.index', compact('model'));        
    }

    public function create() {
        $model = new Permissao;
        return view('permissao.create', compact('model'));
    }

    public function store(Request $request) {
        $model = new Permissao($request->all());
        if (!$model->validate())
            $this->throwValidationException($request, $model->_validator);
        $model->save();
        Session::flash('flash_success', 'Permissão Criada!');
        return redirect("permissao/$model->codpermissao");  
    }

    public function edit($codpermissao) {
        $model = Permissao::findOrFail($codpermissao);
        return view('permissao.edit',  compact('model'));
    }

    public function update($codpermissao, Request $request) {
        $model = Permissao::findOrFail($codpermissao);
        $model->fill($request->all());
        if (!$model->validate())
            $this->throwValidationException($request, $model->_validator);
        $model->save();
        
        Session::flash('flash_success', "Permissão '{$model->observacoes}' Atualizada!");
        return redirect("permissao/$model->codpermissao"); 
    }
    
    public function show($codpermissao) {
        $model = Permissao::find($codpermissao);
        return view('permissao.show', compact('model'));
    }

    public function destroy($id)
    {
        try{
            Permissao::find($id)->delete();
            $ret = ['resultado' => true, 'mensagem' => 'Permissão excluída com sucesso!'];
        }
        catch(\Exception $e){
            $ret = ['resultado' => false, 'mensagem' => 'Erro ao excluir permissão!', 'exception' => $e];
        }
        return json_encode($ret);
    }
}
