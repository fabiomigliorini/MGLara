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
            case 'variacao-negativa':
                $this->variacaoNegativa();
                break;

            case 'emite-transferencias':
                $this->emiteTransferencias();
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
        $this->line('- variacao-negativa: Ajusta estoque negativo da variação transferindo o saldo de outra variacao do mesmo produto, do mesmo local de estoque!');
        $this->line('- emite-transferencias: Gera notas de transferencia de uma filial para outra a fim de corrigir o estoque negativo!');
    }
    
    public function emiteTransferencias()
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
    
    public function variacaoNegativa()
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
                
        // aguarda meio segundo para rodar recalculo dos custos medios
        sleep(3);
    }
    
}
