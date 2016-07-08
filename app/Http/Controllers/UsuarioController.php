<?php

namespace MGLara\Http\Controllers;

use MGLara\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
//use Illuminate\Hashing\BcryptHasher;
use MGLara\Models\Usuario;
use MGLara\Models\GrupoUsuario;
use MGLara\Models\Ecf;
use MGLara\Models\Filial;
use MGLara\Models\Operacao;
use MGLara\Models\Portador;

class UsuarioController extends Controller
{
    public function __construct()
    {
        $this->middleware('permissao:usuario.consulta', ['only' => ['index', 'show']]);
        $this->middleware('permissao:usuario.inclusao', ['only' => ['create', 'store']]);
        $this->middleware('permissao:usuario.alteracao', ['only' => ['edit', 'update']]);
        $this->middleware('permissao:usuario.exclusao', ['only' => ['delete', 'destroy']]);
        
        $this->filiais    = [''=>''] + Filial::lists('filial', 'codfilial')->all();
        $this->ecfs       = [''=>''] + Ecf::lists('ecf', 'codecf')->all();
        $this->ops        = [''=>''] + Operacao::lists('operacao', 'codoperacao')->all();
        $this->portadores = [''=>''] + Portador::lists('portador', 'codportador')->all();
        $this->prints     = [''=>''] + Usuario::printers();        
    }
    
    public function index(Request $request) {
        $model = Usuario::filterAndPaginate(
            $request->get('codusuario'),
            $request->get('usuario'),
            $request->get('codpessoa'),
            $request->get('codfilial')
        );
        $filiais = Filial::lists('filial', 'codfilial');
        return view('usuario.index', compact('model', 'filiais'));        
    }

    public function create() {
        $filiais    = $this->filiais;
        $ecfs       = $this->ecfs;
        $ops        = $this->ops;
        $portadores = $this->portadores;
        $prints     = $this->prints;
        return view('usuario.create', compact('ecfs', 'filiais', 'ecfs', 'ops', 'portadores', 'prints'));
    }

    public function store(Request $request) {
        $model = new Usuario($request->all());
        if (!$model->validate())
            $this->throwValidationException($request, $model->_validator);
        $model->senha = bcrypt($model->senha);
        $model->save();
        Session::flash('flash_create', 'Registro inserido.');
        return redirect('usuario');
    }

    public function edit($codusuario) {
        $model = Usuario::findOrFail($codusuario);
        $filiais    = $this->filiais;
        $ecfs       = $this->ecfs;
        $ops        = $this->ops;
        $portadores = $this->portadores;
        $prints     = $this->prints;
        if(!empty(!in_array($model->impressoramatricial, $prints)))
            $prints[$model->impressoramatricial] = $model->impressoramatricial;
        
        if(!empty(!in_array($model->impressoratermica, $prints)))
            $prints[$model->impressoratermica] = $model->impressoratermica;
        
        if(!empty(!in_array($model->impressoratelanegocio, $prints)))
            $prints[$model->impressoratelanegocio] = $model->impressoratelanegocio;
                
        return view('usuario.edit',  compact('model','ecfs', 'ops', 'filiais', 'portadores', 'prints'));
    }

    public function update($codusuario, Request $request) {
        $model = Usuario::findOrFail($codusuario);
        $model->fill($request->all());
        if (!$model->validate())
            $this->throwValidationException($request, $model->_validator);
        if (isset($model->senha))
             $model->senha = bcrypt($model->senha);
        $model->save();
        
        Session::flash('flash_update', 'Registro atualizado.');
        return redirect('usuario');
    }
    
    public function show($codusuario) {
        $model = Usuario::find($codusuario);
        return view('usuario.show', compact('model'));
    }

    public function destroy($codusuario) {

        try{
	        Usuario::find($codusuario)->delete();
	        Session::flash('flash_delete', 'Registro deletado!');
	        return Redirect::route('usuario.index');
        }
        catch(\Exception $e){
        	return view('errors.fk');
        }        
    }
    
    public function permissao(Request $request, $codusuario) {
        $model = Usuario::find($codusuario);
        $filiais = Filial::orderBy('codfilial', 'asc')->get();
        $grupos = GrupoUsuario::filterAndPaginate(
            $request->get('codgrupo'),
            $request->get('grupousuario')
        );
        return view('usuario.permissao', compact('model', 'grupos', 'filiais'));
    }

    public function attachPermissao(Request $request) {
        $model = Usuario::find($request->get('codusuario'));
        $model->GrupoUsuario()->attach($request->get('codgrupousuario'), ['codfilial' => $request->get('codfilial')]);
    }
    
    public function detachPermissao(Request $request) {
        DB::table( 'tblgrupousuariousuario' )
            ->where( 'codgrupousuario', '=', $request->codgrupousuario, 'and' )
            ->where( 'codusuario', '=', $request->codusuario, 'and' )
            ->where( 'codfilial', '=', $request->codfilial )
            ->delete();        
    }

//    public function listagemJson(Request $request){
//        if($request->get('q')) {
//            $marcas = Marca::marca($request->get('q'))->select('codmarca as id', 'marca')->take(10)->get();
//            return response()->json(['items' => $marcas]);       
//        } elseif($request->get('id')) {
//            $marca = Marca::find($request->get('id'));
//            return response()->json($marca);
//        }
//    }


            
}
