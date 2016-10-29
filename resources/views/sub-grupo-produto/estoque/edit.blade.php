@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li><a href="<?php echo url("grupo-produto/$model->codgrupoproduto");?>"><span class="glyphicon glyphicon-list-alt"></span> Listagem</a></li>             
            <li><a href="<?php echo url('sub-grupo-produto/create?codgrupoproduto='.$model->GrupoProduto->codgrupoproduto);?>" ><span class="glyphicon glyphicon-plus"></span> Novo</a></li>             
            <li><a href="<?php echo url("sub-grupo-produto/$model->codsubgrupoproduto");?>"><span class="glyphicon glyphicon-eye-open"></span> Detalhes</a></li> 
        </ul>
    </div>
</nav>
<ol class="breadcrumb header">Alterar Sub Grupo Produto: {{$model->subgrupoproduto}}</ol>
<hr>
<br>
{!! Form::model($model, ['method' => 'PATCH', 'class' => 'form-horizontal', 'id' => 'form-sub-grupo-produto', 'action' => ['SubGrupoProdutoController@update', $model->codsubgrupoproduto] ]) !!}
    @include('errors.form_error')
    @include('sub-grupo-produto.form', ['submitTextButton' => 'Salvar'])
{!! Form::close() !!}
@stop