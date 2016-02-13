<?php

namespace MGLara\Http\Controllers;

use MGLara\Http\Controllers\Controller;
use MGLara\Models\Usuario;
use MGLara\Models\Filial;
use Illuminate\Http\Request;
#use MGLara\Repositories\UsuarioRepository;

class UsuarioController extends Controller
{
    #protected $usuarioRepository;
    
    public function __construct(/*UsuarioRepository $usuarioRepository*/)
    {
        #$this->usuarioRepository = $usuarioRepository;
        #$this->middleware('permissao:usuario.consulta', ['only' => ['index', 'show']]);
        #$this->middleware('permissao:usuario.inclusao', ['only' => ['create', 'store']]);
        #$this->middleware('permissao:usuario.edicao', ['only' => ['edit', 'update']]);
        #$this->middleware('permissao:usuario.exclusao', ['only' => ['delete', 'destroy']]);
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

        return view('usuario.create');
    }

    public function store(Request $request) {
        $model = new Usuario($request->all());
        if (!$model->validate())
            $this->throwValidationException($request, $model->validator);
        $model->save();
        \Session::flash('flash_message', 'Registro inserido.');
        return redirect('usuario');
    }

    public function edit($codusuario) {

        $model = Usuario::findOrFail($codusuario);
        return view('usuario.edit',  compact('model'));
    }

    public function update($codmarca, Request $request) {
        /*$marca = Marca::findOrFail($codmarca);
        $marca->fill($request->all());
        if (!$marca->validate())
            $this->throwValidationException($request, $marca->validator);
        $marca->save();
        
        \Session::flash('flash_message', 'Registro atualizado.');
        return redirect('usuario');*/
    }
    
    public function show($codusuario) {
        $model = Usuario::find($codusuario);
        return view('usuario.show', compact('model'));
    }

    public function delete($codmarca) {
//        Marca::find($codmarca)->delete();
//        die('ddd');
//        \Session::flash('flash_message', 'Registro deletado.');
//        return Redirect::route('usuario');
    }
    
    public function destroy($codmarca) {

//        Marca::find($codmarca)->delete();
//        
//        \Session::flash('flash_message', 'Registro deletado.');
//        return Redirect::route('usuario.index');
    }
    

    public function ajax(Request $request){
        if($request->get('q')) {
            $marcas = Marca::marca($request->get('q'))->select('codmarca as id', 'marca')->take(10)->get();
            return response()->json(['items' => $marcas]);       
        } elseif($request->get('id')) {
            $marca = Marca::find($request->get('id'));
            return response()->json($marca);
        }
        
    }    
}
