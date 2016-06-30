@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li><a href="{{ url('produto') }}"><span class="glyphicon glyphicon-list-alt"></span> Listagem</a></li>             
            <li><a href="{{ url('produto/create') }}"><span class="glyphicon glyphicon-plus"></span> Novo</a></li>             
            <li><a href="{{ url("produto/$model->codproduto") }}"><span class="glyphicon glyphicon-eye-open"></span> Detalhes</a></li>  
        </ul>
    </div>
</nav>
<h1 class="header">
{!! 
    titulo(
        $model->codproduto,
        [
            ['url' => "produto/$model->codproduto", 'descricao' => $model->produto],
            ['url' => null, 'descricao' => 'Alterar'],
        ],
        $model->inativo
    ) 
!!}     
</h1>
<hr>
<br>
<?php
$model->codgrupoproduto     = $model->SubGrupoProduto->GrupoProduto->codgrupoproduto;;
$model->codfamiliaproduto   = $model->SubGrupoProduto->GrupoProduto->FamiliaProduto->codfamiliaproduto;;
$model->codsecaoproduto     = $model->SubGrupoProduto->GrupoProduto->FamiliaProduto->SecaoProduto->codsecaoproduto;
?>
{!! Form::model($model, ['method' => 'PATCH', 'class' => 'form-horizontal', 'id' => 'form-produto', 'action' => ['ProdutoController@update', $model->codproduto] ]) !!}
    @include('errors.form_error')
    @include('produto.form', ['submitTextButton' => 'Salvar'])
{!! Form::close() !!}
@stop