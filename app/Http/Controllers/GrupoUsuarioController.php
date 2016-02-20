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
        $this->middleware('permissao:grupousuario.consulta', ['only' => ['index', 'show']]);
        $this->middleware('permissao:grupousuario.inclusao', ['only' => ['create', 'store']]);
        $this->middleware('permissao:grupousuario.edicao', ['only' => ['edit', 'update']]);
        $this->middleware('permissao:grupousuario.exclusao', ['only' => ['delete', 'destroy']]);
    }
    
    public function index(Request $request) {
        $model = GrupoUsuario::filterAndPaginate(
            $request->get('codgrupousuario'),
            $request->get('grupousuario')
        );
        return view('grupousuario.index', compact('model'));        
    }

    public function create() {
        return view('grupousuario.create');
    }

    public function store(Request $request) {
        $model = new GrupoUsuario($request->all());
        if (!$model->validate())
            $this->throwValidationException($request, $model->_validator);
        $model->save();
        Session::flash('flash_create', 'Registro inserido.');
        return redirect('grupousuario');
    }

    public function edit($codgrupousuario) {
        $model = GrupoUsuario::findOrFail($codgrupousuario);
        return view('grupousuario.edit',  compact('model'));
    }

    public function update($codgrupousuario, Request $request) {
        $model = GrupoUsuario::findOrFail($codgrupousuario);
        $model->fill($request->all());
        if (!$model->validate())
            $this->throwValidationException($request, $model->_validator);
        $model->save();
        
        Session::flash('flash_update', 'Registro atualizado.');
        return redirect('grupousuario');
    }
    
    public function show($codgrupousuario, Request $request) {
        $model = GrupoUsuario::find($codgrupousuario);
        $permissoes = Permissao::filterAndPaginate(
            $request->get('codpermissao'),
            $request->get('permissao')
        );        
        return view('grupousuario.show', compact('model', 'permissoes'));
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
            return Redirect::route('grupousuario.index');
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
