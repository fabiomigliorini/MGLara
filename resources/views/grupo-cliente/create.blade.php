@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li><a href="{{ url("grupo-cliente") }}"><span class="glyphicon glyphicon-list-alt"></span> Listagem</a></li>
        </ul>
    </div>
</nav>
<ol class="breadcrumb header">Novo grupo de cliente</ol>
<hr>
<br>
{!! Form::model($model, ['method' => 'POST', 'class' => 'form-horizontal', 'id' => 'form-grupo-cliente', 'route' => 'grupo-cliente.store']) !!}
    @include('errors.form_error')
    @include('grupo-cliente.form', ['submitTextButton' => 'Salvar'])
 {!! Form::close() !!}   
@stop