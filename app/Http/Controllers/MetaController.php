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
        $model = Meta::findOrFail($id);
        
        /*
        $dados = $model->totalVendas();
        if ($request->get('debug') == true) {
            return $dados;
        }
        */
        
        /*
         * 
         * TOTAIS FILIAL / Subgerente
            select 
                  f.filial
                , mf.valormetafilial
                , (
                    select 
                            sum((case when n.codoperacao = 1 then -1 else 1 end) * coalesce(n.valortotal, 0)) as valorvendas
                    from tblnegocio n
                    where n.codnegociostatus = 2 -- fechado
                    and n.codpessoa not in (select distinct f2.codpessoa from tblfilial f2)
                    and n.codnaturezaoperacao in (1, 2) -- Venda / Devolucao de Vendas -- TODO: Fazer modelagem para tirar o codigo fixo
                    and n.lancamento between m.periodoinicial and m.periodofinal
                    and n.codfilial = mf.codfilial
                ) as valorvendas
                , mfp.codpessoa
                , p.pessoa
            from tblmeta m
            inner join tblmetafilial mf on (mf.codmeta = m.codmeta)
            inner join tblfilial f on (f.codfilial = mf.codfilial)
            left join tblmetafilialpessoa mfp on (mfp.codmetafilial = mf.codmetafilial and mfp.codcargo = 2) -- Subgerente -- TODO: Fazer modelagem
            left join tblpessoa p on (p.codpessoa = mfp.codpessoa)
            where m.codmeta = 1
            --and mf.codfilial =102

        --Totais Vendedor
        select 
              mf.codfilial
            , f.filial
            , mf.valormetavendedor
            , mfp.codpessoa
            , p.fantasia
            , (
                select 
                        sum(coalesce(npb.valortotal, 0) * (case when n.codoperacao = 1 then -1 else 1 end) * (coalesce(n.valortotal, 0) / coalesce(n.valorprodutos, 0))) as valorvendas
                from tblnegocio n
                inner join tblnegocioprodutobarra npb on (npb.codnegocio = n.codnegocio)
                inner join tblprodutobarra pb on (pb.codprodutobarra = npb.codprodutobarra)
                inner join tblproduto p on (p.codproduto = pb.codproduto)
                where n.codnegociostatus = 2 -- fechado
                and n.codpessoa not in (select distinct f2.codpessoa from tblfilial f2)
                and n.codnaturezaoperacao in (1, 2) -- Venda / Devolucao de Vendas -- TODO: Fazer modelagem para tirar o codigo fixo
                and p.codsubgrupoproduto != 2951 -- Xerox -- TODO: Fazer modelagem para tirar o codigo fixo
                and n.lancamento between m.periodoinicial and m.periodofinal
                and n.codpessoavendedor = mfp.codpessoa
            ) as valorvendas
            , m.percentualcomissaovendedor
        from tblmeta m
        inner join tblmetafilial mf on (mf.codmeta = m.codmeta)
        inner join tblfilial f on (mf.codfilial = f.codfilial)
        inner join tblmetafilialpessoa mfp on (mfp.codmetafilial = mf.codmetafilial and mfp.codcargo = 1) -- Vendedor -- TODO: Fazer modelagem
        inner join tblpessoa p on (p.codpessoa = mfp.codpessoa)
        where m.codmeta = 1
        --and mf.codfilial = 102


         */
        
        
        return view('meta.show', compact('model', 'dados'));
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
        $model->fill($request->all()['meta']);

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
                                'codmetafilial' => $mf->codmetafilial,
                                'codpessoa'     => $pessoa_dado['codpessoa'],
                                'codcargo'      => $pessoa_dado['codcargo']
                            ];

                            if(!empty($pessoa_dado['codmetafilialpessoa'])) {
                                $pessoa = MetaFilialPessoa::findOrFail($pessoa_dado['codmetafilialpessoa']);
                                $pessoa->fill($pessoa_dados);
                            } else {
                                $pessoa = new MetaFilialPessoa($pessoa_dados);
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
