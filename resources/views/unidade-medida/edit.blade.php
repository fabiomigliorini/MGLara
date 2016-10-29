@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li><a href="{{ url('unidade-medida') }}"><span class="glyphicon glyphicon-list-alt"></span> Listagem</a></li>             
            <li><a href="{{ url('unidade-medida/create') }}"><span class="glyphicon glyphicon-plus"></span> Novo</a></li>             
            <li><a href="{{ url("unidade-medida/$model->codunidademedida") }}"><span class="glyphicon glyphicon-eye-open"></span> Detalhes</a></li>  
        </ul>
    </div>
</nav>
<ol class="breadcrumb header">Alterar unidade de medida: {{$model->unidademedida}}</ol>
<hr>
<br>
{!! Form::model($model, ['method' => 'PATCH', 'class' => 'form-horizontal', 'id' => 'form-unidade-medida', 'action' => ['UnidadeMedidaController@update', $model->codunidademedida] ]) !!}
    @include('errors.form_error')
    @include('unidade-medida.form', ['submitTextButton' => 'Salvar'])
{!! Form::close() !!}
@stop