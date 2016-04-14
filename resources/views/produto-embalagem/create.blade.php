@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li><a href="{{ url("produto/$request->codproduto") }}"><span class="glyphicon glyphicon-list-alt"></span> Listagem</a></li>
        </ul>
    </div>
</nav>
<h1 class="header">Nova Embalagem</h1>
<hr>
<br>
{!! Form::model($model, ['method' => 'POST', 'class' => 'form-horizontal', 'id' => 'form-produto-embalagem', 'route' => ['produto-embalagem.store', 'codproduto' => $request->codproduto]]) !!}
    @include('errors.form_error')
    @include('produto-embalagem.form', ['submitTextButton' => 'Salvar'])
 {!! Form::close() !!}   
@stop