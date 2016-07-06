@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li><a href="<?php echo url("estoque-movimento/$model->codestoquemovimento");?>"><span class="glyphicon glyphicon-eye-open"></span> Detalhes</a></li>             
        </ul>
    </div>
</nav>
<h1 class="header">
    <a href='{{ url("grupo-produto/{$model->EstoqueMes->EstoqueSaldo->EstoqueLocalProdutoVariacao->Produto->SubGrupoProduto->codgrupoproduto}") }}'>
        {{$model->EstoqueMes->EstoqueSaldo->EstoqueLocalProdutoVariacao->Produto->SubGrupoProduto->GrupoProduto->grupoproduto}}
    </a> ›
    <a href='{{ url("sub-grupo-produto/{$model->EstoqueMes->EstoqueSaldo->EstoqueLocalProdutoVariacao->Produto->codsubgrupoproduto}") }}'>
        {{$model->EstoqueMes->EstoqueSaldo->EstoqueLocalProdutoVariacao->Produto->SubGrupoProduto->subgrupoproduto}}
    </a> ›
    <a href='{{ url("produto/{$model->EstoqueMes->EstoqueSaldo->EstoqueLocalProdutoVariacao->codproduto}") }}'>
        {{ $model->EstoqueMes->EstoqueSaldo->EstoqueLocalProdutoVariacao->Produto->produto }}     
    </a>    
</h1>
<hr>
<br>
{!! Form::model($model, ['method' => 'PATCH', 'class' => 'form-horizontal', 'id' => 'estoqueMovimento', 'action' => ['EstoqueMovimentoController@update', $model->codestoquemovimento]]) !!}
    @include('errors.form_error')
    @include('estoque-movimento.form', ['submitTextButton' => 'Salvar'])
{!! Form::close() !!}
@stop