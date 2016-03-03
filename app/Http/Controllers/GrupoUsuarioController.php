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
    }
    
    public function index(Request $request) {
        $model = GrupoUsuario::filterAndPaginate(
            $request->get('codgrupousuario'),
            $request->get('grupousuario')
        );
        return view('grupo-usuario.index', compact('model'));        
    }

    public function create() {
        return view('grupo-usuario.create');
    }

    public function store(Request $request) {
        $model = new GrupoUsuario($request->all());
        if (!$model->validate())
            $this->throwValidationException($request, $model->_validator);
        $model->save();
        Session::flash('flash_create', 'Registro inserido.');
        return redirect('grupo-usuario');
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
        
        Session::flash('flash_update', 'Registro atualizado.');
        return redirect('grupo-usuario');
    }
    
    public function show($codgrupousuario, Request $request) {
        $model = GrupoUsuario::find($codgrupousuario);
        $permissoes = Permissao::filterAndPaginate(
            $request->get('codpermissao'),
            $request->get('permissao')
        );        
        return view('grupo-usuario.show', compact('model', 'permissoes'));
    }

//    public function delete($codgrupousuario) {
//        GrupoUsuario::find($codgrupousuario)->delete();
//        die('ddd');
//        Session::flash('flash_message', 'Registro deletado.');
//        return Redirect::route('grupousuario');
//    }
    
    public function destroy($codgrupousuario) {

    	try{
            GrupoUsuario::find($codgrupousuario)->delete();
            Session::flash('flash_delete', 'Registro deletado!');
            return Redirect::route('grupo-usuario.index');
    	}
    	catch(\Exception $e){
			return view('errors.fk');
    	}    	
    	
    }
    
    public function attachPermissao(Request $request) {
        $model = GrupoUsuario::find($request->get('codgrupousuario'));
        $model->Permissao()->attach($request->get('codpermissao'));
    }
    
    public function detachPermissao(Request $request) {
        $model = GrupoUsuario::find($request->get('codgrupousuario'));
        $model->Permissao()->detach($request->get('codpermissao'));
    }
}
