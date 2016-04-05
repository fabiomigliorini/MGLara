<?php

namespace MGLara\Http\Controllers;

use MGLara\Http\Controllers\Controller;
use MGLara\Models\EstoqueMes;
use MGLara\Models\EstoqueMovimento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

class EstoqueMesController extends Controller
{
    
    public function __construct()
    {
        #$this->middleware('permissao:estoque-mes.consulta', ['only' => ['index', 'show']]);
        #$this->middleware('permissao:estoque-mes.inclusao', ['only' => ['create', 'store']]);
        #$this->middleware('permissao:estoque-mes.edicao', ['only' => ['edit', 'update']]);
        #$this->middleware('permissao:estoque-mes.exclusao', ['only' => ['delete', 'destroy']]);
    }
    
    public function index(Request $request) {

        $model = EstoqueMes::filterAndPaginate(
            $request->get('codestoquemes')
            # $request->get('permissao')
        );

        return view('estoque-mes.index', compact('model'));        
    }

    public function show(Request $request, $codestoquemes) {
        $model = EstoqueMes::find($codestoquemes);
        return view('estoque-mes.show', compact('model'));
    }
    
    public function create() {
        return view('permissao.create');
    }

    public function store(Request $request) {
        $model = new Permissao($request->all());
        if (!$model->validate())
            $this->throwValidationException($request, $model->_validator);
        $model->save();
        Session::flash('flash_create', 'Registro inserido.');
        return redirect('estoque-mes');
    }

    public function edit($codpermissao) {
        $model = Permissao::findOrFail($codpermissao);
        return view('estoque-mes.edit',  compact('model'));
    }

    public function update($codpermissao, Request $request) {
        $model = Permissao::findOrFail($codpermissao);
        $model->fill($request->all());
        if (!$model->validate())
            $this->throwValidationException($request, $model->_validator);
        $model->save();
        
        Session::flash('flash_update', 'Registro atualizado.');
        return redirect('estoque-mes');
    }
    
//    public function delete($codpermissao) {
//        Permissao::find($codpermissao)->delete();
//        die('ddd');
//        Session::flash('flash_message', 'Registro deletado.');
//        return Redirect::route('permissao');
//    }
    
    public function destroy($codpermissao) {

        try{
            Permissao::find($codpermissao)->delete();
            Session::flash('flash_delete', 'Registro deletado!');
            return Redirect::route('estoque-mes.index');
        }
        catch(\Exception $e){
            return view('errors.fk');
        }        
    }
}
