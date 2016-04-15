@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li><a href="{{ url("produto/$model->codproduto") }}"><span class="glyphicon glyphicon-list-alt"></span> Listagem</a></li>
            <li><a href="{{ url("produto-barra/create?codproduto=$model->codproduto") }}"><span class="glyphicon glyphicon-plus"></span> Nova</a></li>
        </ul>
    </div>
</nav>
<h1 class="header">Alterar Código de Barras: {{ $model->Produto->produto }}</h1>
<hr>
<br>
{!! Form::model($model, ['method' => 'PATCH', 'class' => 'form-horizontal', 'id' => 'form-produto-barra', 'action' => ['ProdutoEmbalagemController@update', $model->codprodutoembalagem] ]) !!}
    @include('errors.form_error')
    @include('produto-barra.form', ['submitTextButton' => 'Salvar'])
{!! Form::close() !!}
@stop