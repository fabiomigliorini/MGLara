<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;

use MGLara\Http\Controllers\Controller;
use MGLara\Models\EstoqueSaldo;
use MGLara\Models\EstoqueMes;

class EstoqueSaldoController extends Controller
{
    
    public function index(Request $request)
    {
        //$filtro = $request->all();
        $agrupamento_atual = empty($request->agrupamento)?'secaoproduto':$request->agrupamento;
        $valor = empty($request->valor)?'custo':$request->valor;
        
        $arr_valor = [
            'custo'=>'Custo do Produto', 
            'venda'=>'Preço de Venda',
        ];
        
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
        
        $itens = EstoqueSaldo::totais($agrupamento_atual, $valor, $filtro);

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
                    'arr_valor',
                    'agrupamento_atual',
                    'agrupamento_proximo',
                    'valor',
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
    
    public function relatorioAnaliseFiltro(Request $request)
    {

        $filtro = self::filtroEstatico($request, 'estoque-saldo.relatorio-analise', ['ativo' => 1]);
        
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
        
        $arr_ativo = [
            '1' => 'Ativos',
            '2' => 'Inativos',
            '9' => 'Todos',
        ];
        
        return view('estoque-saldo.relatorio-analise-filtro', compact('arr_ativo', 'arr_valor', 'arr_saldos', 'arr_minimo', 'arr_maximo', 'filtro'));
    }
    
    public function relatorioAnalise(Request $request)
    {
        $filtro = self::filtroEstatico($request, 'estoque-saldo.relatorio-analise');
        
        $dados = EstoqueSaldo::relatorioAnalise($filtro);
        
        if (!empty($filtro['debug'])) {
            return $dados;
        }
        
        return view('estoque-saldo.relatorio-analise', compact('dados'));
    }

}
