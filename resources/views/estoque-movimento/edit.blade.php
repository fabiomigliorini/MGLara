@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li><a href="{{ url("estoque-movimento/$model->codestoquemovimento") }}"><span class="glyphicon glyphicon-eye-open"></span> Detalhes</a></li>
        </ul>
    </div>
</nav>
<ol class="breadcrumb header">
    {!!
        titulo(
                $model->codestoquemovimento, 
                [
                    url("produto/{$model->EstoqueMes->EstoqueSaldo->EstoqueLocalProdutoVariacao->ProdutoVariacao->codproduto}")=>$model->EstoqueMes->EstoqueSaldo->EstoqueLocalProdutoVariacao->ProdutoVariacao->Produto->produto,
                    (empty($model->EstoqueMes->EstoqueSaldo->EstoqueLocalProdutoVariacao->ProdutoVariacao->variacao))?"<i class='text-muted'>{ Sem Variação }</i>":$model->EstoqueMes->EstoqueSaldo->EstoqueLocalProdutoVariacao->ProdutoVariacao->variacao,
                    url("estoque-local/{$model->EstoqueMes->EstoqueSaldo->EstoqueLocalProdutoVariacao->codestoquelocal}")=>$model->EstoqueMes->EstoqueSaldo->EstoqueLocalProdutoVariacao->EstoqueLocal->estoquelocal,
                    ($model->EstoqueMes->EstoqueSaldo->fiscal)?"Fiscal":"Fisico",
                    url("estoque-mes/{$model->codestoquemes}")=>formataData($model->EstoqueMes->mes, 'EC'),
                    'Alterar Movimento de Estoque',
                ],
                $model->EstoqueMes->EstoqueSaldo->EstoqueLocalProdutoVariacao->ProdutoVariacao->Produto->inativo
        )
    !!}
</ol>
<hr>
<br>
{!! Form::model($model, ['method' => 'PATCH', 'class' => 'form-horizontal', 'id' => 'form-estoque-movimento', 'action' => ['EstoqueMovimentoController@update', $model->codestoquemovimento]]) !!}
    @include('errors.form_error')
    @include('estoque-movimento.form')
{!! Form::close() !!}
@stop