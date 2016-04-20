@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li><a href="{{ url("grupo-cliente") }}"><span class="glyphicon glyphicon-list-alt"></span> Listagem</a></li>
        </ul>
    </div>
</nav>
<h1 class="header">Novo grupo de cliente</h1>
<hr>
<br>
{!! Form::model($model, ['method' => 'POST', 'class' => 'form-horizontal', 'id' => 'form-grupo-cliente', 'route' => 'grupo-cliente.store']) !!}
    @include('errors.form_error')
    @include('grupo-cliente.form', ['submitTextButton' => 'Salvar'])
 {!! Form::close() !!}   
@stop