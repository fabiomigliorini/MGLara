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
use MGLara\Models\ProdutoVariacao;
use Illuminate\Support\Facades\DB;

/**
 * @property $codprodutovariacao bigint
 * @property $codestoquelocal bigint
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
        Log::info('EstoqueCalculaEstatisticas Job Inicializada', ['attemps' => $this->attempts(), 'codprodutovariacao' => $this->codprodutovariacao, 'codestoquelocal' => $this->codestoquelocal]);
        
        Log::info('EstoqueCalculaEstatisticas Início Calculo data Ultima Compra');
        
        // Busca todos produtos variacao
        $pvs = ProdutoVariacao::orderBy('codprodutovariacao');
        if (!empty($this->codprodutovariacao)) {
            $pvs->where('codprodutovariacao', $this->codprodutovariacao);
        }
        
        // Percorre ajustando a data da ultima compra
        foreach ($pvs->get() as $pv) {
            $data = null;
            $quantidade = null;
            $custo = null;
            $sql = "
                select 
                    nf.emissao
                    , sum(nfpb.quantidade * coalesce(pe.quantidade, 1)) as quantidade
                    , sum(nfpb.valortotal) as valortotal 
                from tblprodutobarra pb
                left join tblprodutoembalagem pe on (pe.codprodutoembalagem = pb.codprodutoembalagem)
                inner join tblnotafiscalprodutobarra nfpb on (nfpb.codprodutobarra = pb.codprodutobarra)
                inner join tblnotafiscal nf on (nf.codnotafiscal = nfpb.codnotafiscal)
                inner join tblnaturezaoperacao no on (no.codnaturezaoperacao = nf.codnaturezaoperacao)
                where pb.codprodutovariacao = {$pv->codprodutovariacao}
                and no.compra = true
                group by pb.codprodutovariacao, nf.emissao 
                order by nf.emissao desc
                limit 1
                ";
            $compra = DB::select($sql);
            if (isset($compra[0])) {
                $data = $compra[0]->emissao;
                $quantidade = $compra[0]->quantidade;
                $custo = $compra[0]->valortotal;
            }
            if ($quantidade > 0) {
                $custo /= $quantidade;
            }
            $ret = ProdutoVariacao::where('codprodutovariacao', $pv->codprodutovariacao)->update([
                'dataultimacompra' => $data,
                'quantidadeultimacompra' => $quantidade,
                'custoultimacompra' => $custo,
            ]);
        }
    
        Log::info('EstoqueCalculaEstatisticas Fim Calculo Ultima Compra');

        $bimestre = new Carbon('today - 2 months');
        $semestre = new Carbon('today - 6 months');
        $ano = new Carbon('today - 1 year');
        $agora = new Carbon('now');
        
        Log::info('EstoqueCalculaEstatisticas Calculando Volume de vendas');

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
            group by 
                    tblnegocio.codestoquelocal
                    , tblprodutobarra.codprodutovariacao
                    , tblprodutobarra.codproduto
        ";
        
        $regs = DB::select($sql);
        
        Log::info('EstoqueCalculaEstatisticas Atualizando volume de vendas');
        
        $atualizados = [];
        foreach ($regs as $reg) {
            $elpv = EstoqueLocalProdutoVariacao::buscaOuCria($reg->codprodutovariacao, $reg->codestoquelocal);
            $ret = EstoqueLocalProdutoVariacao::where('codestoquelocalprodutovariacao', $elpv->codestoquelocalprodutovariacao)->update([
                'vendaanoquantidade' => $reg->vendaanoquantidade,
                'vendaanovalor' => $reg->vendaanovalor,
                'vendasemestrequantidade' => $reg->vendasemestrequantidade,
                'vendasemestrevalor' => $reg->vendasemestrevalor,
                'vendabimestrequantidade' => $reg->vendabimestrequantidade,
                'vendabimestrevalor' => $reg->vendabimestrevalor,
                'vendaultimocalculo' => $agora,
                'vendadiaquantidadeprevisao' => ($reg->vendasemestrequantidade / $semestre->diffInDays()),
            ]);
            $atualizados[] = $elpv->codestoquelocalprodutovariacao;
        }
        Log::info('EstoqueCalculaEstatisticas Fim Atualizacao volume de vendas', ['atualizados' => sizeof($atualizados), 'calculados' => sizeof($regs)]);
        
        $elpvs = EstoqueLocalProdutoVariacao::whereNotIn('codestoquelocalprodutovariacao', $atualizados);
        if (!empty($this->codprodutovariacao)) {
            $elpvs = $elpvs->where('codprodutovariacao', $this->codprodutovariacao);
        }
        
        if (!empty($this->codestoquelocal)) {
            $elpvs = $elpvs->where('codestoquelocal', $this->codestoquelocal);
        }
        $ret = $elpvs->update([
            'vendaanoquantidade' => null,
            'vendaanovalor' => null,
            'vendasemestrequantidade' => null,
            'vendasemestrevalor' => null,
            'vendabimestrequantidade' => null,
            'vendabimestrevalor' => null,
            'vendaultimocalculo' => $agora,
            'vendadiaquantidadeprevisao' => null,
        ]);
        Log::info('EstoqueCalculaEstatisticas Não Calculados', ['ret' => $ret]);
        
    }
}
