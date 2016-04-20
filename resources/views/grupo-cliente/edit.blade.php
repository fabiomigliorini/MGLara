@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li><a href="{{ url('grupo-cliente') }}"><span class="glyphicon glyphicon-list-alt"></span> Listagem</a></li>             
            <li><a href="{{ url('grupo-cliente/create') }}"><span class="glyphicon glyphicon-plus"></span> Novo</a></li>             
            <li><a href="{{ url("grupo-cliente/$model->codgrupocliente") }}"><span class="glyphicon glyphicon-eye-open"></span> Detalhes</a></li>  
        </ul>
    </div>
</nav>
<h1 class="header">Alterar grupo de cliente: {{$model->grupocliente}}</h1>
<hr>
<br>
{!! Form::model($model, ['method' => 'PATCH', 'class' => 'form-horizontal', 'id' => 'form-grupo-cliente', 'action' => ['GrupoClienteController@update', $model->codgrupocliente] ]) !!}
    @include('errors.form_error')
    @include('grupo-cliente.form', ['submitTextButton' => 'Salvar'])
{!! Form::close() !!}
@stop