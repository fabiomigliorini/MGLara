<?php

namespace MGLara\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;

use Illuminate\Support\Facades\DB;

use MGLara\Jobs\EstoqueCalculaCustoMedio;
use MGLara\Models\EstoqueMes;
use MGLara\Models\EstoqueMovimento;
use MGLara\Models\EstoqueMovimentoTipo;
use MGLara\Models\EstoqueLocal;
use MGLara\Models\NotaFiscal;
use MGLara\Models\NotaFiscalProdutoBarra;
use MGLara\Models\ProdutoBarra;
use Carbon\Carbon;

class EstoqueAjustaFiscalCommand extends Command
{
    use DispatchesJobs;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'estoque:ajusta-fiscal {metodo?} {--codestoquelocal=} {--auto}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ajusta Estoque Fiscal';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        switch ($this->argument('metodo')) {

            case 'transfere-mesmo-produto-local':
                $this->transfereMesmoProdutoLocal();
                break;

            case 'transfere-mesmo-ncm':
                $this->transfereMesmoNcm();
                break;

            case 'gera-notas-fiscais-transferencia':
                $this->geraNotasFiscaisTransferencia();
                break;

            case 'transfere-manual':
                $this->transfereManual();
                break;

            default:
                $this->metodoDesconhecido();
                break;
        }
    }

    public function metodoDesconhecido()
    {
        $this->line('');
        $this->info('Utilização:');
        $this->line('');
        $this->line('php artisan estoque:ajusta-fiscal metodo --codestoquelocal=? --auto=true');
        $this->line('');
        $this->info('Métodos Disponíveis:');
        $this->line('');
        $this->line('- transfere-mesmo-produto-local: Ajusta estoque negativo da variação transferindo o saldo de outra variacao do mesmo produto, do mesmo local de estoque!');
        $this->line('- transfere-mesmo-ncm: Ajusta estoque negativo transferindo o saldo de outro produto com o mesmo NCM!');
        $this->line('- gera-notas-fiscais-transferencia: Gera notas de transferencia de uma filial para outra a fim de corrigir o estoque negativo!');
        $this->line('- transfere-manual: Solicita codestoquemes de onde transferir saldo para cobrir saldo negativo!');
    }

    public function transfereSaldo($quantidade, Carbon $data, $codprodutovariacaoorigem, $codestoquelocalorigem, $codprodutovariacaodestino, $codestoquelocaldestino)
    {
        DB::beginTransaction();

        $mes_origem = EstoqueMes::buscaOuCria($codprodutovariacaoorigem, $codestoquelocalorigem, true, $data);
        $mes_destino = EstoqueMes::buscaOuCria($codprodutovariacaodestino, $codestoquelocaldestino, true, $data);

        $tipo = EstoqueMovimentoTipo::findOrFail(4201);

        $mov_origem = new EstoqueMovimento();
        $mov_origem->codestoquemes = $mes_origem->codestoquemes;
        $mov_origem->codestoquemovimentotipo = $tipo->codestoquemovimentotipoorigem;
        $mov_origem->data = $data;
        $mov_origem->manual = true;
        $mov_origem->saidaquantidade = $quantidade;
        if (!$mov_origem->save()) {
            throw new Exception('Erro ao Salvar Movimento de Destino!');
        }

        $mov_destino = new EstoqueMovimento();
        $mov_destino->codestoquemes = $mes_destino->codestoquemes;
        $mov_destino->codestoquemovimentotipo = $tipo->codestoquemovimentotipo;
        $mov_destino->codestoquemovimentoorigem = $mov_origem->codestoquemovimento;
        $mov_destino->data = $data;
        $mov_destino->manual = true;
        $mov_destino->entradaquantidade = $quantidade;

        if (!$mov_destino->save()) {
            throw new Exception('Erro ao Salvar Movimento de Destino!');
        }

        $this->info("Criada Transferência de {$mes_origem->codestoquemes}({$mov_origem->codestoquemovimento}) para {$mes_destino->codestoquemes}({$mov_destino->codestoquemovimento})!");
        $this->line('');

        DB::commit();

        $this->dispatch((new EstoqueCalculaCustoMedio($mes_origem->codestoquemes))->onQueue('urgent'));
        $this->dispatch((new EstoqueCalculaCustoMedio($mes_destino->codestoquemes))->onQueue('urgent'));

        // aguarda dois segundos para rodar recalculo dos custos medios
        sleep(2);
    }

    public function geraNotasFiscaisTransferencia()
    {
        // Pega opcao do estoquelocal
        $codestoquelocal = $this->option('codestoquelocal');

        if (empty($codestoquelocal)) {
            $this->line('');
            $this->error('codestoquelocal não informado! Utilize --codestoquelocal=?');
            $this->line('');
            return;
        }

        // Instancia Estoque Local
        $el = EstoqueLocal::findOrFail($codestoquelocal);

        // Busca saldos negativos do estoquelocal
        $sql = "
            select p.codproduto, p.produto, elpv.codestoquelocal, elpv.codestoquelocalprodutovariacao, el.estoquelocal, es.customedio, pv.codprodutovariacao, pv.variacao, es.saldoquantidade, es.saldovalor, es.codestoquesaldo, (select mes.codestoquemes from tblestoquemes mes where mes.codestoquesaldo = es.codestoquesaldo order by mes desc limit 1) as codestoquemes
            from tblproduto p
            inner join tblprodutovariacao pv on (pv.codproduto = p.codproduto)
            inner join tblestoquelocalprodutovariacao elpv on (elpv.codprodutovariacao = pv.codprodutovariacao)
            inner join tblestoquelocal el on (el.codestoquelocal = elpv.codestoquelocal)
            inner join tblfilial f on (f.codfilial = el.codfilial)
            inner join tblestoquesaldo es on (es.codestoquelocalprodutovariacao = elpv.codestoquelocalprodutovariacao and es.fiscal = true)
            where elpv.codestoquelocal = $codestoquelocal
            and coalesce(es.saldoquantidade, 0) < 0
            order by p.codproduto, saldoquantidade
        ";

        $dados = DB::select($sql);

        // Pergunta se deseja continuar
        if (!$this->confirm(sizeof($dados) . ' registros com saldo negativo encontrados! Continuar [y|N]')) {
            return;
        }

        // Percorre negativos
        foreach ($dados as $negativo) {

            // Mostra registro
            $this->line("{$negativo->codproduto} - {$negativo->produto} - {$negativo->variacao} - {$negativo->saldoquantidade}");

            // Busca alternativas da mesma variacao
            $sql = "
                select coalesce(pv.variacao, '{ Sem Variacao }') as variacao, pv.codprodutovariacao, elpv.codestoquelocal, es.codestoquesaldo, es.saldoquantidade, es.customedio, es.codestoquelocalprodutovariacao
                from tblprodutovariacao pv
                inner join tblestoquelocalprodutovariacao elpv on (elpv.codprodutovariacao = pv.codprodutovariacao)
                inner join tblestoquesaldo es on (es.codestoquelocalprodutovariacao = elpv.codestoquelocalprodutovariacao and es.fiscal = true)
                where pv.codprodutovariacao = {$negativo->codprodutovariacao}
                and es.codestoquelocalprodutovariacao != {$negativo->codestoquelocalprodutovariacao}
                and es.saldoquantidade > 0
                order by elpv.codestoquelocal, es.saldoquantidade DESC, pv.variacao ASC NULLS FIRST
            ";

            $alternativas = DB::select($sql);

            // Soma quantidade disponivel das alternativas
            $qtd_alternativas = 0;
            foreach ($alternativas as $alternativa) {
                if (isset($cache_saldos[$alternativa->codestoquesaldo])) {
                    $qtd_alternativas += $cache_saldos[$alternativa->codestoquesaldo];
                } else {
                    $qtd_alternativas += $alternativa->saldoquantidade;
                }
            }

            // se a quantidade disponivel for menor que o saldo negativo
            // busca somente pelo produto, independente da variacao
            $saldo = abs($negativo->saldoquantidade);
            if ($qtd_alternativas < $saldo) {

                $sql = "
                    select coalesce(pv.variacao, '{ Sem Variacao }') as variacao, pv.codprodutovariacao, elpv.codestoquelocal, es.codestoquesaldo, es.saldoquantidade, es.customedio, es.codestoquelocalprodutovariacao
                    from tblprodutovariacao pv
                    inner join tblestoquelocalprodutovariacao elpv on (elpv.codprodutovariacao = pv.codprodutovariacao)
                    inner join tblestoquesaldo es on (es.codestoquelocalprodutovariacao = elpv.codestoquelocalprodutovariacao and es.fiscal = true)
                    where pv.codproduto = {$negativo->codproduto}
                    and es.codestoquelocalprodutovariacao != {$negativo->codestoquelocalprodutovariacao}
                    and es.saldoquantidade > 0
                    order by elpv.codestoquelocal, es.saldoquantidade DESC, pv.variacao ASC NULLS FIRST
                ";

                $alternativas = DB::select($sql);

            }

            // Percorre alternativas
            $i=0;
            while ($saldo > 0 && ($i <= (sizeof($alternativas) -1))) {

                // faz um cache do saldo, controlando quanto ja foi utilizado
                if (!isset($cache_saldos[$alternativas[$i]->codestoquesaldo])) {
                    $cache_saldos[$alternativas[$i]->codestoquesaldo] = $alternativas[$i]->saldoquantidade;
                }

                // utiliza a menor quantidade entre o saldo disponivel e o negativo
                $quantidade = min([$saldo, $cache_saldos[$alternativas[$i]->codestoquesaldo]]);

                // se tinha algo disponivel
                if ($quantidade > 0) {

                    // se a nota ja tiver mais de 500 itens, não deixa utilizar mais ela
                    if (isset($nfs[$alternativas[$i]->codestoquelocal])) {
                        if ($nfs[$alternativas[$i]->codestoquelocal]->NotaFiscalProdutoBarraS()->count() >= 500) {
                            unset($nfs[$alternativas[$i]->codestoquelocal]);
                        }
                    }

                    // cria nota fiscal
                    if (!isset($nfs[$alternativas[$i]->codestoquelocal])) {
                        $nfs[$alternativas[$i]->codestoquelocal] = new NotaFiscal;
                        $nfs[$alternativas[$i]->codestoquelocal]->codestoquelocal = $alternativas[$i]->codestoquelocal;
                        $nfs[$alternativas[$i]->codestoquelocal]->codfilial = $nfs[$alternativas[$i]->codestoquelocal]->EstoqueLocal->codfilial;
                        $nfs[$alternativas[$i]->codestoquelocal]->codpessoa = $el->Filial->codpessoa;
                        $nfs[$alternativas[$i]->codestoquelocal]->modelo = NotaFiscal::MODELO_NFE;
                        $nfs[$alternativas[$i]->codestoquelocal]->codnaturezaoperacao = 15; //Transferencia de Saida
                        $nfs[$alternativas[$i]->codestoquelocal]->codoperacao = $nfs[$alternativas[$i]->codestoquelocal]->NaturezaOperacao->codoperacao;
                        $nfs[$alternativas[$i]->codestoquelocal]->serie = 1;
                        $nfs[$alternativas[$i]->codestoquelocal]->numero = 0;
                        $nfs[$alternativas[$i]->codestoquelocal]->emitida = true;
                        $nfs[$alternativas[$i]->codestoquelocal]->emissao = new Carbon('now');
                        $nfs[$alternativas[$i]->codestoquelocal]->saida = $nfs[$alternativas[$i]->codestoquelocal]->emissao;
                        $nfs[$alternativas[$i]->codestoquelocal]->save();
                        $geradas[$nfs[$alternativas[$i]->codestoquelocal]->codnotafiscal] = 0;
                    }

                    // pega o produto barra
                    if (!$pb = ProdutoBarra::where('codprodutovariacao', '=', $alternativas[$i]->codprodutovariacao)->whereNull('codprodutoembalagem')->first()) {
                        if (!$pb = ProdutoBarra::where('codprodutovariacao', '=', $alternativas[$i]->codprodutovariacao)->first()) {
                            continue;
                        }
                    }

                    // cria o item da nota
                    $nfpb = new NotaFiscalProdutoBarra;
                    $nfpb->codprodutobarra = $pb->codprodutobarra;
                    $nfpb->codnotafiscal = $nfs[$alternativas[$i]->codestoquelocal]->codnotafiscal;
                    $nfpb->quantidade = $quantidade;
                    $nfpb->valorunitario = $alternativas[$i]->customedio;
                    if ($nfpb->valorunitario == 0) {
                        $nfpb->valorunitario = $negativo->customedio;
                    }
                    if ($nfpb->valorunitario == 0) {
                        $nfpb->valorunitario = $pb->Produto->preco * 0.7;
                    }
                    if (!empty($pb->codprodutoembalagem)) {
                        $nfpb->quantidade /= $pb->ProdutoEmbalagem->quantidade;
                        $nfpb->valorunitario *= $pb->ProdutoEmbalagem->quantidade;
                    }
                    $nfpb->valortotal = $nfpb->quantidade * $nfpb->valorunitario;
                    $nfpb->calculaTributacao();
                    $nfpb->save();

                    // incrementa a quantidade de itens da nota gerada
                    $geradas[$nfs[$alternativas[$i]->codestoquelocal]->codnotafiscal]++;

                    // diminui a quantidade do saldo e do cache
                    $saldo -= $quantidade;
                    $cache_saldos[$alternativas[$i]->codestoquesaldo] -= $quantidade;
                }

                // proxima alternativa
                $i++;
            }
        }

        // lista notas geradas e a quantidade de notas
        foreach ($geradas as $codnotafiscal => $itens) {
            $this->line("Gerada NF $codnotafiscal com $itens itens");
        }
    }

    public function transfereMesmoProdutoLocal()
    {
        $codestoquelocal = $this->option('codestoquelocal');
        $auto = $this->option('auto');

        if (empty($codestoquelocal)) {
            $this->line('');
            $this->error('codestoquelocal não informado! Utilize --codestoquelocal=?');
            $this->line('');
            return;
        }

        $sql = "
            select p.codproduto, p.produto, elpv.codestoquelocal, elpv.codestoquelocalprodutovariacao, el.estoquelocal, pv.codprodutovariacao, pv.variacao, es.saldoquantidade, es.saldovalor, es.codestoquesaldo, (select mes.codestoquemes from tblestoquemes mes where mes.codestoquesaldo = es.codestoquesaldo order by mes desc limit 1) as codestoquemes
            from tblproduto p
            inner join tblprodutovariacao pv on (pv.codproduto = p.codproduto)
            inner join tblestoquelocalprodutovariacao elpv on (elpv.codprodutovariacao = pv.codprodutovariacao)
            inner join tblestoquelocal el on (el.codestoquelocal = elpv.codestoquelocal)
            inner join tblfilial f on (f.codfilial = el.codfilial)
            inner join tblestoquesaldo es on (es.codestoquelocalprodutovariacao = elpv.codestoquelocalprodutovariacao and es.fiscal = true)
            where elpv.codestoquelocal = $codestoquelocal
            and coalesce(es.saldoquantidade, 0) < 0
            order by p.codproduto, saldoquantidade
        ";

        $dados = DB::select($sql);

        if (!$this->confirm(sizeof($dados) . ' registros com saldo negativo encontrados! Continuar [y|N]')) {
            return;
        }

        foreach ($dados as $negativo) {
            $this->line("{$negativo->codproduto} - {$negativo->produto} - {$negativo->variacao} - {$negativo->saldoquantidade}");

            $sql = "
                select coalesce(pv.variacao, '{ Sem Variacao }') as variacao, pv.codprodutovariacao, elpv.codestoquelocal, es.codestoquesaldo, es.saldoquantidade, es.codestoquelocalprodutovariacao
                from tblprodutovariacao pv
                inner join tblestoquelocalprodutovariacao elpv on (elpv.codprodutovariacao = pv.codprodutovariacao and elpv.codestoquelocal = {$negativo->codestoquelocal})
                inner join tblestoquesaldo es on (es.codestoquelocalprodutovariacao = elpv.codestoquelocalprodutovariacao and es.fiscal = true)
                where pv.codproduto = {$negativo->codproduto}
                and es.codestoquelocalprodutovariacao != {$negativo->codestoquelocalprodutovariacao}
                and es.saldoquantidade > 0
                order by es.saldoquantidade DESC, pv.variacao ASC NULLS FIRST
            ";

            $alternativas = DB::select($sql);
            if (sizeof($alternativas) == 0) {
                $this->info('Sem alternativas');
                continue;
            }

            $headers = ['#', 'Variação', 'Saldo'];

            $data=[];
            $choices=[];
            foreach ($alternativas as $i => $alt) {
                $choices[$i] = $i;
                $data[] = [
                    'indice' => $i,
                    'variacao' => $alt->variacao,
                    'saldo' => $alt->saldoquantidade,
                ];
            }
            $choices[] = 'Nenhum';

            $this->table($headers, $data);

            if (!$auto) {
                $escolhido = $this->choice('Transferir de qual alternativa?', $choices, false);

                if ($escolhido == 'Nenhum') {
                    continue;
                }
            } else {
                $escolhido = 0;
            }

            $origem = $alternativas[$escolhido];

            $quantidade = min([abs($negativo->saldoquantidade), abs($origem->saldoquantidade)]);
            $data = Carbon::now();
            $codprodutovariacaoorigem = $origem->codprodutovariacao;
            $codestoquelocalorigem = $origem->codestoquelocal;
            $codprodutovariacaodestino = $negativo->codprodutovariacao;
            $codestoquelocaldestino = $negativo->codestoquelocal;

            $this->transfereSaldo(
                $quantidade,
                $data,
                $codprodutovariacaoorigem,
                $codestoquelocalorigem,
                $codprodutovariacaodestino,
                $codestoquelocaldestino);

        }

    }


    public function transfereMesmoNcm()
    {
        $auto = $this->option('auto');
        
        $sql_negativos = "
            select p.codproduto, p.produto, pv.variacao, coalesce(p.preco, 0) as preco, el.sigla, em.saldoquantidade, em.saldovalor, em.customedio, em.codestoquemes, em.mes, elpv.codprodutovariacao, elpv.codestoquelocal, n.ncm, p.codncm, f.codempresa, es.saldoquantidade as saldoquantidade_atual
            from tblestoquemes em
            inner join tblestoquesaldo es on (es.codestoquesaldo = em.codestoquesaldo and es.fiscal = true)
            inner join tblestoquelocalprodutovariacao elpv on (elpv.codestoquelocalprodutovariacao = es.codestoquelocalprodutovariacao)
            inner join tblprodutovariacao pv on (pv.codprodutovariacao = elpv.codprodutovariacao)
            inner join tblproduto p on (p.codproduto = pv.codproduto)
            inner join tblestoquelocal el on (el.codestoquelocal = elpv.codestoquelocal)
            inner join tblfilial f on (f.codfilial = el.codfilial)
            inner join tblncm n on (n.codncm = p.codncm)
            where em.saldoquantidade < 0
            order by em.mes, n.ncm, p.preco DESC, p.produto, pv.variacao nulls first, elpv.codestoquelocal
            limit 1
            ";

        $i_alternativa_produto = 0;
        
        while ($dados = DB::select($sql_negativos))
        {
            $negativo = $dados[0];
            $this->line('');
            $this->line('');
            $this->line('');
            $this->line('');
            $this->info("http://192.168.1.205/MGLara/estoque-mes/$negativo->codestoquemes");


            $this->table(
                [
                    'Mês',
                    '#',
                    'Produto',
                    'Variação',
                    'Venda',
                    'Loc',
                    'Qtd',
                    'Atual',
                    'Val',
                    'Médio',
                    'NCM',
                ], [[
                    $negativo->mes,
                    $negativo->codproduto,
                    $negativo->produto,
                    $negativo->variacao,
                    $negativo->preco,
                    $negativo->sigla,
                    $negativo->saldoquantidade,
                    $negativo->saldoquantidade_atual,
                    $negativo->saldovalor,
                    $negativo->customedio,
                    $negativo->ncm,
                ]]);

            $sql = "
                select
                    p.codproduto
                    , p.produto
                    , p.preco
                    , coalesce(fiscal.saldoquantidade_atual, 0) - coalesce(fisico.saldoquantidade_atual, 0) as sobra_atual
                    , coalesce(fiscal.saldoquantidade, 0) - coalesce(fisico.saldoquantidade, 0) as sobra
                    , fisico.saldoquantidade as fisico_saldoquantidade
                    , fiscal.saldoquantidade as fiscal_saldoquantidade
                    , fiscal.customedio as fiscal_customedio
                from tblproduto p
                left join (
                    select pv.codproduto, sum(em.saldoquantidade) as saldoquantidade, sum(em.saldovalor) as saldovalor, avg(em.customedio) as customedio, sum(es.saldoquantidade) as saldoquantidade_atual
                    from tblestoquelocalprodutovariacao elpv
                    inner join tblprodutovariacao pv on (pv.codprodutovariacao = elpv.codprodutovariacao)
                    inner join tblestoquelocal el on (el.codestoquelocal = elpv.codestoquelocal)
                    inner join tblfilial f on (f.codfilial = el.codfilial)
                    inner join tblestoquesaldo es on (es.codestoquelocalprodutovariacao = elpv.codestoquelocalprodutovariacao and es.fiscal = false)
                    inner join tblestoquemes em on (em.codestoquemes = (select em2.codestoquemes from tblestoquemes em2 where em2.codestoquesaldo = es.codestoquesaldo and em2.mes <= '{$negativo->mes}' order by mes desc limit 1))
                    where f.codempresa = {$negativo->codempresa}
                    group by pv.codproduto
                ) fisico on (fisico.codproduto = p.codproduto)
                left join (
                    select pv.codproduto, sum(em.saldoquantidade) as saldoquantidade, sum(em.saldovalor) as saldovalor, avg(em.customedio) as customedio, sum(es.saldoquantidade) as saldoquantidade_atual
                    from tblestoquelocalprodutovariacao elpv
                    inner join tblprodutovariacao pv on (pv.codprodutovariacao = elpv.codprodutovariacao)
                    inner join tblestoquelocal el on (el.codestoquelocal = elpv.codestoquelocal)
                    inner join tblfilial f on (f.codfilial = el.codfilial)
                    inner join tblestoquesaldo es on (es.codestoquelocalprodutovariacao = elpv.codestoquelocalprodutovariacao and es.fiscal = true)
                    inner join tblestoquemes em on (em.codestoquemes = (select em2.codestoquemes from tblestoquemes em2 where em2.codestoquesaldo = es.codestoquesaldo and em2.mes <= '{$negativo->mes}' order by mes desc limit 1))
                    where f.codempresa = {$negativo->codempresa}
                    group by pv.codproduto
                ) fiscal on (fiscal.codproduto = p.codproduto)
                where p.codtipoproduto = 0
                AND p.codncm = {$negativo->codncm}
                AND coalesce(fiscal.saldoquantidade_atual, 0) > coalesce(fisico.saldoquantidade_atual, 0)
                AND coalesce(fiscal.saldoquantidade, 0) > coalesce(fisico.saldoquantidade, 0)
                and coalesce(fiscal.saldoquantidade_atual, 0) > 1
                and coalesce(fiscal.saldoquantidade, 0) > 1
                order by abs(p.preco - {$negativo->preco})
            ";

            $alt_prods = DB::select($sql);

            $data=[];
            $choices=[];
            foreach ($alt_prods as $i => $alt) {
                $choices[$i] = $alt->codproduto;
                $data[$alt->codproduto] = [
                    $alt->codproduto,
                    $alt->produto,
                    $alt->preco,
                    $alt->sobra_atual,
                    $alt->sobra,
                    $alt->fisico_saldoquantidade,
                    $alt->fiscal_saldoquantidade,
                    $alt->fiscal_customedio,
                ];
                $cods[$alt->codproduto] = $i;
            }

            $this->table([
                '#',
                'Produto',
                'Preço',
                'Sobra At',
                'Sobra',
                'Fisico',
                'Fiscal',
                'Médio',
            ], $data);

            if (!$auto) {
                $codproduto = $this->choice('Transferir de qual alternativa?', $choices, false);
            } else {
                $this->error($i_alternativa_produto);
                $codproduto = $alt_prods[$i_alternativa_produto]->codproduto;
                $this->error($codproduto);
            }

            $produto = $alt_prods[$cods[$codproduto]];

            $sql = "
                select
                    em_fiscal.codestoquemes
                    , el.codestoquelocal
                    , el.sigla
                    , pv.codprodutovariacao
                    , pv.variacao
                    , es_fiscal.saldoquantidade - case when (es_fisico.saldoquantidade > 0) then es_fisico.saldoquantidade else 0 end as sobra_atual
                    , em_fiscal.saldoquantidade - case when (em_fisico.saldoquantidade > 0) then em_fisico.saldoquantidade else 0 end as sobra
                    , es_fisico.saldoquantidade as fisico_saldoquantidade
                    , es_fiscal.saldoquantidade as fiscal_saldoquantidade
                from tblprodutovariacao pv
                inner join tblestoquelocalprodutovariacao elpv on (elpv.codprodutovariacao = pv.codprodutovariacao)
                inner join tblestoquelocal el on (el.codestoquelocal = elpv.codestoquelocal)
                left join tblestoquesaldo es_fiscal on (es_fiscal.codestoquelocalprodutovariacao = elpv.codestoquelocalprodutovariacao and es_fiscal.fiscal = true)
                left join tblestoquemes em_fiscal on (em_fiscal.codestoquemes = (select em.codestoquemes from tblestoquemes em where em.mes <= '{$negativo->mes}' and em.codestoquesaldo = es_fiscal.codestoquesaldo order by mes desc limit 1))
                left join tblestoquesaldo es_fisico on (es_fisico.codestoquelocalprodutovariacao = elpv.codestoquelocalprodutovariacao and es_fisico.fiscal = false)
                left join tblestoquemes em_fisico on (em_fisico.codestoquemes = (select em.codestoquemes from tblestoquemes em where em.mes <= '{$negativo->mes}' and em.codestoquesaldo = es_fisico.codestoquesaldo order by mes desc limit 1))
                where pv.codproduto = {$produto->codproduto}
                and (coalesce(es_fiscal.saldoquantidade, 0) > 0 or coalesce(es_fisico.saldoquantidade, 0) > 0)
                order by es_fiscal.saldoquantidade - case when (es_fisico.saldoquantidade > 0) then es_fisico.saldoquantidade else 0 end desc
            ";

            $alt_meses = DB::select($sql);

            $data=[];
            $choices=[];
            foreach ($alt_meses as $i => $alt) {
                $choices[$i] = $alt->codestoquemes;
                $data[$alt->codestoquemes] = [
                    $alt->codestoquemes,
                    $alt->sigla,
                    $alt->variacao,
                    $alt->sobra_atual,
                    $alt->sobra,
                    $alt->fisico_saldoquantidade,
                    $alt->fiscal_saldoquantidade,
                ];
                $cods[$alt->codestoquemes] = $i;
            }

            $this->table([
                '#',
                'Loc',
                'Variacao',
                'Sobra At',
                'Sobra',
                'Fisico',
                'Fiscal',
            ], $data);

            if (!$auto) {
                $codestoquemes = $this->choice('Transferir de qual alternativa?', $choices, false);
            } else {
                $codestoquemes = null;
                foreach ($alt_meses as $alt) {
                    if ($alt->sobra_atual > 0 and $alt->sobra > 0) {
                        $codestoquemes = $alt->codestoquemes;
                        break;
                    }
                }
            }
            
            if ($codestoquemes == null) {
                $i_alternativa_produto++;
                continue;
            }
            
            $mes = $alt_meses[$cods[$codestoquemes]];
            
            $quatidade = ($negativo->saldoquantidade_atual < $negativo->saldoquantidade)?abs($negativo->saldoquantidade_atual):abs($negativo->saldoquantidade);

            $quantidade = min([
                $produto->sobra_atual,
                $produto->sobra,
                $mes->sobra_atual,
                $mes->sobra,
                $quatidade,
            ]);

            if ($quantidade <= 0) {
                $this->error('Estoquemes escolhido não tem saldo!');
                continue;
            }

            if (!$auto) {
                $quantidade = $this->ask('Informe a quantidade:', $quantidade);
            }

            if ($quantidade <= 0) {
                continue;
            }

            $this->transfereSaldo(
                $quantidade,
                Carbon::createFromFormat('Y-m-d', $negativo->mes)->endOfMonth(),
                $mes->codprodutovariacao,
                $mes->codestoquelocal,
                $negativo->codprodutovariacao,
                $negativo->codestoquelocal
            );
            
            $i_alternativa_produto = 0;
            
        }


    }

    public function transfereManual()
    {

        $sql = "
            select p.codproduto, p.produto, pv.variacao, p.preco, el.sigla, em.saldoquantidade, em.saldovalor, em.customedio, em.codestoquemes, em.mes, elpv.codprodutovariacao, elpv.codestoquelocal, n.ncm
            from tblestoquemes em
            inner join tblestoquesaldo es on (es.codestoquesaldo = em.codestoquesaldo and es.fiscal = true)
            inner join tblestoquelocalprodutovariacao elpv on (elpv.codestoquelocalprodutovariacao = es.codestoquelocalprodutovariacao)
            inner join tblprodutovariacao pv on (pv.codprodutovariacao = elpv.codprodutovariacao)
            inner join tblproduto p on (p.codproduto = pv.codproduto)
            inner join tblestoquelocal el on (el.codestoquelocal = elpv.codestoquelocal)
            inner join tblncm n on (n.codncm = p.codncm)
            where em.saldoquantidade < 0
            order by em.mes, n.ncm, p.produto, pv.variacao nulls first, elpv.codestoquelocal
            limit 1
            ";


        while ($dados = DB::select($sql))
        {
            $negativo = $dados[0];
            $this->line('');
            $this->line('');
            $this->line('');
            $this->line('');
            $this->info("http://192.168.1.205/MGLara/estoque-mes/$negativo->codestoquemes");

            $data = Carbon::createFromFormat('Y-m-d', $negativo->mes)->endOfMonth();

            $this->table(
                [
                    'Mês',
                    '#',
                    'Produto',
                    'Variação',
                    'Venda',
                    'Loc',
                    'Qtd',
                    'Val',
                    'Médio',
                    'NCM',
                ], [[
                    $negativo->mes,
                    $negativo->codproduto,
                    $negativo->produto,
                    $negativo->variacao,
                    $negativo->preco,
                    $negativo->sigla,
                    $negativo->saldoquantidade,
                    $negativo->saldovalor,
                    $negativo->customedio,
                    $negativo->ncm,
                ]]);

            do {

                $codestoquemes_origem = $this->ask('Informe o codestoquemes para transferir o saldo:');

                if (!$mes_origem = EstoqueMes::find($codestoquemes_origem)) {
                    $this->error('Estoque Mes não localizado!');
                    continue;
                }

                $this->table(
                    [
                        '#',
                        'Produto',
                        'Variação',
                        'Venda',
                        'Loc',
                        'Qtd',
                        'Val',
                        'Médio',
                        'NCM',
                    ], [[
                        $mes_origem->EstoqueSaldo->EstoqueLocalProdutoVariacao->ProdutoVariacao->codproduto,
                        $mes_origem->EstoqueSaldo->EstoqueLocalProdutoVariacao->ProdutoVariacao->Produto->produto,
                        $mes_origem->EstoqueSaldo->EstoqueLocalProdutoVariacao->ProdutoVariacao->variacao,
                        $mes_origem->EstoqueSaldo->EstoqueLocalProdutoVariacao->ProdutoVariacao->Produto->preco,
                        $mes_origem->EstoqueSaldo->EstoqueLocalProdutoVariacao->EstoqueLocal->sigla,
                        $mes_origem->EstoqueSaldo->saldoquantidade,
                        $mes_origem->EstoqueSaldo->saldovalor,
                        $mes_origem->EstoqueSaldo->customedio,
                        $mes_origem->EstoqueSaldo->EstoqueLocalProdutoVariacao->ProdutoVariacao->Produto->Ncm->ncm,
                    ]]);

                if ($mes_origem->EstoqueSaldo->saldoquantidade <= 0) {
                    $this->error('Este produto não tem saldo de estoque disponível!');
                    continue;
                }

                if ($this->confirm('Transferir deste Saldo?', true) == true) {
                    break;
                }

            } while (true);

            $quantidade = min([abs($negativo->saldoquantidade), abs($mes_origem->saldoquantidade)]);
            $quantidade = $this->ask('Informe a quantidade:', $quantidade);

            if ($quantidade <= 0) {
                continue;
            }

            $this->transfereSaldo(
                $quantidade,
                $data,
                $mes_origem->EstoqueSaldo->EstoqueLocalProdutoVariacao->codprodutovariacao,
                $mes_origem->EstoqueSaldo->EstoqueLocalProdutoVariacao->codestoquelocal,
                $negativo->codprodutovariacao,
                $negativo->codestoquelocal
                );


        }

    }


}
