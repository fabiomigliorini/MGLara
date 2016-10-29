@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li><a href="{{ url("estado/$request->codestado") }}"><span class="glyphicon glyphicon-list-alt"></span> Listagem</a></li>
        </ul>
    </div>
</nav>
<ol class="breadcrumb header">Nova Cidade</ol>
<hr>
<br>
{!! Form::model($model, ['method' => 'POST', 'class' => 'form-horizontal', 'id' => 'form-cidade', 'route' => ['cidade.store', 'codestado'=> $request->codestado]]) !!}
    @include('errors.form_error')
    @include('cidade.form', ['submitTextButton' => 'Salvar'])
{!! Form::close() !!}   
@stop