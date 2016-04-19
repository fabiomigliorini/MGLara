@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li><a href="{{ url("estado/$model->codestado") }}"><span class="glyphicon glyphicon-list-alt"></span> Listagem</a></li>             
            <li><a href="{{ url("cidade/create?codestado=$model->codestado") }}"><span class="glyphicon glyphicon-plus"></span> Novo</a></li>             
            <li><a href="{{ url("cidade/$model->codcidade") }}"><span class="glyphicon glyphicon-eye-open"></span> Detalhes</a></li>  
        </ul>
    </div>
</nav>
<h1 class="header">Alterar cidade: {{$model->cidade}}</h1>
<hr>
<br>
{!! Form::model($model, ['method' => 'PATCH', 'class' => 'form-horizontal', 'id' => 'form-cidade', 'action' => ['CidadeController@update', $model->codcidade] ]) !!}
    @include('errors.form_error')
    @include('cidade.form', ['submitTextButton' => 'Salvar'])
{!! Form::close() !!}
@stop