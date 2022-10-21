<?php

namespace MGLara\Library\Magazord;

use \Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

// use MGLara\Library\Magazord\MagazordBase;
// use MGLara\Models\Marca;
// use MGLara\Models\SecaoProduto;
// use MGLara\Models\FamiliaProduto;
// use MGLara\Models\GrupoProduto;
use MGLara\Models\MagazordProduto;
use MGLara\Models\Produto;

class Magazord {

    public static function sincronizaProduto(Produto $produto)
    {
        foreach ($produto->MagazordProdutoS as $mp) {
            static::sincronizaMagazordProduto($mp);
        }
    }

    public static function sincronizaMagazordProduto(MagazordProduto $mp)
    {
        static::sincronizaPrecoVarejo($mp);
        static::sincronizaPrecoAtacado($mp);
        static::sincronizaSaldo($mp);
    }

    public static function sincronizaPrecoVarejo(MagazordProduto $mp)
    {
        $api = new MagazordApi();
        $precoVenda = floatval($mp->Produto->preco);
        if (!empty($mp->codprodutoembalagem)) {
            if (!empty($mp->ProdutoEmbalagem->preco)) {
                $precoVenda = floatval($mp->ProdutoEmbalagem->preco);
            } else {
                $precoVenda *= floatval($mp->ProdutoEmbalagem->quantidade);
            }
        }
        $ret = $api->postPreco($mp->sku, intVal(env('MAGAZORD_TABELAPRECO_VAREJO')), $precoVenda);
        if ($ret) {
            $mp->update([
                'precovarejo' => $precoVenda,
                'precovarejoatualizado' => Carbon::now(),
            ]);
        }
        return $ret;
    }

    public static function sincronizaPrecoAtacado(MagazordProduto $mp)
    {
        $api = new MagazordApi();
        $percentual = 1 - (env('MAGAZORD_DESCONTO_ATACADO') / 100);
        $precoVenda = round(floatval($mp->precovarejo) * $percentual, 2);
        $ret = $api->postPreco($mp->sku, intVal(env('MAGAZORD_TABELAPRECO_ATACADO')), $precoVenda);
        if ($ret) {
            $mp->update([
                'precoatacado' => $precoVenda,
                'precoatacadoatualizado' => Carbon::now(),
            ]);
        }
        return $ret;
    }

    public static function sincronizaSaldo(MagazordProduto $mp)
    {
        $api = new MagazordApi();
        $sql = '
            select es.saldoquantidade
            from tblestoquelocalprodutovariacao elpv
            inner join tblestoquesaldo es on (es.codestoquelocalprodutovariacao = elpv.codestoquelocalprodutovariacao)
            where elpv.codprodutovariacao = :codprodutovariacao
            and elpv.codestoquelocal = :codestoquelocal
            and es.fiscal = false
        ';
        $data = DB::select($sql, [
            'codprodutovariacao' => $mp->codprodutovariacao,
            'codestoquelocal' => env('MAGAZORD_CODESTOQUELOCAL')
        ]);
        $quantidade = 0;
        if (isset($data[0])) {
            $quantidade = floor($data[0]->saldoquantidade)??0;
        }
        if (!empty($mp->codprodutoembalagem)) {
            $quantidade = floor($quantidade / $mp->ProdutoEmbalagem->quantidade);
        }
        $ret = $api->postEstoque($mp->sku, floor($quantidade));
        if ($ret) {
            $mp->update([
                'saldoquantidade' => $quantidade,
                'saldoquantidadeatualizado' => Carbon::now(),
            ]);
        }
        return $ret;
    }

