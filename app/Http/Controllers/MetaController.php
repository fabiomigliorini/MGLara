<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;

use MGLara\Http\Requests;
use MGLara\Http\Controllers\Controller;
use MGLara\Models\Meta;
use MGLara\Models\MetaFilial;
use MGLara\Models\MetaFilialPessoa;
use MGLara\Models\Filial;

use Illuminate\Support\Facades\Session;
use DB;
use Carbon\Carbon;

class MetaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $model = Meta::where('periodoinicial', '<=', Carbon::today())
                ->where('periodofinal', '>=', Carbon::today())
                ->first();
        
        if($model) {
            return redirect("meta/$model->codmeta");
        } else {
            $model = Meta::orderBy('periodofinal', 'DESC')->first();
            if(!is_null($model)) {
                return redirect("meta/$model->codmeta");
            } else {
                return view('meta.index', compact('model'));
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $parametros = $request->all();
        $model = Meta::findOrFail($id);
        $dados = $model->totalVendas();
        
        if ($request->get('debug') == true) {
            return $dados;
        }

        return view('meta.show', compact('model', 'dados'));
    }

    public function relatorio($id, Request $request)
    {
        $parametros = $request->all();
        $model = Meta::findOrFail($id);
        $dados = $model->totalVendas();
        
        if ($request->get('debug') == true) {
            return $dados;
        }

        return view('meta.relatorio', compact('model', 'dados'));
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
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
        $request_meta = $request->all()['meta'];
        $request_meta['periodoinicial'] = new Carbon($request_meta['periodoinicial']);
        $request_meta['periodofinal'] = new Carbon($request_meta['periodofinal']);
        $model = new Meta($request_meta);

        DB::beginTransaction();
        
        if (!$model->validate()) {
            $this->throwValidationException($request, $model->_validator);        
        }
        
        try {
            if (!$model->save()){
                throw new Exception ('Erro ao Criar Meta!');            
            }
            
            $metasfilial = $request->all()['metafilial'];
            foreach ($metasfilial as $metafilial => $meta)
            {
                if(!empty($meta['controla'])) {

                    $mf = new MetaFilial();
                    $mf->codfilial = $metafilial;
                    $mf->codmeta = $model->codmeta;
                    $mf->valormetafilial    = $meta['valormetafilial'];
                    $mf->valormetavendedor  = $meta['valormetavendedor'];
                    $mf->observacoes        = $meta['observacoes'];
                    
                    if (!$mf->save()) {
                        throw new Exception ('Erro ao Criar Meta Filial!');
                    }
                    
                    $pessoas = $meta['pessoas'];
                    foreach ($pessoas as $pessoa)
                    {
                        $mfp = new MetaFilialPessoa();
                        $mfp->codmetafilial = $mf->codmetafilial;
                        $mfp->codpessoa     = $pessoa['codpessoa'];
                        $mfp->codcargo      = $pessoa['codcargo'];
                        
                        if($mfp->codcargo && $mfp->codpessoa) {
                            if (!$mfp->save()) {
                                throw new Exception ('Erro ao Criar Meta filial pessoa!');
                            }
                        }
                    }
                }
            }
            
            DB::commit();
            Session::flash('flash_success', "Meta  criada com sucesso!");
            return redirect("meta/$model->codmeta");            
            
        } catch (Exception $ex) {
            DB::rollBack();
            $this->throwValidationException($request, $model->_validator);              
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $model = Meta::findOrFail($id);
        $model['meta'] =  $model->getAttributes();
        $metasfiliais = [];
        foreach($model->MetaFilialS()->get() as $metafilial)
        {
            $metasfiliais[$metafilial['codfilial']] = $metafilial;
            $metafilial['controla'] = TRUE;
            $pessoas = [];
            foreach ($metafilial->MetaFilialPessoaS()->get() as $pessoa)
            {
                $pessoas[$pessoa['codpessoa']] = [
                    'codmetafilialpessoa' => $pessoa['codmetafilialpessoa'],
                    'codpessoa'=> $pessoa['codpessoa'],
                    'codcargo'=> $pessoa['codcargo']
                ];
            }
            $metasfiliais[$metafilial['codfilial']]['pessoas'] = $pessoas;
        }
        $model['metafilial'] = $metasfiliais;
        
        return view('meta.edit',  compact('model'));
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
        $model = Meta::findOrFail($id);
        $dados_meta = $request->all()['meta'];
        $dados_meta['periodoinicial'] = new Carbon($dados_meta['periodoinicial']);
        $dados_meta['periodofinal'] = new Carbon($dados_meta['periodofinal']);
        $model->fill($dados_meta);
        
        DB::beginTransaction();

        if (!$model->validate()) {
            $this->throwValidationException($request, $model->_validator);
        }

        try {
            if (!$model->save()){
                throw new Exception ('Erro ao Alterar Meta!');            
            }
            
            $metasfilial = $request->all()['metafilial'];
            foreach ($metasfilial as $metafilial => $meta)
            {
                if(isset($meta['controla'])) {
                    
                    if(empty($meta['codmetafilial'])) {
                        $mf = new MetaFilial();
                        $mf->codfilial = $metafilial;
                        $mf->codmeta = $model->codmeta;
                    } else {
                        $mf = MetaFilial::findOrFail($meta['codmetafilial']);
                    }
                    $mf->valormetafilial    = $meta['valormetafilial'];
                    $mf->valormetavendedor  = $meta['valormetavendedor'];
                    $mf->observacoes        = $meta['observacoes'];
                    
                    if (!$mf->save()) {
                        throw new Exception ('Erro ao Alterar Meta Filial!');
                    }

                    if(isset($meta['pessoas'])) {
                        $codmetafilialpessoa = [];
                        foreach ($meta['pessoas'] as $pessoa_dado)
                        {
                            $pessoa_dados = [
                                'codmetafilial' => (int) $mf->codmetafilial,
                                'codpessoa'     => (int) $pessoa_dado['codpessoa'],
                                'codcargo'      => (int) $pessoa_dado['codcargo']
                            ];

                            if(!empty($pessoa_dado['codmetafilialpessoa'])) {
                                $pessoa = MetaFilialPessoa::findOrFail($pessoa_dado['codmetafilialpessoa']);
                                $pessoa->fill($pessoa_dados);
                            } else {
                                $pessoa = new MetaFilialPessoa();
                                $pessoa->fill($pessoa_dados);
                            }

                            if (!$pessoa->validate()) {
                                $this->throwValidationException($request, $pessoa->_validator);
                            }
                            
                            $pessoa->save();
                            
                            $codmetafilialpessoa[] = $pessoa->codmetafilialpessoa;
                        }
                        $mf->MetaFilialPessoaS()->whereNotIn('codmetafilialpessoa', $codmetafilialpessoa)->delete();
                    } else {
                        $mf->MetaFilialPessoaS()->delete();
                    }
                }
            }
            
            DB::commit();
            Session::flash('flash_success', "Meta alterada!");
            return redirect("meta/$model->codmeta");           
        } catch (Exception $ex) {
            DB::rollBack();
            $this->throwValidationException($request, $model->_validator);              
        }        
    }
    
    
    public function destroy($id)
    {
        try{
            $meta = Meta::findOrFail($id);
            foreach ($meta->MetaFilialS()->get() as $mf)
            {
                foreach ($mf->MetaFilialPessoaS()->get() as $mfp)
                {
                    if (!$mfp->delete()){
                        throw new Exception ('Erro excluir Meta Filial Pessoa!');            
                    }                    
                }
                if (!$mf->delete()){
                    throw new Exception ('Erro excluir Meta Filial!');            
                }                    
            }
            $meta->delete();
            $ret = ['resultado' => true, 'mensagem' => 'Meta excluída com sucesso!'];
        }
        catch(\Exception $e){
            $ret = ['resultado' => false, 'mensagem' => 'Erro ao excluir meta!', 'exception' => "$e"];
        }
        return json_encode($ret);
    }    
}
