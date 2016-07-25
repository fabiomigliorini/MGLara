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
        $parametros = $request->all();
        $descricao = [];
        
        // Decide tipo do Agrupamento
        
        // Por Variacao
        if (!empty($request->codprodutovariacao)) {
            
            $agrupamento = 'variacao';
            $codigo = 'codprodutovariacao';
            
            $model = \MGLara\Models\ProdutoVariacao::findOrFail($request->codprodutovariacao);
            
            $parametros['codprodutovariacao'] = $model->codprodutovariacao;
            $descricao['codprodutovariacao'] = empty($model->variacao)?'{ Sem Variacao }':$model->variacao;
            
            $parametros['codproduto'] = $model->codproduto;
            $descricao['codproduto'] = $model->Produto->produto;
            
            $parametros['codmarca'] = $model->Produto->codmarca;
            $descricao['codmarca'] = $model->Produto->Marca->marca;
            
            $parametros['codsubgrupoproduto'] = $model->Produto->codsubgrupoproduto;
            $descricao['codsubgrupoproduto'] = $model->Produto->SubGrupoProduto->subgrupoproduto;
            
            $parametros['codgrupoproduto'] = $model->Produto->SubGrupoProduto->codgrupoproduto;
            $descricao['codgrupoproduto'] = $model->Produto->SubGrupoProduto->GrupoProduto->grupoproduto;
            
            $parametros['codfamiliaproduto'] = $model->Produto->SubGrupoProduto->GrupoProduto->codfamiliaproduto;
            $descricao['codfamiliaproduto'] = $model->Produto->SubGrupoProduto->GrupoProduto->FamiliaProduto->familiaproduto;
            
            $parametros['codsecaoproduto'] = $model->Produto->SubGrupoProduto->GrupoProduto->FamiliaProduto->codsecaoproduto;
            $descricao['codsecaoproduto'] = $model->Produto->SubGrupoProduto->GrupoProduto->FamiliaProduto->SecaoProduto->secaoproduto;
            
        // Por Variacao
        } else if (!empty($request->codproduto)) {
            
            $agrupamento = 'variacao';
            $codigo = 'codprodutovariacao';
            
            $model = \MGLara\Models\Produto::findOrFail($request->codproduto);
            
            $parametros['codproduto'] = $model->codproduto;
            $descricao['codproduto'] = $model->produto;
            
            $parametros['codmarca'] = $model->codmarca;
            $descricao['codmarca'] = $model->Marca->marca;
            
            $parametros['codsubgrupoproduto'] = $model->codsubgrupoproduto;
            $descricao['codsubgrupoproduto'] = $model->SubGrupoProduto->subgrupoproduto;
            
            $parametros['codgrupoproduto'] = $model->SubGrupoProduto->codgrupoproduto;
            $descricao['codgrupoproduto'] = $model->SubGrupoProduto->GrupoProduto->grupoproduto;
            
            $parametros['codfamiliaproduto'] = $model->SubGrupoProduto->GrupoProduto->codfamiliaproduto;
            $descricao['codfamiliaproduto'] = $model->SubGrupoProduto->GrupoProduto->FamiliaProduto->familiaproduto;
            
            $parametros['codsecaoproduto'] = $model->SubGrupoProduto->GrupoProduto->FamiliaProduto->codsecaoproduto;
            $descricao['codsecaoproduto'] = $model->SubGrupoProduto->GrupoProduto->FamiliaProduto->SecaoProduto->secaoproduto;

        // Por Produto
        } else if ((!empty($request->codmarca)) && (!empty($request->codsubgrupoproduto))) {

            $agrupamento = 'produto';
            $codigo = 'codproduto';
            
            $model = \MGLara\Models\Marca::findOrFail($request->codmarca);
            
            $parametros['codmarca'] = $model->codmarca;
            $descricao['codmarca'] = $model->marca;
            
            $model = \MGLara\Models\SubGrupoProduto::findOrFail($request->codsubgrupoproduto);
            
            $parametros['codsubgrupoproduto'] = $model->codsubgrupoproduto;
            $descricao['codsubgrupoproduto'] = $model->subgrupoproduto;
            
            $parametros['codgrupoproduto'] = $model->codgrupoproduto;
            $descricao['codgrupoproduto'] = $model->GrupoProduto->grupoproduto;
            
            $parametros['codfamiliaproduto'] = $model->GrupoProduto->codfamiliaproduto;
            $descricao['codfamiliaproduto'] = $model->GrupoProduto->FamiliaProduto->familiaproduto;
            
            $parametros['codsecaoproduto'] = $model->GrupoProduto->FamiliaProduto->codsecaoproduto;
            $descricao['codsecaoproduto'] = $model->GrupoProduto->FamiliaProduto->SecaoProduto->secaoproduto;
            
        // Por Marca
        } else if (!empty($request->codsubgrupoproduto)) {

            $agrupamento = 'marca';
            $codigo = 'codmarca';
            
            $model = \MGLara\Models\SubGrupoProduto::findOrFail($request->codsubgrupoproduto);
            
            $parametros['codsubgrupoproduto'] = $model->codsubgrupoproduto;
            $descricao['codsubgrupoproduto'] = $model->subgrupoproduto;
            
            $parametros['codgrupoproduto'] = $model->codgrupoproduto;
            $descricao['codgrupoproduto'] = $model->GrupoProduto->grupoproduto;
            
            $parametros['codfamiliaproduto'] = $model->GrupoProduto->codfamiliaproduto;
            $descricao['codfamiliaproduto'] = $model->GrupoProduto->FamiliaProduto->familiaproduto;
            
            $parametros['codsecaoproduto'] = $model->GrupoProduto->FamiliaProduto->codsecaoproduto;
            $descricao['codsecaoproduto'] = $model->GrupoProduto->FamiliaProduto->SecaoProduto->secaoproduto;
            
        // Por Sub Grupo
        } else if (!empty($request->codgrupoproduto)) {
            
            $agrupamento = 'subgrupoproduto';
            $codigo = 'codsubgrupoproduto';
            
            $model = \MGLara\Models\GrupoProduto::findOrFail($request->codgrupoproduto);
            
            $parametros['codgrupoproduto'] = $model->codgrupoproduto;
            $descricao['codgrupoproduto'] = $model->grupoproduto;
            
            $parametros['codfamiliaproduto'] = $model->codfamiliaproduto;
            $descricao['codfamiliaproduto'] = $model->FamiliaProduto->familiaproduto;
            
            $parametros['codsecaoproduto'] = $model->FamiliaProduto->codsecaoproduto;
            $descricao['codsecaoproduto'] = $model->FamiliaProduto->SecaoProduto->secaoproduto;
            
        // Por Grupo
        } else if (!empty($request->codfamiliaproduto)) {
            
            $agrupamento = 'grupoproduto';
            $codigo = 'codgrupoproduto';
            
            $model = \MGLara\Models\FamiliaProduto::findOrFail($request->codfamiliaproduto);
            
            $parametros['codfamiliaproduto'] = $model->codfamiliaproduto;
            $descricao['codfamiliaproduto'] = $model->familiaproduto;
            
            $parametros['codsecaoproduto'] = $model->codsecaoproduto;
            $descricao['codsecaoproduto'] = $model->SecaoProduto->secaoproduto;
            
        // Por Familia
        } else if (!empty($request->codsecaoproduto)) {
            
            $agrupamento = 'familiaproduto';
            $codigo = 'codfamiliaproduto';
            
            $model = \MGLara\Models\SecaoProduto::findOrFail($request->codsecaoproduto);
            
            $parametros['codsecaoproduto'] = $model->codsecaoproduto;
            $descricao['codsecaoproduto'] = $model->secaoproduto;
            
        // Por Secao
        } else {
            $agrupamento = 'secaoproduto';
            $codigo = 'codsecaoproduto';
        }
        
        // Pega Descricao do Local, caso filtrado
        if (!empty($request->codestoquelocal)) {
            $model = \MGLara\Models\EstoqueLocal::findOrFail($request->codestoquelocal);
            $parametros['codestoquelocal'] = $model->codestoquelocal;
            $descricao['codestoquelocal'] = $model->estoquelocal;
        }
        
        // Monta Array do Título
        $titulo = [];
        $arr = $parametros;
        if (!empty($descricao['codprodutovariacao'])) {
            $titulo = [urlArrGet($arr, 'estoque-saldo') => $descricao['codprodutovariacao']] + $titulo;
        }
        if (!empty($descricao['codproduto'])) {
            unset($arr['codprodutovariacao']);
            $titulo = [urlArrGet($arr, 'estoque-saldo') => $descricao['codproduto']] + $titulo;
        }
        if (!empty($descricao['codmarca'])) {
            unset($arr['codproduto']);
            $titulo = [urlArrGet($arr, 'estoque-saldo') => $descricao['codmarca']] + $titulo;
        }
        if (!empty($descricao['codsubgrupoproduto'])) {
            unset($arr['codmarca']);
            $titulo = [urlArrGet($arr, 'estoque-saldo') => $descricao['codsubgrupoproduto']] + $titulo;
        }
        if (!empty($descricao['codgrupoproduto'])) {
            unset($arr['codsubgrupoproduto']);
            $titulo = [urlArrGet($arr, 'estoque-saldo') => $descricao['codgrupoproduto']] + $titulo;
        }
        if (!empty($descricao['codfamiliaproduto'])) {
            unset($arr['codgrupoproduto']);
            $titulo = [urlArrGet($arr, 'estoque-saldo') => $descricao['codfamiliaproduto']] + $titulo;
        }
        if (!empty($descricao['codsecaoproduto'])) {
            unset($arr['codfamiliaproduto']);
            $titulo = [urlArrGet($arr, 'estoque-saldo') => $descricao['codsecaoproduto']] + $titulo;
        }
        if (!empty($descricao['codestoquelocal'])) {
            unset($arr['codsecaoproduto']);
            $titulo = [urlArrGet($arr, 'estoque-saldo') => $descricao['codestoquelocal']] + $titulo;
        }
        if (!empty($titulo)) {
            $titulo = [url('estoque-saldo') => 'Saldos de Estoque'] + $titulo;
        } else {
            $titulo = ['Saldos de Estoque'];
        }
        
        $itens = EstoqueSaldo::totais($agrupamento, $parametros);
        return view('estoque-saldo.index', compact('itens', 'parametros', 'codigo', 'titulo'));
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
