<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;

use MGLara\Http\Requests;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use MGLara\Http\Controllers\Controller;
use MGLara\Models\Marca;
use MGLara\Models\EstoqueLocal;
use MGLara\Models\EstoqueSaldo;
use Carbon\Carbon;

class MarcaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {      
        $model = Marca::filterAndPaginate(
            $request->get('codmarca'),
            $request->get('marca'),
            $request->get('inativo')    
        ); 
        $ess = EstoqueSaldo::saldoPorMarca();
        $els = EstoqueLocal::where('inativo', null)->orderBy('codestoquelocal')->get();
        return view('marca.index', compact('model', 'ess', 'els'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $model = Marca::class;
        return view('marca.create', compact('model'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $model = new Marca($request->all());
        if (!$model->validate())
            $this->throwValidationException($request, $model->_validator);
        $model->save();
        Session::flash('flash_create', 'Registro inserido.');
        return redirect('marca');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $model = Marca::findOrFail($id);
        $ess = EstoqueSaldo::saldoPorProdutoMarca($model->codmarca);
        $els = EstoqueLocal::where('inativo', null)->orderBy('codestoquelocal')->get();
        return view('marca.show', compact('model', 'ess', 'els'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $model = Marca::findOrFail($id);
        return view('marca.edit',  compact('model'));
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
        $model = Marca::findOrFail($id);
        $model->fill($request->all());
        if (!$model->validate())
            $this->throwValidationException($request, $model->_validator);
        $model->save();
        Session::flash('flash_update', 'Registro atualizado.');
        return redirect("marca/$id");        
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
	        Marca::find($id)->delete();
	        Session::flash('flash_delete', 'Registro deletado!');
	        return Redirect::route('marca.index');
        }
        catch(\Exception $e){
        	return view('errors.fk');
        }     
    }
    
    public function listagemJson(Request $request) 
    {
        if($request->get('q')) 
        {
            $marcas = Marca::marca($request->get('q'))
                    ->select('codmarca as id', 'marca')
                    ->inativo($request->get('inativo'))
                    ->take(10)->get();
            
            return response()->json(['items' => $marcas]);       
            
        } elseif($request->get('id')) 
        {
            $marca = Marca::find($request->get('id'));
            return response()->json($marca);
        }
    } 
    
    public function buscaCodproduto($id)
    {
        $model = Marca::findOrFail($id);
        foreach ($model->ProdutoS as $prod)
            $arr_codproduto[] = $prod->codproduto;
        echo json_encode($arr_codproduto);        
    }

    public function inativo(Request $request)
    {
        $model = Marca::find($request->get('codmarca'));
        if($request->get('acao') == 'ativar')
            $model->inativo = null;
        else
            $model->inativo = Carbon::now();
        
        $model->save();
    }    
}
