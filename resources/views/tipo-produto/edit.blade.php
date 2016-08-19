@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li><a href="{{ url('tipo-produto') }}"><span class="glyphicon glyphicon-list-alt"></span> Listagem</a></li>             
            <li><a href="{{ url('tipo-produto/create') }}"><span class="glyphicon glyphicon-plus"></span> Novo</a></li>             
            <li><a href="{{ url("tipo-produto/$model->codtipoproduto") }}"><span class="glyphicon glyphicon-eye-open"></span> Detalhes</a></li>  
        </ul>
    </div>
</nav>
<h1 class="header">
{!! 
    titulo(
        $model->codtipoproduto,
        [
            url("tipo-produto/$model->codtipoproduto") => $model->tipoproduto,
            'Alterar',
        ],
        $model->inativo
    ) 
!!}     
</h1>
<hr>
<br>
{!! Form::model($model, ['method' => 'PATCH', 'class' => 'form-horizontal', 'id' => 'form-tipo-produto', 'action' => ['TipoProdutoController@update', $model->codtipoproduto] ]) !!}
    @include('errors.form_error')
    @include('tipo-produto.form', ['submitTextButton' => 'Salvar'])
{!! Form::close() !!}
@stop