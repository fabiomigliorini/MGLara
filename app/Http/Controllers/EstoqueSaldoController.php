<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;

use MGLara\Http\Requests;
use MGLara\Http\Controllers\Controller;
use MGLara\Models\EstoqueSaldo;
use MGLara\Models\EstoqueMes;

class EstoqueSaldoController extends Controller
{

    public function index(Request $request)
    {
        //$filtro = $request->all();
        $agrupamento_atual = empty($request->agrupamento)?'secaoproduto':$request->agrupamento;
        
        $arr_saldos = [
            '' => '', 
            -1=>'Negativo', 
            1=>'Positivo',
        ];
        
        $arr_minimo = [
            '' => '', 
            -1=>'Abaixo Mínimo', 
            1=>'Acima Mínimo'
        ];
        
        $arr_maximo = [
            '' => '', 
            -1=>'Abaixo Máximo', 
            1=>'Acima Máximo'
        ];
        
        $arr_agrupamentos = [
            '' => '',
            'secaoproduto' => 'Seção',
            'familiaproduto' => 'Família',
            'grupoproduto' => 'Grupo',
            'subgrupoproduto' => 'Sub-Grupo',
            'marca' => 'Marca',
            'produto' => 'Produto',
            'variacao' => 'Variação',
        ];
        
        switch ($agrupamento_atual) {
            
            case 'secaoproduto':
                $codigo = 'codsecaoproduto';
                $agrupamento_proximo = 'familiaproduto';
                $url_detalhes = 'secao-produto/';
                break;
            
            case 'familiaproduto':
                $codigo = 'codfamiliaproduto';
                $agrupamento_proximo = 'grupoproduto';
                $url_detalhes = 'familia-produto/';
                break;
            
            case 'grupoproduto':
                $codigo = 'codgrupoproduto';
                $agrupamento_proximo = 'subgrupoproduto';
                $url_detalhes = 'grupo-produto/';
                break;
            
            case 'subgrupoproduto':
                $codigo = 'codsubgrupoproduto';
                $agrupamento_proximo = 'marca';
                $url_detalhes = 'sub-grupo-produto/';
                break;
            
            case 'marca':
                $codigo = 'codmarca';
                $agrupamento_proximo = 'produto';
                $url_detalhes = 'marca/';
                break;
            
            case 'produto':
                $codigo = 'codproduto';
                $agrupamento_proximo = 'variacao';
                $url_detalhes = 'produto/';
                break;
            
            case 'variacao':
                $codigo = 'codprodutovariacao';
                $agrupamento_proximo = 'variacao';
                $url_detalhes = 'produto-variacao/';
                break;
        }
        
        $filtro = $request->all();
        
        /*
        $titulo = [];
        $filtro = [];

        // Secao
        if (!empty($request->codsecaoproduto)) {
            $model = \MGLara\Models\SecaoProduto::findOrFail($request->codsecaoproduto);
            $filtro['codsecaoproduto'] = $model->codsecaoproduto;
            $titulo[url("secao-produto/{$model->codsecaoproduto}")] = $model->secaoproduto;
        }
        
        // Familia
        if (!empty($request->codfamiliaproduto)) {
            $model = \MGLara\Models\FamiliaProduto::findOrFail($request->codfamiliaproduto);
            $filtro['codfamiliaproduto'] = $model->codfamiliaproduto;
            $titulo[url("familia-produto/{$model->codfamiliaproduto}")] = $model->familiaproduto;
        }
        
        // Grupo
        if (!empty($request->codgrupoproduto)) {
            $model = \MGLara\Models\GrupoProduto::findOrFail($request->codgrupoproduto);
            $filtro['codgrupoproduto'] = $model->codgrupoproduto;
            $titulo[url("grupo-produto/{$model->codgrupoproduto}")] = $model->grupoproduto;
        }

        // Sub-Grupo
        if (!empty($request->codsubgrupoproduto)) {
            $model = \MGLara\Models\SubGrupoProduto::findOrFail($request->codsubgrupoproduto);
            $filtro['codsubgrupoproduto'] = $model->codsubgrupoproduto;
            $titulo[url("sub-grupo-produto/{$model->codsubgrupoproduto}")] = $model->subgrupoproduto;
        } 
        
        // Marca
        if (!empty($request->codmarca)) {
            $model = \MGLara\Models\Marca::findOrFail($request->codmarca);
            $filtro['codmarca'] = $model->codmarca;
            $titulo[url("marca/{$model->codmarca}")] = $model->marca;
        }
            
        // Produto
        if (!empty($request->codproduto)) {
            $model = \MGLara\Models\Produto::findOrFail($request->codproduto);
            $filtro['codproduto'] = $model->codproduto;
            $titulo[url("produto/{$model->codproduto}")] = $model->produto;
        }
        
        // Variacao
        if (!empty($request->codprodutovariacao)) {
            $model = \MGLara\Models\ProdutoVariacao::findOrFail($request->codprodutovariacao);
            $titulo[url("produto/{$model->codproduto}#{$model->codprodutovariacao}")] = empty($model->variacao)?'{ Sem Variação }':$model->variacao;
            $filtro['codprodutovariacao'] = $model->codprodutovariacao;
        }
        
        // Local
        if (!empty($request->codestoquelocal)) {
            $model = \MGLara\Models\EstoqueLocal::findOrFail($request->codestoquelocal);
            $filtro['codestoquelocal'] = $model->codestoquelocal;
            $titulo[url("estoque-local/{$model->codestoquelocal}")] = $model->estoquelocal;
        }
        
        // Saldo Negativo / Positivo
        if (!empty($request->saldo)) {
            $filtro['saldo'] = $request->saldo;
            $titulo[] = $arr_saldos[$request->saldo];
        }
        
        // Estoque Minimo
        if (!empty($request->minimo)) {
            $filtro['minimo'] = $request->minimo;
            $titulo[] = $arr_minimo[$request->minimo];
        }
        
        // Estoque Maximo
        if (!empty($request->maximo)) {
            $filtro['maximo'] = $request->maximo;
            $titulo[] = $arr_maximo[$request->maximo];
        }
         * 
         */
        
        $itens = EstoqueSaldo::totais($agrupamento_atual, $filtro);

        if (!empty($titulo)) {
            $titulo = [url('estoque-saldo') => 'Saldos de Estoque'] + $titulo;
        } else {
            $titulo = ['Saldos de Estoque'];
        }
        
        return view(
            'estoque-saldo.index', 
                compact(
                    'itens',
                    'arr_agrupamentos',
                    'arr_saldos',
                    'arr_minimo',
                    'arr_maximo',
                    'agrupamento_atual',
                    'agrupamento_proximo',
                    'url_detalhes',
                    'filtro',
                    'codigo'
                )
            );
    }
    
    /**
     * Redireciona para último EstoqueMes encontrado
     *
     * @param  bigint  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $ems = EstoqueMes::where('codestoquesaldo', $id)
               ->orderBy('mes', 'DESC')
               ->take(1)
               ->get();
        return redirect("estoque-mes/{$ems[0]->codestoquemes}");
    }
    
    public function zera($id)
    {
        $model = EstoqueSaldo::findOrFail($id);
        return json_encode($model->zera());
    }
    
    public function relatorio(Request $request)
    {
        return view('estoque-saldo.relatorio', []);
    }

}
