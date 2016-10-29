@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li><a href="<?php echo url("estoque-mes/$model->codestoquemes");?>"><span class="glyphicon glyphicon-list-alt"></span> Listagem</a></li>
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
{!! Form::model($model, ['method' => 'POST', 'class' => 'form-horizontal', 'id' => 'form-estoque-movimento', 'route' => ['estoque-movimento.store', 'codestoquemes' => $model->codestoquemes]]) !!}
    @include('errors.form_error')
    @include('estoque-movimento.form', ['submitTextButton' => 'Salvar'])
 {!! Form::close() !!}   
@stop