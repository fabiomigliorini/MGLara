<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;

use MGLara\Http\Requests;
use MGLara\Http\Controllers\Controller;
use MGLara\Models\Meta;
use MGLara\Models\MetaFilial;
use MGLara\Models\MetaFilialPessoa;
use DB;

class MetaController extends Controller
{
    public function __construct()
    {
        #$this->middleware('parametros', ['only' => ['index']]);
    }
        
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $model = [];
        return view('meta.index', compact('model'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $model = new Meta();
        return view('meta.create', compact('model'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $model = new Meta($request->all()['meta']);
        
        DB::beginTransaction();
        
        if (!$model->validate()) {
            $this->throwValidationException($request, $model->_validator);        
        }
        
        try {
            if (!$model->save()){
                throw new Exception ('Erro ao Criar Meta!');            
            }
            
            $metasfilial = $request->all()['metafilial'];
            foreach ($metasfilial as $metafilial => $value) 
            {
                $mf = new MetaFilial();
                $mf->codmeta            = $model->codmeta;
                $mf->codfilial          = $metafilial;
                $mf->valormetafilial    = $value['valormetafilial'];
                $mf->valormetavendedor  = $value['valormetavendedor'];
                $mf->observacoes        = $value['observacoes'];
                
                if (!$mf->save()) {
                    throw new Exception ('Erro ao Criar Meta Filial!');
                }
                
                $pessoas = $value['pessoas'];
                foreach ($pessoas as $pessoa)
                {
                    dd($pessoa);
                    if(!empty($pessoa['cargo'] && !empty($pessoa['codpessoa']))) {
                        $mfp = new MetaFilialPessoa();
                        $mfp->codmetafilial = $mf->codmetafilial;
                        $mfp->codpessoa     = $pessoa['codpessoa'];
                        $mfp->codcargo      = $pessoa['cargo'];
                    }
                    dd($mfp);
                    
                    if (!$mfp->save()) {
                        throw new Exception ('Erro ao Criar Meta filial pessoa!');
                    }
                } 
            }
            
            DB::commit();
            Session::flash('flash_success', "Meta  criada com sucesso!");
            return redirect("meta");            
            
        } catch (Exception $ex) {
            DB::rollBack();
            $this->throwValidationException($request, $model->_validator);              
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
