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
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class UsuarioController extends Controller
{
    public function __construct()
    {
        $this->middleware('permissao:usuario.consulta', ['only' => ['index', 'show']]);
        $this->middleware('permissao:usuario.inclusao', ['only' => ['create', 'store']]);
        $this->middleware('permissao:usuario.alteracao', ['only' => ['edit', 'update']]);
        $this->middleware('permissao:usuario.exclusao', ['only' => ['delete', 'destroy']]);
        $this->middleware('parametros', ['only' => ['index', 'permissao']]);
    }
    
    public function index(Request $request) {
        
        if (!$request->session()->has('usuario.index')) {
            $request->session()->put('usuario.index.ativo', '1');
        }

        $parametros = $request->session()->get('usuario.index');        
        $model = Usuario::search($parametros)->orderBy('usuario', 'ASC')->paginate(20);
        return view('usuario.index', compact('model'));        
    }

    public function create() {
        $model = new Usuario();
        return view('usuario.create', compact('model'));
    }

    public function store(Request $request) {
        $model = new Usuario($request->all());
        if (!$model->validate())
            $this->throwValidationException($request, $model->_validator);
        $model->senha = bcrypt($model->senha);
        $model->save();
        Session::flash('flash_success', 'Usuário Criado!');
        return redirect("usuario/$model->codusuario");  
    }

    public function edit($codusuario) {
        $model = Usuario::findOrFail($codusuario);
                
        $usuario = Usuario::find(Auth::user()->codusuario);
        $grupos = $usuario->extractgrupos();
        $admin = false;
        foreach ($grupos as $grupo)
        {
            if ($grupo['grupo'] == '1') {
                $admin = true;
            }
        }

        if($admin) { 
            return view('usuario.edit',  compact('model'));
        } elseif(!$admin && $model->codusuario == $usuario->codusuario){
            return view('usuario.edit',  compact('model'));
        } else {
            return view('errors.403');
        }        
    }

    public function update($codusuario, Request $request) {
        $model = Usuario::findOrFail($codusuario);
        $model->fill($request->all());
        if (!$model->validate()) {
            $this->throwValidationException($request, $model->_validator);
        }
        if (isset($model->senha)) {
            $model->senha = bcrypt($model->senha);
        }
        $model->save();
        
        Session::flash('flash_success', "Usuário '{$model->usuario}' Atualizado!");
        return redirect("usuario/$model->codusuario"); 
    }
    
    public function show($codusuario) {
        $model = Usuario::find($codusuario);
        $usuario = Usuario::find(Auth::user()->codusuario);
        return view('usuario.show', compact('model', 'usuario'));
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
            Usuario::find($id)->delete();
            $ret = ['resultado' => true, 'mensagem' => 'Usuário excluído com sucesso!'];
        }
        catch(\Exception $e){
            $ret = ['resultado' => false, 'mensagem' => 'Erro ao excluir usuário!', 'exception' => $e];
        }
        return json_encode($ret);
    }
    
    public function inativo(Request $request)
    {
        $model = Usuario::find($request->get('codusuario'));
        if($request->get('acao') == 'ativar')
        {
            $model->inativo = null;
            $msg = "Usuário '{$model->usuario}' Reativado!";
        }
        else
        {
            $model->inativo = Carbon::now();
            $msg = "Usuário '{$model->usuario}' Inativado!";
        }
        
        $model->save();
        Session::flash('flash_success', $msg);
    }    
    
    public function permissao(Request $request, $codusuario) {
        if (!$request->session()->has('usuario.permissao')) {
            $request->session()->put('usuario.permissao', []);
        }

        $parametros = $request->session()->get('usuario.permissao');        
        
        $model = Usuario::find($codusuario);
        $filiais = Filial::orderBy('codfilial', 'asc')->get();
        $grupos = GrupoUsuario::search($parametros)->orderBy('grupousuario', 'ASC')->paginate(20);
        
        
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
