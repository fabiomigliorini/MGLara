<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;

use MGLara\Http\Requests;
use MGLara\Http\Controllers\Controller;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use MGLara\Models\NotaFiscal;
use MGLara\Models\NotaFiscalProdutoBarra;
use MGLara\Models\NegocioProdutoBarra;

class NotaFiscalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return redirect()->away("/MGsis/index.php?r=notaFiscal/index");
        //
        echo 'index nota fsical';
        die();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect()->away("/MGsis/index.php?r=notaFiscal/view&id=$id");
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
    
    
    public function geraTransferencias($codfilial)
    {
        
        DB::BeginTransaction();
        
        $sql = "
            
            --Negocios gerados a partir de uma Filial, com NF emitida por outra Filial
            select 
                      tblnegocio.codfilial
                    , tblnegocio.codestoquelocal
                    , destino.codpessoa
                    , tblnegocio.codnaturezaoperacao	
                    , tblnegocioprodutobarra.codnegocioprodutobarra
            from tblnotafiscal 
            inner join tblnotafiscalprodutobarra on (tblnotafiscalprodutobarra.codnotafiscal = tblnotafiscal.codnotafiscal)
            inner join tblnegocioprodutobarra on (tblnegocioprodutobarra.codnegocioprodutobarra = tblnotafiscalprodutobarra.codnegocioprodutobarra)
            inner join tblnegocio on (tblnegocio.codnegocio = tblnegocioprodutobarra.codnegocio)
            inner join tblfilial as origem on (origem.codfilial = tblnegocio.codfilial)
            inner join tblfilial as destino on (destino.codfilial = tblnotafiscal.codfilial)
            left join (

                    select 
                              tblnotafiscal.codnotafiscal
                            , tblnotafiscal.codfilial
                            , tblnotafiscal.codpessoa
                            , tblnotafiscalprodutobarra.codnotafiscalprodutobarra
                            , tblnotafiscalprodutobarra.codnegocioprodutobarra
                    from tblnotafiscal 
                    inner join tblnotafiscalprodutobarra on (tblnotafiscalprodutobarra.codnotafiscal = tblnotafiscal.codnotafiscal)
                    where tblnotafiscal.emitida = true
                    --and tblnotafiscal.nfeautorizacao is not null -- Nao importa se ainda esta em digitacao
                    and tblnotafiscal.nfeinutilizacao is null
                    and tblnotafiscal.nfecancelamento is null
                    and tblnotafiscalprodutobarra.codnegocioprodutobarra is not null

                    ) emitida on (

                    emitida.codfilial = tblnegocio.codfilial
                    and emitida.codpessoa = destino.codpessoa
                    AND emitida.codnegocioprodutobarra = tblnegocioprodutobarra.codnegocioprodutobarra

                    )
            where tblnotafiscal.emitida = true
            and tblnotafiscal.nfeautorizacao is not null
            and tblnotafiscal.nfeinutilizacao is null
            and tblnotafiscal.nfecancelamento is null
            and tblnotafiscal.emissao >= '2017-10-01 00:00:00.0'
            and tblnegocio.codfilial <> destino.codfilial
            and origem.codempresa = destino.codempresa
            and emitida.codnotafiscal is null
            and origem.codfilial = {$codfilial}
            --limit 50

            union all

            --Negocios Intercompany
            select 
                      tblnegocio.codfilial
                    , tblnegocio.codestoquelocal
                    , tblnegocio.codpessoa
                    , tblnegocio.codnaturezaoperacao
                    , tblnegocioprodutobarra.codnegocioprodutobarra
            from tblnegocio
            inner join tblnegocioprodutobarra on (tblnegocioprodutobarra.codnegocio = tblnegocio.codnegocio)
            inner join tblfilial as origem on (origem.codfilial = tblnegocio.codfilial)
            inner join tblfilial as destino on (destino.codpessoa = tblnegocio.codpessoa)
            left join (

                    select 
                              tblnotafiscal.codnotafiscal
                            , tblnotafiscal.codfilial
                            , tblnotafiscal.codpessoa
                            , tblnotafiscalprodutobarra.codnotafiscalprodutobarra
                            , tblnotafiscalprodutobarra.codnegocioprodutobarra
                    from tblnotafiscal 
                    inner join tblnotafiscalprodutobarra on (tblnotafiscalprodutobarra.codnotafiscal = tblnotafiscal.codnotafiscal)
                    where tblnotafiscal.emitida = true
                    --and tblnotafiscal.nfeautorizacao is not null -- Nao importa se ainda esta em digitacao
                    and tblnotafiscal.nfeinutilizacao is null
                    and tblnotafiscal.nfecancelamento is null
                    and tblnotafiscalprodutobarra.codnegocioprodutobarra is not null

                    ) emitida on (

                    emitida.codfilial = tblnegocio.codfilial
                    and emitida.codpessoa = tblnegocio.codpessoa
                    AND emitida.codnegocioprodutobarra = tblnegocioprodutobarra.codnegocioprodutobarra

                    )
            where tblnegocio.codnegociostatus = 2
            and tblnegocio.lancamento >= '2017-10-01 00:00:00'
            and emitida.codnotafiscal is null
            and tblnegocio.codfilial <> destino.codfilial
            and tblnegocio.codnaturezaoperacao not in (19) --Uso e Consumo
            and origem.codempresa = destino.codempresa
            and origem.codfilial = {$codfilial}
            --limit 50

            limit 600
            
            ";
            
        $regs = DB::select(DB::raw($sql));
        
        $gerados = [];
        
        $nfs = [];
        
        foreach($regs as $reg) 
        {
            if (isset($gerados[$reg->codfilial][$reg->codpessoa][$reg->codnaturezaoperacao])) {
                
                $nf = $nfs[$gerados[$reg->codfilial][$reg->codpessoa][$reg->codnaturezaoperacao]['codnotafiscal']];
                
            } else {
                
                
                $nf = new NotaFiscal;
                $nf->codfilial = $reg->codfilial;
                $nf->codestoquelocal = $reg->codestoquelocal;
                $nf->modelo = NotaFiscal::MODELO_NFE;
                $nf->codpessoa = $reg->codpessoa;
                $nf->emitida = true;
                $nf->codnaturezaoperacao = $reg->codnaturezaoperacao;
                $nf->codoperacao = $nf->NaturezaOperacao->codoperacao;
                $nf->serie = 1;
                $nf->numero = 0;
                $nf->emissao = new Carbon;
                $nf->saida = $nf->emissao;
                $nf->save();
                
                $gerados[$reg->codfilial][$reg->codpessoa][$reg->codnaturezaoperacao] = [
                        'itens' => 0,
                        'codnotafiscal' => 0,
                    ];
                
            }
            
            $npb = NegocioProdutoBarra::findOrFail($reg->codnegocioprodutobarra);
            
            $nfpb = new NotaFiscalProdutoBarra;
            
            $nfpb->codnotafiscal = $nf->codnotafiscal;
            $nfpb->codprodutobarra = $npb->codprodutobarra;
            $nfpb->quantidade = $npb->quantidade;
            
            $preco = $npb->ProdutoBarra->Produto->preco;
            if (!empty($npb->ProdutoBarra->codprodutoembalagem)) {
                $preco *= $npb->ProdutoBarra->ProdutoEmbalagem->quantidade;
            }
            $nfpb->valorunitario = round($preco * 0.7, 2);
            
            $nfpb->valortotal = $nfpb->valorunitario * $npb->quantidade;
            $nfpb->codnegocioprodutobarra = $npb->codnegocioprodutobarra;
            
            $nfpb->calculaTributacao();
            
            $nfpb->save();
            
            $gerados[$reg->codfilial][$reg->codpessoa][$reg->codnaturezaoperacao]['itens']++;
            $gerados[$reg->codfilial][$reg->codpessoa][$reg->codnaturezaoperacao]['codnotafiscal'] = $nf->codnotafiscal;
            $nfs[$nf->codnotafiscal] = $nf;
        }
        
        DB::commit();
        
        return response()->json($gerados);
        
    }
}
