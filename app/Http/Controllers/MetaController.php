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
        if($request->codmeta) {
            $model = Meta::findOrFail($request->codmeta);
        } else {
            $model = Meta::where('periodoinicial', '<=', Carbon::today())
                    ->where('periodofinal', '>=', Carbon::today())
                    ->first();
        }

        return view('meta.index', compact('model'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if ($request->get('alterar'))
        {
            $model = Meta::findOrFail($request->get('alterar'));
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
        } else {
            $model = new Meta();
        }
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
        if ($request->get('alterar'))
        {
            $model = Meta::findOrFail($request->get('alterar'));
            $model->fill($request->all()['meta']);
            
        } else {
            $model = new Meta($request->all()['meta']);
        }
        
        DB::beginTransaction();
        
        if (!$model->validate()) {
            $this->throwValidationException($request, $model->_validator);        
        }
        
        try {
            if (!$model->save()){
                throw new Exception ('Erro ao Criar Meta!');            
            }
            
            $metasfilial = $request->all()['metafilial'];
            //dd($metasfilial);
            
            foreach ($metasfilial as $metafilial => $meta)
            {
                if(!empty($meta['controla'])) {
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
                        throw new Exception ('Erro ao Criar Meta Filial!');
                    }
                    $pessoas = $meta['pessoas'];
                    //dd($pessoas);
                    foreach ($pessoas as $pessoa)
                    {
                        //dd($pessoa);
                        if(!empty($pessoa['codmetafilialpessoa'])) {
                            $mfp = MetaFilialPessoa::findOrFail($pessoa['codmetafilialpessoa']);
                        } else {
                            $mfp = new MetaFilialPessoa();
                            $mfp->codmetafilial = $mf->codmetafilial;
                        }
                        
                        $mfp->codpessoa     = $pessoa['codpessoa'];
                        $mfp->codcargo      = $pessoa['codcargo'];
                        
                        if($mfp->codcargo && $mfp->codpessoa) {
                            //dd($mfp);
                            if (!$mfp->save()) {
                                throw new Exception ('Erro ao Criar Meta filial pessoa!');
                            }
                        } else {
                            $mfp->delete();
                        }
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
