@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li><a href="{{ url('secao-produto') }}"><span class="glyphicon glyphicon-list-alt"></span> Listagem</a></li>             
            <li><a href="{{ url('secao-produto/create') }}"><span class="glyphicon glyphicon-plus"></span> Novo</a></li>             
            <li><a href="{{ url("secao-produto/$model->codsecaoproduto") }}"><span class="glyphicon glyphicon-eye-open"></span> Detalhes</a></li>  
        </ul>
    </div>
</nav>
<h1 class="header">
{!! 
    breadcrumb(
        null,
        ['id' => $model->codsecaoproduto, 'label' => "Alterar Seção: $model->secaoproduto"]
    ) 
!!}     
</h1>
<hr>
<br>
{!! Form::model($model, ['method' => 'PATCH', 'class' => 'form-horizontal', 'id' => 'form-secao-produto', 'action' => ['SecaoProdutoController@update', $model->codsecaoproduto] ]) !!}
    @include('errors.form_error')
    @include('secao-produto.form', ['submitTextButton' => 'Salvar'])
{!! Form::close() !!}
@stop