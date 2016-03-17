@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li><a href="<?php echo url('grupo-produto');?>"><span class="glyphicon glyphicon-list-alt"></span> Listagem</a></li>             
            <li><a href="<?php echo url('grupo-produto/create');?>"><span class="glyphicon glyphicon-plus"></span> Novo</a></li>             
            <li><a href="<?php echo url("grupo-produto/$model->codgrupoproduto");?>"><span class="glyphicon glyphicon-eye-open"></span> Detalhes</a></li> 
            <li>
                {!! Form::open(['method' => 'DELETE', 'id'=>'deleteId', 'route' => ['marca.destroy', $model->codgrupoproduto]]) !!}
                <span class="glyphicon glyphicon-trash"></span>
                {!! Form::submit('Excluir') !!}
                {!! Form::close() !!}
            </li> 
        </ul>
    </div>
</nav>
<h1 class="header">Alterar Grupo Produto: {{$model->grupoproduto}}</h1>
<hr>
<br>
{!! Form::model($model, ['method' => 'PATCH', 'class' => 'form-horizontal', 'id' => 'form-grupo-produto', 'action' => ['GrupoProdutoController@update', $model->codgrupoproduto] ]) !!}
    @include('errors.form_error')
    @include('grupo-produto.form', ['submitTextButton' => 'Salvar'])
{!! Form::close() !!}
@stop