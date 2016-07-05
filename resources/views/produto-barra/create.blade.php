@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li><a href="{{ url("produto/$produto->codproduto") }}"><span class="glyphicon glyphicon-list-alt"></span> Listagem</a></li>
        </ul>
    </div>
</nav>
<h1 class="header">
{!! 
    titulo(
        $produto->codproduto,
        [
            url("produto") => 'Produtos',
            url("produto/$produto->codproduto") => $produto->produto,
            'Novo Código de Barras',
        ],
        $model->inativo,
        6
    ) 
!!}     
</h1>
<hr>
<br>
{!! Form::model($model, ['method' => 'POST', 'class' => 'form-horizontal', 'id' => 'form-produto-barra', 'route' => ['produto-barra.store', 'codproduto' => $produto->codproduto]]) !!}
    @include('errors.form_error')
    @include('produto-barra.form', ['submitTextButton' => 'Salvar'])
 {!! Form::close() !!}   
@stop