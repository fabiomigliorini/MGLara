@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li><a href="{{ url("tipo-produto") }}"><span class="glyphicon glyphicon-list-alt"></span> Listagem</a></li>
        </ul>
    </div>
</nav>
<h1 class="header">
{!! 
    titulo(
        null,
        [
            url("secao-produto") => 'Tipos de Produto',
            'Novo Tipo de Produto',
        ],
        $model->inativo
    ) 
!!}   
</h1>
<hr>
<br>
{!! Form::model($model, ['method' => 'POST', 'class' => 'form-horizontal', 'id' => 'form-tipo-produto', 'route' => 'tipo-produto.store']) !!}
    @include('errors.form_error')
    @include('tipo-produto.form', ['submitTextButton' => 'Salvar'])
 {!! Form::close() !!}   
@stop