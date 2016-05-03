@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li><a href="{{ url('ROTA') }}"><span class="glyphicon glyphicon-list-alt"></span> Listagem</a></li>             
            <li><a href="{{ url('ROTA/create') }}"><span class="glyphicon glyphicon-plus"></span> Novo</a></li>             
            <li><a href="{{ url("ROTA/$model->codROTA") }}"><span class="glyphicon glyphicon-eye-open"></span> Detalhes</a></li>  
        </ul>
    </div>
</nav>
<h1 class="header">Alterar ENTIDADE: {{$model->ROTA}}</h1>
<hr>
<br>
{!! Form::model($model, ['method' => 'PATCH', 'class' => 'form-horizontal', 'id' => 'form-ROTA', 'action' => ['ENTIDADEController@update', $model->codROTA] ]) !!}
    @include('errors.form_error')
    @include('ROTA.form', ['submitTextButton' => 'Salvar'])
{!! Form::close() !!}
@stop