<?php

namespace MGLara\Jobs;

use MGLara\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

use MGLara\Models\EstoqueMes;
use MGLara\Models\EstoqueMovimentoTipo;
use MGLara\Models\EstoqueLocalProdutoVariacao;
use Illuminate\Support\Facades\DB;

/**
 * @property $codestoquemes bigint
 * @property $ciclo bigint
 */

class EstoqueCalculaEstatisticas extends Job implements SelfHandling, ShouldQueue
{
    
    use InteractsWithQueue, SerializesModels, DispatchesJobs;
    
    protected $codprodutovariacao;
    protected $codestoquelocal;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($codprodutovariacao = null, $codestoquelocal = null)
    {
        $this->codprodutovariacao = $codprodutovariacao;
        $this->codestoquelocal = $codestoquelocal;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //DB::enableQueryLog();
        Log::info('EstoqueCalculaEstatisticas', ['codprodutovariacao' => $this->codprodutovariacao, 'codestoquelocal' => $this->codestoquelocal]);
        
        $bimestre = new Carbon('today - 2 months');
        $semestre = new Carbon('today - 6 months');
        $ano = new Carbon('today - 1 year');
        $agora = new Carbon();
        
        $sql = "
            select 
                tblnegocio.codestoquelocal
                , tblprodutobarra.codprodutovariacao
                --, tblprodutobarra.codproduto
                , sum(tblnegocioprodutobarra.quantidade * coalesce(tblprodutoembalagem.quantidade, 1)) as vendaanoquantidade
                , sum(tblnegocioprodutobarra.valortotal) as vendaanovalor
                , sum(case when (tblnegocio.lancamento >= '{$semestre->toIso8601String()}') then tblnegocioprodutobarra.quantidade * coalesce(tblprodutoembalagem.quantidade, 1) else 0 end) as vendasemestrequantidade
                , sum(case when (tblnegocio.lancamento >= '{$semestre->toIso8601String()}') then tblnegocioprodutobarra.valortotal else 0 end) as vendasemestrevalor
                , sum(case when (tblnegocio.lancamento >= '{$bimestre->toIso8601String()}') then tblnegocioprodutobarra.quantidade * coalesce(tblprodutoembalagem.quantidade, 1) else 0 end) as vendabimestrequantidade
                , sum(case when (tblnegocio.lancamento >= '{$bimestre->toIso8601String()}') then tblnegocioprodutobarra.valortotal else 0 end) as vendabimestrevalor
            from tblnegocio 
            inner join tblnaturezaoperacao on (tblnaturezaoperacao.codnaturezaoperacao = tblnegocio.codnaturezaoperacao)
            inner join tblnegocioprodutobarra on (tblnegocioprodutobarra.codnegocio = tblnegocio.codnegocio)
            inner join tblprodutobarra on (tblprodutobarra.codprodutobarra = tblnegocioprodutobarra.codprodutobarra)
            left join tblprodutoembalagem on (tblprodutoembalagem.codprodutoembalagem = tblprodutobarra.codprodutoembalagem)
            where tblnegocio.codnegociostatus = 2 --Fechado
            and tblnegocio.lancamento >= '{$ano->toIso8601String()}'
            and tblnaturezaoperacao.venda = true
            ";
            
        if (!empty($this->codprodutovariacao)) {
            $sql .= "
                and tblprodutobarra.codprodutovariacao = {$this->codprodutovariacao}
            ";
        }
        
        if (!empty($this->codestoquelocal)) {
            $sql .= "
                and tblnegocio.codestoquelocal = {$this->codestoquelocal}
            ";
        }
        
        $sql .= "
            --and tblprodutobarra.codprodutovariacao in (14991, 14992)
            --and tblprodutobarra.codproduto in (100)
            group by 
                    tblnegocio.codestoquelocal
                    , tblprodutobarra.codprodutovariacao
                    , tblprodutobarra.codproduto
        ";
        
        $regs = DB::select($sql);
        
        foreach ($regs as $reg) {
            $elpv = EstoqueLocalProdutoVariacao::buscaOuCria($reg->codprodutovariacao, $reg->codestoquelocal);
            $elpv->vendaanoquantidade = $reg->vendaanoquantidade;
            $elpv->vendaanovalor = $reg->vendaanovalor;
            $elpv->vendasemestrequantidade = $reg->vendasemestrequantidade;
            $elpv->vendasemestrevalor = $reg->vendasemestrevalor;
            $elpv->vendabimestrequantidade = $reg->vendabimestrequantidade;
            $elpv->vendabimestrevalor = $reg->vendabimestrevalor;
            $elpv->vendaultimocalculo = $agora;
            $elpv->vendadiaquantidadeprevisao = ($reg->vendasemestrequantidade / $semestre->diffInDays());
            $elpv->save();
        }
        
        //$query = DB::query('tblnegocio');
        //dd(DB::getQueryLog());
                
        /*
        dd($query);
        
        $dados = $query->get();
        
        dd ($dados);
        dd ($sql);
        
        dd($this->codprodutovariacao);
        */
        /*
        if ($this->ciclo > 10)
            return;
        
        $mes = EstoqueMes::findOrFail($this->codestoquemes);
        
        //busca totais de registros nao baseados no custo medio
        $sql = "
            select 
                sum(entradaquantidade) entradaquantidade
                , sum(entradavalor) entradavalor
            from tblestoquemovimento mov
            left join tblestoquemovimentotipo tipo on (tipo.codestoquemovimentotipo = mov.codestoquemovimentotipo)
            where mov.codestoquemes = {$mes->codestoquemes}
            and tipo.preco in (" . EstoqueMovimentoTipo::PRECO_INFORMADO . ", " . EstoqueMovimentoTipo::PRECO_ORIGEM . ")";

        $mov = DB::select($sql);
        $mov = $mov[0];

        //busca saldo inicial
        $inicialquantidade = 0;
        $inicialvalor = 0;
        $anterior = $mes->buscaAnteriores(1);
        if (isset($anterior[0]))
        {
            $inicialquantidade = $anterior[0]->saldoquantidade;
            $inicialvalor = $anterior[0]->saldovalor;
        }

        //calcula custo medio
        $valor = $mov->entradavalor;
        $quantidade = $mov->entradaquantidade;
        if ($inicialquantidade > 0 && $inicialvalor > 0)
        {
            $valor += $inicialvalor;
            $quantidade += $inicialquantidade;
        }
        $customedio = 0;
        if ($quantidade != 0)
            $customedio = abs($valor/$quantidade);
        
        //recalcula valor movimentacao com base custo medio
        $sql = "
            update tblestoquemovimento
            set saidavalor = saidaquantidade * $customedio
                , entradavalor = entradaquantidade * $customedio
            where tblestoquemovimento.codestoquemes = {$mes->codestoquemes} 
            and tblestoquemovimento.codestoquemovimentotipo in 
                (select t.codestoquemovimentotipo from tblestoquemovimentotipo t where t.preco = " . EstoqueMovimentoTipo::PRECO_MEDIO . ")
            ";
            
        $ret = DB::update($sql);
        
        //recalcula valor movimentacao para registros originados a partir deste mes
        $sql = "
            update tblestoquemovimento
            set entradavalor = orig.saidavalor
                , saidavalor = orig.entradavalor
            from tblestoquemovimento orig
            where tblestoquemovimento.codestoquemovimentoorigem = orig.codestoquemovimento
            and orig.codestoquemes = {$mes->codestoquemes}
            ";
            
        $ret = DB::update($sql);
        
        //busca totais movimentados do 
        $sql = "
            select 
                sum(entradaquantidade) entradaquantidade
                , sum(entradavalor) entradavalor
                , sum(saidaquantidade) saidaquantidade
                , sum(saidavalor) saidavalor
            from tblestoquemovimento mov
            left join tblestoquemovimentotipo tipo on (tipo.codestoquemovimentotipo = mov.codestoquemovimentotipo)
            where mov.codestoquemes = {$mes->codestoquemes}
            ";

        $mov = DB::select($sql);
        $mov = $mov[0];
        
        //calcula custo medio e totais novamente
        $mes->inicialquantidade = $inicialquantidade;
        $mes->inicialvalor = $mes->inicialquantidade * $customedio;
        $mes->entradaquantidade = $mov->entradaquantidade;
        $mes->entradavalor = $mov->entradavalor;
        $mes->saidaquantidade = $mov->saidaquantidade;
        $mes->saidavalor = $mov->saidavalor;
        $mes->saldoquantidade = $inicialquantidade + $mov->entradaquantidade - $mov->saidaquantidade;
        $mes->saldovalor = $mes->saldoquantidade * $customedio;
        $customedioanterior = $mes->customedio;
        $mes->customedio = $customedio;

        $mes->save();
        
        $customediodiferenca = abs($customedio - $customedioanterior);
        
        $mesesRecalcular = [];
        if ($customediodiferenca > 0.01)
        {
            $sql = "
                select distinct dest.codestoquemes
                from tblestoquemovimento orig
                inner join tblestoquemovimento dest on (dest.codestoquemovimentoorigem = orig.codestoquemovimento)
                where orig.codestoquemes = {$mes->codestoquemes}
                ";
            $ret = DB::select($sql);
            foreach ($ret as $row)
                $mesesRecalcular[] = $row->codestoquemes;
        }
        
        $proximo = $mes->buscaProximos(1);
        if (isset($proximo[0]))
            $mesesRecalcular[] = $proximo[0]->codestoquemes;
        else
        {
            $mes->EstoqueSaldo->saldoquantidade = $mes->saldoquantidade;
            $mes->EstoqueSaldo->saldovalor = $mes->saldovalor;
            $mes->EstoqueSaldo->customedio = $mes->customedio;
            $mes->EstoqueSaldo->save();
            
            //atualiza 'dataentrada'
            DB::update(DB::raw("
                update tblestoquesaldo
                set dataentrada = (
                        select 
                                x.data 
                        from (
                                select 
                                        mov.data
                                        , mov.entradaquantidade
                                        , sum(mov.entradaquantidade) over (order by mov.data desc) as soma
                                from tblestoquemes mes
                                inner join tblestoquemovimento mov on (mov.codestoquemes = mes.codestoquemes)
                                inner join tblestoquemovimentotipo tipo on (tipo.codestoquemovimentotipo = mov.codestoquemovimentotipo)
                                where mes.codestoquesaldo = tblestoquesaldo.codestoquesaldo
                                and mov.entradaquantidade is not null
                                and tipo.atualizaultimaentrada = true
                                ) x
                        where soma >= tblestoquesaldo.saldoquantidade
                        order by data DESC
                        limit 1
                )
                where tblestoquesaldo.codestoquesaldo = {$mes->codestoquesaldo}
            "));
        }
        
        foreach ($mesesRecalcular as $mes)
            $this->dispatch((new EstoqueCalculaCustoMedio($mes, $this->ciclo +1))->onQueue('urgent'));
        */
    }
}
