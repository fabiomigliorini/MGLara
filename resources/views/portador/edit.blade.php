@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li><a href="{{ url('portador') }}"><span class="glyphicon glyphicon-list-alt"></span> Listagem</a></li>             
            <li><a href="{{ url('portador/create') }}"><span class="glyphicon glyphicon-plus"></span> Novo</a></li>             
            <li><a href="{{ url("portador/$model->codportador") }}"><span class="glyphicon glyphicon-eye-open"></span> Detalhes</a></li>  
        </ul>
    </div>
</nav>
<ol class="breadcrumb header">Alterar portador: {{$model->portador}}</ol>
<hr>
<br>
{!! Form::model($model, ['method' => 'PATCH', 'class' => 'form-horizontal', 'id' => 'form-portador', 'action' => ['PortadorController@update', $model->codportador] ]) !!}
    @include('errors.form_error')
    @include('portador.form', ['submitTextButton' => 'Salvar'])
{!! Form::close() !!}
@stop