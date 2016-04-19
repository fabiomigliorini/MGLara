@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li><a href="{{ url("pais/$request->codpais") }}"><span class="glyphicon glyphicon-list-alt"></span> Listagem</a></li>
        </ul>
    </div>
</nav>
<h1 class="header">Novo Estado</h1>
<hr>
<br>
{!! Form::model($model, ['method' => 'POST', 'class' => 'form-horizontal', 'id' => 'form-estado', 'route' => ['estado.store', 'codpais'=> $request->codpais]]) !!}
    @include('errors.form_error')
    @include('estado.form', ['submitTextButton' => 'Salvar'])
{!! Form::close() !!}   
@stop