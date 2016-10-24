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
                , sum(case when (tblnegocio.lancamento >= '{$semestre->toIso8601String()}') then tblnegocioprodutobarra.valortotal * (tblnegocio.valortotal / tblnegocio.valorprodutos) else 0 end) as vendasemestrevalor
                , sum(case when (tblnegocio.lancamento >= '{$bimestre->toIso8601String()}') then tblnegocioprodutobarra.quantidade * coalesce(tblprodutoembalagem.quantidade, 1) else 0 end) as vendabimestrequantidade
                , sum(case when (tblnegocio.lancamento >= '{$bimestre->toIso8601String()}') then tblnegocioprodutobarra.valortotal * (tblnegocio.valortotal / tblnegocio.valorprodutos) else 0 end) as vendabimestrevalor
            from tblnegocio 
            inner join tblnaturezaoperacao on (tblnaturezaoperacao.codnaturezaoperacao = tblnegocio.codnaturezaoperacao)
            inner join tblnegocioprodutobarra on (tblnegocioprodutobarra.codnegocio = tblnegocio.codnegocio)
            inner join tblprodutobarra on (tblprodutobarra.codprodutobarra = tblnegocioprodutobarra.codprodutobarra)
            left join tblprodutoembalagem on (tblprodutoembalagem.codprodutoembalagem = tblprodutobarra.codprodutoembalagem)
            where tblnegocio.codnegociostatus = 2 --Fechado
            and tblnegocio.lancamento >= '{$ano->toIso8601String()}'
            and tblnaturezaoperacao.venda = true
            --and tblprodutobarra.codproduto in (select tblproduto.codproduto from tblproduto where tblproduto.codmarca = 29) -- ACRILEX
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
        
    }
}
