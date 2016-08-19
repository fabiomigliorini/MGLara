<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use MGLara\Http\Controllers\Controller;
use MGLara\Models\Ncm;

class NcmController extends Controller
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
        if (!$request->session()->has('ncm.index')) {
            $request->session()->put('ncm.index.ativo', []);
        }

        //$parametros = $request->session()->get('ncm.index');
        $model = Ncm::whereNull('codncmpai')->orderBy('ncm', 'ASC')->paginate(20);
        $ncms = Ncm::find($request->get('codncm'));
        return view('ncm.index', compact('model', 'ncms'));        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $model = new Ncm();
        return view('ncm.create', compact('model'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $model = new Ncm($request->all());
        
        if (!$model->validate()) {
            $this->throwValidationException($request, $model->_validator);
        }

        $model->save();
        Session::flash('flash_success', 'NCM Criada!');
        return redirect("ncm/$model->codncm"); 
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $model = Ncm::findOrFail($id);
        $filhos = $model->NcmS()->paginate(10);
        return view('ncm.show', compact('model', 'filhos'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $model = Ncm::findOrFail($id);
        return view('ncm.edit',  compact('model'));
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
        $model = Ncm::findOrFail($id);
        $model->fill($request->all());
        
        if (!$model->validate()) {
            $this->throwValidationException($request, $model->_validator);
        }

        $model->save();

        Session::flash('flash_success', "NCM '{$model->descricao}' Atualizada!");
        return redirect("ncm/$model->codncm");         
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
            Ncm::find($id)->delete();
            $ret = ['resultado' => true, 'mensagem' => 'NCM excluída com sucesso!'];
        }
        catch(\Exception $e){
            $ret = ['resultado' => false, 'mensagem' => 'Erro ao excluir NCM!', 'exception' => $e];
        }
        return json_encode($ret);
    } 
    
    public function listagemJson(Request $request) {
        if($request->get('q')) {
            return Ncm::select2($request->get('q'));
        } elseif($request->get('id')) {
            $ncm = Ncm::find($request->get('id'));
            return response()->json([
                'id' => $ncm->codncm,
                'ncm' => formataNcm($ncm->ncm),
                'descricao' => $ncm->descricao
            ]);
        }
    } 
    
}
