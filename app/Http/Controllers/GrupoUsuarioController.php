<?php

namespace MGLara\Http\Controllers;

use MGLara\Http\Controllers\Controller;
use MGLara\Models\GrupoUsuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use MGLara\Models\Permissao;

class GrupoUsuarioController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('permissao:grupo-usuario.consulta', ['only' => ['index', 'show']]);
        $this->middleware('permissao:grupo-usuario.inclusao', ['only' => ['create', 'store']]);
        $this->middleware('permissao:grupo-usuario.alteracao', ['only' => ['edit', 'update']]);
        $this->middleware('permissao:grupo-usuario.exclusao', ['only' => ['delete', 'destroy']]);
        
        $this->middleware('parametros', ['only' => ['index', 'show']]);
    }
    
    public function index(Request $request) {
        
        if (!$request->session()->has('grupo-usuario.index')) {
            $request->session()->put('grupo-usuario.index', []);
        }

        $parametros = $request->session()->get('grupo-usuario.index');
        
        $model = GrupoUsuario::search($parametros)->orderBy('grupousuario', 'ASC')->paginate(20);
        return view('grupo-usuario.index', compact('model'));        
    }

    public function create() {
        $model = new GrupoUsuario;
        return view('grupo-usuario.create', compact('model'));
    }

    public function store(Request $request) {
        $model = new GrupoUsuario($request->all());
        if (!$model->validate())
            $this->throwValidationException($request, $model->_validator);
        $model->save();
        Session::flash('flash_success', 'Grupo de usuário Criado!');
        return redirect("grupo-usuario/$model->codgrupousuario");  
    }

    public function edit($codgrupousuario) {
        $model = GrupoUsuario::findOrFail($codgrupousuario);
        return view('grupo-usuario.edit',  compact('model'));
    }

    public function update($codgrupousuario, Request $request) {
        $model = GrupoUsuario::findOrFail($codgrupousuario);
        $model->fill($request->all());
        if (!$model->validate())
            $this->throwValidationException($request, $model->_validator);
        $model->save();
        
        Session::flash('flash_success', "Grupo de usuário '{$model->grupousuario}' Atualizado!");
        return redirect("grupo-usuario/$model->codgrupousuario"); 
    }
    
    public function show(Request $request, $id) {
        
        if (!$request->session()->has('grupo-usuario.show')) {
            $request->session()->put("grupo-usuario.show", []);
        }

        $parametros = $request->session()->get('grupo-usuario.show');
        $model = GrupoUsuario::find($id);
        $permissoes = Permissao::orderBy('permissao', 'ASC')->get();        
        return view('grupo-usuario.show', compact('model', 'permissoes'));
    }

    public function destroy($id)
    {
        try{
            GrupoUsuario::find($id)->delete();
            $ret = ['resultado' => true, 'mensagem' => 'Grupo de usuário excluído com sucesso!'];
        }
        catch(\Exception $e){
            $ret = ['resultado' => false, 'mensagem' => 'Erro ao excluir grupo de usuário!', 'exception' => $e];
        }
        return json_encode($ret);
    }
    
    public function attachPermissao(Request $request) {
        $model = GrupoUsuario::find($request->get('codgrupousuario'));
        $model->PermissaoS()->attach($request->get('codpermissao'));
    }
    
    public function detachPermissao(Request $request) {
        $model = GrupoUsuario::find($request->get('codgrupousuario'));
        $model->PermissaoS()->detach($request->get('codpermissao'));
    }
}
