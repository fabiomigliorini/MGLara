@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li><a href="{{ url("unidade-medida") }}"><span class="glyphicon glyphicon-list-alt"></span> Listagem</a></li>
        </ul>
    </div>
</nav>
<ol class="breadcrumb header">Nova unidade de medida</ol>
<hr>
<br>
{!! Form::model($model, ['method' => 'POST', 'class' => 'form-horizontal', 'id' => 'form-unidade-medida', 'route' => 'unidade-medida.store']) !!}
    @include('errors.form_error')
    @include('unidade-medida.form', ['submitTextButton' => 'Salvar'])
 {!! Form::close() !!}   
@stop