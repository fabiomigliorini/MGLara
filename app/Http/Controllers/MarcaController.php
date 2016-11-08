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
    public function __construct()
    {
        $this->middleware('parametros', ['only' => ['index']]);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->session()->has('marca.index')) {
            $request->session()->put('marca.index.ativo', '1');
        }

        $parametros = $request->session()->get('marca.index');
        $model = Marca::search($parametros)->orderBy('marca', 'ASC')->paginate(20);
        return view('marca.index', compact('model'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $model = new Marca();
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
        Session::flash('flash_success', 'Marca Criada!');
        return redirect("marca/$model->codmarca"); 
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
        return view('marca.show', compact('model'));
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
        
        if(is_null($request->input('site'))) {
            $model->site = FALSE;
        }

        if (!$model->validate()) {
            $this->throwValidationException($request, $model->_validator);
        }

        $model->save();
        Session::flash('flash_success', "Marca '{$model->marca}' Atualizada!");
        return redirect("marca/$model->codmarca");         
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
            $ret = ['resultado' => true, 'mensagem' => 'Marca excluída com sucesso!'];
        }
        catch(\Exception $e){
            $ret = ['resultado' => false, 'mensagem' => 'Erro ao excluir marca!', 'exception' => $e];
        }
        return json_encode($ret);
    } 
    
    public function listagemJson(Request $request) 
    {
        if($request->get('q')) 
        {
            $parametros['marca'] = $request->get('q');
            $parametros['ativo'] = $request->get('ativo');
            $marcas = Marca::search($parametros)
                    ->select('codmarca as id', 'marca')
                    ->take(10)
                    ->get();
            
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
        {
            $model->inativo = null;
            $msg = "Marca '{$model->marca}' Reativada!";
        }
        else
        {
            $model->inativo = Carbon::now();
            $msg = "Marca '{$model->marca}' Inativada!";
        }
        
        $model->save();
        Session::flash('flash_success', $msg);
    }     
}
