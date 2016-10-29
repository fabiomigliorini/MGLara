@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li><a href="{{ url("ROTA") }}"><span class="glyphicon glyphicon-list-alt"></span> Listagem</a></li>
        </ul>
    </div>
</nav>
<ol class="breadcrumb header">Novo Produto</ol>
<hr>
<br>
{!! Form::model($model, ['method' => 'POST', 'class' => 'form-horizontal', 'id' => 'form-ROTA', 'route' => 'ROTA.store']) !!}
    @include('errors.form_error')
    @include('ROTA.form', ['submitTextButton' => 'Salvar'])
 {!! Form::close() !!}   
@stop