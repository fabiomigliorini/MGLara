@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li><a href="{{ url("tributacao") }}"><span class="glyphicon glyphicon-list-alt"></span> Listagem</a></li>
        </ul>
    </div>
</nav>
<ol class="breadcrumb header">Nova Tributação</ol>
<hr>
<br>
{!! Form::open(['method' => 'POST', 'class' => 'form-horizontal', 'id' => 'form-tributacao', 'route' => 'tributacao.store']) !!}
    @include('errors.form_error')
    @include('tributacao.form', ['submitTextButton' => 'Salvar'])
 {!! Form::close() !!}   
@stop