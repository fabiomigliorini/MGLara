@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li><a href="{{ url("pessoa") }}"><span class="glyphicon glyphicon-list-alt"></span> Listagem</a></li>
        </ul>
    </div>
</nav>
<ol class="breadcrumb header">Nova Pessoa</ol>
<hr>
<br>
{!! Form::model($model, ['method' => 'POST', 'class' => 'form-horizontal', 'id' => 'form-pessoa', 'route' => 'pessoa.store']) !!}
    @include('errors.form_error')
    @include('pessoa.form', ['submitTextButton' => 'Salvar'])
 {!! Form::close() !!}   
@stop