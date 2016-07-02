@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li><a href="{{ url("secao-produto/$model->codsecaoproduto") }}"><span class="glyphicon glyphicon-list-alt"></span> Listagem</a></li>             
            <li><a href="{{ url("familia-produto/create?codsecaoproduto=$model->codsecaoproduto") }}"><span class="glyphicon glyphicon-plus"></span> Novo</a></li>             
            <li><a href="{{ url("familia-produto/$model->codfamiliaproduto") }}"><span class="glyphicon glyphicon-eye-open"></span> Detalhes</a></li>  
        </ul>
    </div>
</nav>
<h1 class="header">
{!! 
    titulo(
        $model->codfamiliaproduto,
        [
            url("secao-produto/$model->codsecaoproduto") => $model->SecaoProduto->secaoproduto,
            url("familia-produto/$model->codfamiliaproduto") => $model->familiaproduto,
            'Alterar'
        ],
        $model->inativo
    ) 
!!}  
</h1>
<hr>
<br>
{!! Form::model($model, ['method' => 'PATCH', 'class' => 'form-horizontal', 'id' => 'form-familia-produto', 'action' => ['FamiliaProdutoController@update', $model->codfamiliaproduto] ]) !!}
    @include('errors.form_error')
    @include('familia-produto.form', ['submitTextButton' => 'Salvar'])
{!! Form::close() !!}
@stop