    public static function sincronizaPrecos ($limit = 50)
    {
        Log::info ('Buscando precos para sinconizar com Magazord!');

        // Busca registros para atualizar
        $sql = '
            with atualizar as (
            	select
            		coalesce(pe.preco, p.preco * coalesce(pe.quantidade, 1)) as preco,
            		coalesce(mp.precovarejo, 0) as precovarejo,
            		mp.codmagazordproduto,
                    mp.codprodutoembalagem,
            		pv.codproduto,
                    mp.sku,
                    mp.codmagazordproduto
            	from tblmagazordproduto mp
            	inner join tblprodutovariacao pv on (pv.codprodutovariacao = mp.codprodutovariacao)
            	inner join tblproduto p on (p.codproduto = pv.codproduto)
            	left join tblprodutoembalagem pe on (pe.codprodutoembalagem = mp.codprodutoembalagem)
            )
            select *
            from atualizar
            where preco != precovarejo
            limit :limit
        ';
        $regs = DB::select($sql, ['limit' => $limit]);

        // Loga quantidade de registros encontrados
        $count = count($regs);
        Log::info ("{$count} precos encontrados para sinconizar com Magazord!");
        if ($count == 0) {
            return 0;
        }

        // inicializa transacao que so sera commitada caso api do magazord valide todas alteracoes
        DB::beginTransaction();
        $data = [];
        $count = 0;

        // percorre os registros
        foreach ($regs as $reg) {

            // monta array com o preco de varejo
            $data[] = (object) [
                'produto' => $reg->sku,
                'tabelaPreco' => intval(env('MAGAZORD_TABELAPRECO_VAREJO')),
                'precoVenda' => floatval($reg->preco)
            ];

            // monta array com o preco de atacado
            $percentual = 1 - (env('MAGAZORD_DESCONTO_ATACADO') / 100);
            $precoAtacado = round(floatval($reg->preco) * $percentual, 2);
            $data[] = (object) [
                'produto' => $reg->sku,
                'tabelaPreco' => intval(env('MAGAZORD_TABELAPRECO_ATACADO')),
                'precoVenda' => $precoAtacado
            ];

            // marca os precos como atualizados na tabela
            $sql = '
                update tblmagazordproduto
                set precovarejo = :precovarejo,
                    precovarejoatualizado = :agora,
                    precoatacado = :precoatacado,
                    precoatacadoatualizado = :agora
                where codmagazordproduto = :codmagazordproduto
            ';
            $count += DB::update($sql, [
                'precovarejo' => $reg->preco,
                'precoatacado' => $precoAtacado,
                'agora' => Carbon::now(),
                'codmagazordproduto' => $reg->codmagazordproduto
            ]);
        }

        // submete o array de precos alterados pra api
        $api = new MagazordApi();
        $ret = $api->postPrecos($data);

        // salva alteracoes no banco caso a api confirme a alteracao dos precos
        if ($ret) {
            Log::info ("{$count} precos sinconizados com Magazord!");
            DB::commit();
        } else {
            Log::info ("Falha ao sincronizar precos com Magazord!");
        }

        // retonrna a quantidade de produtos com preco alterados
        return $count;
    }

    public static function sincronizaSaldos ($limit = 1000)
    {

        Log::info ('Buscando estoque para sinconizar com Magazord!');

        // Busca registros para atualizar
        $sql = '
            with regs as (
            	select
            		mp.codmagazordproduto,
            		mp.codproduto,
            		mp.codprodutovariacao,
            		mp.codprodutoembalagem,
            		mp.codprodutovariacao,
            		pe.quantidade as quantidadeembalagem,
            		mp.sku,
            		floor(coalesce(es.saldoquantidade, 0) / coalesce(pe.quantidade, 1)) as saldoquantidademg,
            		coalesce(mp.saldoquantidade, 0) as saldoquantidademagazord
            	from tblmagazordproduto mp
            	left join tblestoquelocalprodutovariacao elpv on (elpv.codestoquelocal = :codestoquelocal and elpv.codprodutovariacao = mp.codprodutovariacao)
            	left join tblestoquesaldo es on (es.codestoquelocalprodutovariacao = elpv.codestoquelocalprodutovariacao and es.fiscal = false)
            	left join tblprodutoembalagem pe on (pe.codprodutoembalagem = mp.codprodutoembalagem)
            	order by mp.codproduto, mp.codprodutovariacao, pe.quantidade nulls first
            )
            select *
            from regs
            where saldoquantidademg != saldoquantidademagazord
            limit :limit
        ';
        $regs = DB::select($sql, [
            'codestoquelocal' => env('MAGAZORD_CODESTOQUELOCAL'),
            'limit' => $limit
        ]);

        // Loga quantidade de registros encontrados
        $count = count($regs);
        Log::info ("{$count} produtos encontrados para sinconizar com Magazord!");
        if ($count == 0) {
            return 0;
        }

        // percorre os registros
        $api = new MagazordApi();
        $sql = '
            update tblmagazordproduto
            set saldoquantidade = :saldoquantidade,
                saldoquantidadeatualizado = :agora
            where codmagazordproduto = :codmagazordproduto
        ';
        $data = [];
        $count = 0;
        foreach ($regs as $reg) {
            $ret = $api->postEstoque($reg->sku, intval($reg->saldoquantidademg));
            if ($ret) {
                Log::info ("Produto {$reg->codproduto} SKU {$reg->sku} saldo de {$reg->saldoquantidademg} sinconizado com Magazord!");
                $count += DB::update($sql, [
                    'saldoquantidade' => intval($reg->saldoquantidademg),
                    'agora' => Carbon::now(),
                    'codmagazordproduto' => $reg->codmagazordproduto
                ]);
            } else {
                Log::error ("Produto {$reg->codproduto} SKU {$reg->sku} Falha ao atualizar saldo de {$reg->saldoquantidademg} com Magazord!");
            }
        }
        Log::info ("{$count} precos sinconizados com Magazord!");
        return $count;
    }

}
