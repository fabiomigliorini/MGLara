<?php

namespace MGLara\Library\Mercos;

use \Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

// use MGLara\Library\Magazord\MagazordBase;
// use MGLara\Models\Marca;
// use MGLara\Models\SecaoProduto;
// use MGLara\Models\FamiliaProduto;
// use MGLara\Models\GrupoProduto;
use MGLara\Models\MercosProduto as MercosProdutoModel;
use MGLara\Models\MercosProdutoImagem;
use MGLara\Models\Produto;
use MGLara\Models\ProdutoVariacao;
use MGLara\Models\ProdutoEmbalagem;
use MGLara\Models\ProdutoImagem;
use MGLara\Models\EstoqueLocalProdutoVariacao;

class MercosProduto {

    public static function exportaProduto ($codproduto, $codprodutovariacao, $codprodutoembalagem)
    {

        $qry = MercosProdutoModel::where([
            'codproduto' => $codproduto,
            'codprodutovariacao' => $codprodutovariacao,
        ]);
        if (!empty($codprodutoembalagem)) {
            $qry->where('codprodutoembalagem', $codprodutoembalagem);
        } else {
            $qry->whereNull('codprodutoembalagem');
        }
        $mp = $qry->first();

        $api = new MercosApi();

        $p = Produto::findOrFail($codproduto);
        $nome = $p->produto;
        $preco_tabela = (double)$p->preco;
        $codigo = formataCodigo($codproduto, 6);
        $codigo .= '-' . formataCodigo($codprodutovariacao, 8);
        $unidade = $p->UnidadeMedida->sigla;
        $peso_bruto = (double) $p->peso;
        $largura = (double) $p->largura;
        $altura = (double) $p->altura;
        $comprimento = (double) $p->profundidade;

        $sql = '
            select es.saldoquantidade
            from tblestoquelocalprodutovariacao elpv
            inner join tblestoquesaldo es on (es.codestoquelocalprodutovariacao = elpv.codestoquelocalprodutovariacao)
            where elpv.codprodutovariacao = :codprodutovariacao
            and elpv.codestoquelocal = :codestoquelocal
            and es.fiscal = false
        ';
        $data = DB::select($sql, [
            'codprodutovariacao' => $codprodutovariacao,
            'codestoquelocal' => env('MERCOS_CODESTOQUELOCAL')
        ]);
        $saldo_estoque = 0;
        if (isset($data[0])) {
            $saldo_estoque = floor($data[0]->saldoquantidade)??0;
        }

        $pv = ProdutoVariacao::findOrFail($codprodutovariacao);
        if (!empty($pv->variacao)) {
            $nome .= ' ' . $pv->variacao;
        }

        if (!empty($codprodutoembalagem)) {
            $pe = ProdutoEmbalagem::findOrFail($codprodutoembalagem);
            // dd($pe);
            $nome .= ' C/' . formataNumero($pe->quantidade, 0);
            if (!empty($pe->preco)) {
                $preco_tabela = $pe->preco;
            } else {
                $preco_tabela *= $pe->quantidade;
            }
            $codigo .= '-' . formataCodigo($codprodutoembalagem, 8);
            $unidade = $pe->UnidadeMedida->sigla;
            $saldo_estoque = floor($saldo_estoque / $pe->quantidade);

            $peso_bruto = (double) $pe->peso;
            $largura = (double) $pe->largura;
            $altura = (double) $pe->altura;
            $comprimento = (double) $pe->profundidade;

        }

        $preco_minimo = $preco_tabela;
        $comissao = null;
        $ipi = null;
        $tipo_ipi = 'P';
        $st = null;
        $moeda = 0;
        $observacoes = $p->descricaosite;
        $observacoes .= "\n\nCódigo de Barras: \n";
        $observacoes .= $codigo . " \n";
        foreach ($pv->ProdutoBarraS()->orderBy('codprodutoembalagem', 'ASC')->get() as $pb) {
            $observacoes .= $pb->barras . ' ';
            if (empty($pb->codprodutoembalagem)) {
                $observacoes .= $p->UnidadeMedida->sigla;
            } else {
                $observacoes .= $pb->ProdutoEmbalagem->UnidadeMedida->sigla .
                    ' C/' . formataNumero($pb->ProdutoEmbalagem->quantidade, 0);
            }
            $observacoes .= "\n";
        }
        $grade_cores = null;
        $grade_tamanhos = null;
        $excluido = false;
        $ativo = true;
        $categoria_id = null;
        $codigo_ncm = $p->Ncm->ncm;
        $multiplo = null;
        $peso_dimensoes_unitario = true;
        $exibir_no_b2b = true;

        $alt = Carbon::now();

        if (!empty($mp)) {
            $ret = $api->putProdutos(
                $mp->produtoid,
                $nome,
                $preco_tabela,
                $preco_minimo,
                $codigo,
                $comissao,
                $ipi,
                $tipo_ipi,
                $st,
                $moeda,
                $unidade,
                $saldo_estoque,
                $observacoes,
                $grade_cores,
                $grade_tamanhos,
                $excluido,
                $ativo,
                $categoria_id,
                $codigo_ncm,
                $multiplo,
                $peso_bruto,
                $largura,
                $altura,
                $comprimento,
                $peso_dimensoes_unitario,
                $exibir_no_b2b
            );
        } else {
            $ret = $api->postProdutos(
                $nome,
                $preco_tabela,
                $preco_minimo,
                $codigo,
                $comissao,
                $ipi,
                $tipo_ipi,
                $st,
                $moeda,
                $unidade,
                $saldo_estoque,
                $observacoes,
                $grade_cores,
                $grade_tamanhos,
                $excluido,
                $ativo,
                $categoria_id,
                $codigo_ncm,
                $multiplo,
                $peso_bruto,
                $largura,
                $altura,
                $comprimento,
                $peso_dimensoes_unitario,
                $exibir_no_b2b
            );
            $mp = MercosProdutoModel::firstOrNew([
                'produtoid' => $api->headers['meuspedidosid']
            ]);
        }

        // Salva dados da ultima modificacao
        $mp->codproduto = $codproduto;
        $mp->codprodutovariacao = $codprodutovariacao;
        $mp->codprodutoembalagem = $codprodutoembalagem;
        $mp->preco = $preco_tabela;
        $mp->precoatualizado = $alt;
        $mp->saldoquantidade = $saldo_estoque;
        $mp->saldoquantidadeatualizado = $alt;
        $ret = $mp->save();

        // exporta imagem principal
        if (!empty($pv->codprodutoimagem)) {
            static::exportaImagem($mp->produtoid, $pv->codprodutoimagem, $mp->codmercosproduto, 1);
        }

        // exporta imagens adicionais
        foreach ($pv->ProdutoImagemProdutoVariacaoS as $pipv) {
            static::exportaImagem($mp->produtoid, $pipv->codprodutoimagem, $mp->codmercosproduto, 2);
        }

        // retorna
        return [
            'codproduto' => $codproduto,
            'codprodutovariacao' => $codprodutovariacao,
            'codprodutoembalagem' => $codprodutoembalagem,
            'codprodutoembalagem' => $codprodutoembalagem,
            'produtoid' => $mp->produtoid,
            'retorno' => $ret,
        ];
    }

    public static function exportaImagem($produtoid, $codprodutoimagem, $codmercosproduto, $ordem = 2)
    {
        $pi = ProdutoImagem::findOrFail($codprodutoimagem);
        $qtd = MercosProdutoImagem::where([
            'codmercosproduto' => $codmercosproduto,
            'codimagem' => $pi->codimagem,
        ])->count();
        if ($qtd > 0) {
            return;
        }
        sleep(5);
        $api = new MercosApi();
        $arquivo = './public/imagens/' . $pi->Imagem->arquivo;
        $data = file_get_contents($arquivo);
        $base64 = base64_encode($data);

        $ret = $api->postImagensProduto(
            $produtoid,
            $ordem,
            $base64
        );
        if ($ret) {
            $mpi = MercosProdutoImagem::firstOrNew([
                'codmercosproduto' => $codmercosproduto,
                'codimagem' => $pi->codimagem,
            ]);
            $mpi->save();
        }
        return $ret;
    }

}
